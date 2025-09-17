<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Models\Book;
use App\Models\Models\Loan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ActivityLogger;
use App\Models\User as SystemUser;
use Illuminate\Support\Facades\Mail;
use App\Notifications\LoanEventNotification;
use App\Models\Models\Reservation;
use Illuminate\Support\Facades\Log;

class LoanController extends Controller
{
    public function index()
    {
        $query = Loan::with(['user','book'])->latest();

        // Filters
        $search = request('q');
        $status = request('status');
        $overdueOnly = request('overdue') === '1';
        $userId = request('user_id');
        $bookId = null; // removed per UX
        $requestedFrom = null; // removed per UX
        $requestedTo = null; // removed per UX
        $issuedFrom = request('issued_from');
        $issuedTo = request('issued_to');
        $dueFrom = request('due_from');
        $dueTo = request('due_to');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($uq) use ($search) {
                    $uq->where('name','like',"%{$search}%")
                       ->orWhere('email','like',"%{$search}%");
                })->orWhereHas('book', function($bq) use ($search) {
                    $bq->where('title_en','like',"%{$search}%")
                       ->orWhere('title_bn','like',"%{$search}%")
                       ->orWhere('isbn','like',"%{$search}%");
                });
            });
        }
        if ($status && in_array($status, ['pending','issued','returned','declined','return_requested'])) {
            $query->where('status', $status);
        }
        if ($userId) { $query->where('user_id', $userId); }
        if ($overdueOnly) {
            $query->whereNull('returned_at')
                  ->whereDate('due_at','<', now()->toDateString());
        }
        // book and requested date filters removed by request
        if ($issuedFrom) { $query->whereDate('issued_at','>=',$issuedFrom); }
        if ($issuedTo) { $query->whereDate('issued_at','<=',$issuedTo); }
        if ($dueFrom) { $query->whereDate('due_at','>=',$dueFrom); }
        if ($dueTo) { $query->whereDate('due_at','<=',$dueTo); }

        $loans = $query->paginate(20)->appends(request()->query());

        // For filter dropdowns
        $users = \App\Models\User::orderBy('name')->select('id','name','email')->get();
        return view('admin.loans.index', compact('loans','users','search','status','userId','issuedFrom','issuedTo','dueFrom','dueTo','overdueOnly'));
    }

    public function show(Loan $loan)
    {
        $loan->load(['user','book']);
        return view('admin.loans.show', compact('loan'));
    }

    public function create()
    {
        $users = \App\Models\User::where('status','approved')->orderBy('name')->get();
        $books = Book::orderBy('title_en')->get();
        return view('admin.loans.create', compact('users','books'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'issued_at' => 'required|date',
            'due_at' => 'required|date|after_or_equal:issued_at',
        ]);

        // Guard: Only approved users can be issued loans
        $borrower = \App\Models\User::findOrFail($validated['user_id']);
        if (($borrower->status ?? 'pending') !== 'approved') {
            return back()->withErrors(['user_id' => 'Only approved users can be issued books.']);
        }

        $book = Book::findOrFail($validated['book_id']);
        if($book->available_copies < 1){
            return back()->withErrors(['book_id' => 'No available copies']);
        }

        $loan = Loan::create([
            'book_id' => $book->id,
            'user_id' => $validated['user_id'],
            'issued_by_user_id' => Auth::id(),
            'issued_at' => $validated['issued_at'],
            'due_at' => $validated['due_at'],
            'status' => 'issued',
        ]);

        $book->decrement('available_copies');
        ActivityLogger::log('loan.issued','Loan',$loan->id,['book_id' => $book->id, 'user_id' => $validated['user_id']]);
        try {
            Mail::raw("Your loan for '{$book->title_en}' has been issued.", function($m) use ($borrower){ $m->to($borrower->email)->subject('Loan Issued'); });
        } catch (\Throwable $e) { Log::error($e->getMessage()); }
        return redirect()->route('admin.loans.index')->with('status','Book issued');
    }

    public function return(Loan $loan, Request $request)
    {
        if($loan->status === 'returned'){
            return back();
        }
        $returnedAt = Carbon::now();
        $loan->update([
            'returned_at' => $returnedAt->toDateString(),
            'status' => 'returned',
        ]);

        $loan->book()->increment('available_copies');
        ActivityLogger::log('loan.returned','Loan',$loan->id,[]);
        // Fulfill next reservation if any
        try {
            $book = $loan->book;
            $nextReservation = Reservation::where('book_id', $book->id)
                ->where('status','active')
                ->orderBy('queued_at')
                ->first();
            if ($nextReservation) {
                // Create a pending loan for the reserved user and mark reservation fulfilled
                \App\Models\Models\Loan::create([
                    'book_id' => $book->id,
                    'user_id' => $nextReservation->user_id,
                    'status' => 'pending',
                    'requested_at' => now()->toDateString(),
                ]);
                $nextReservation->update(['status' => 'fulfilled', 'notified_at' => now()]);
            }
        } catch (\Throwable $e) { Log::error($e->getMessage()); }
        // Notify admins/librarians about return
        try {
            $admins = SystemUser::role(['Admin','Librarian'])->get();
            $book = $loan->book; $user = $loan->user;
            foreach ($admins as $admin) {
                $admin->notify(new LoanEventNotification('loan.returned', [
                    'message' => "Book returned: '{$book->title_en}' by {$user->name}",
                    'loan_id' => $loan->id,
                    'book_id' => $book->id,
                    'book_title' => $book->title_en,
                    'by_user_id' => $user->id,
                    'by_user_name' => $user->name,
                    'url' => '/admin/loans/' . $loan->id,
                ]));
            }
        } catch (\Throwable $e) { Log::error($e->getMessage()); }

        return back()->with('status','Book returned');
    }

    // User creates a borrow request (pending)
    public function request(\App\Models\Models\Book $book, Request $request)
    {
        $user = Auth::user();
        if (($user->status ?? 'pending') !== 'approved') {
            return back()->with('error','Only approved users can request books.');
        }
        $request->validate([
            'requested_due_at' => 'nullable|date|after_or_equal:today',
        ]);
        // Prevent duplicate active requests for same book
        $existing = Loan::where('user_id',$user->id)
                        ->where('book_id',$book->id)
                        ->whereIn('status',['pending','issued'])
                        ->first();
        if ($existing) {
            return back()->with('status','You already have a loan '.$existing->status.' for this book.');
        }
        // Optional: block if no copies; or allow waitlist. We proceed to allow pending requests always.
        $loan = Loan::create([
            'book_id' => $book->id,
            'user_id' => $user->id,
            'status' => 'pending',
            'issued_by_user_id' => null,
            'issued_at' => null,
            'due_at' => null,
            'requested_at' => Carbon::now()->toDateString(),
            'requested_due_at' => $request->input('requested_due_at'),
        ]);
        ActivityLogger::log('loan.requested','Loan',$loan->id,['book_id' => $book->id]);
        // Notify admins/librarians about new borrow request
        try {
            $admins = SystemUser::role(['Admin','Librarian'])->get();
            foreach ($admins as $admin) {
                $admin->notify(new LoanEventNotification('loan.requested', [
                    'message' => "New borrow request for '{$book->title_en}'",
                    'loan_id' => $loan->id,
                    'book_id' => $book->id,
                    'book_title' => $book->title_en,
                    'by_user_id' => $user->id,
                    'by_user_name' => $user->name,
                    'url' => '/admin/loans/' . $loan->id,
                ]));
            }
        } catch (\Throwable $e) { Log::error($e->getMessage()); }
        return back()->with('status','Borrow request submitted.');
    }

    // Admin approves a pending request
    public function approve(Request $request, Loan $loan)
    {
        if($loan->status !== 'pending'){
            return back()->with('error','Only pending requests can be approved.');
        }
        $book = $loan->book;
        if($book->available_copies < 1){
            return back()->with('error','No available copies to approve.');
        }
        $validated = $request->validate([
            'issued_at' => 'required|date',
            'due_at' => 'required|date|after_or_equal:issued_at',
        ]);
        $loan->update([
            'status' => 'issued',
            'issued_at' => $validated['issued_at'],
            'due_at' => $validated['due_at'],
            'issued_by_user_id' => Auth::id(),
        ]);
        $book->decrement('available_copies');
        ActivityLogger::log('loan.approved','Loan',$loan->id,[]);
        // Notify borrower about approval
        try {
            $borrower = $loan->user; $book = $loan->book;
            $borrower->notify(new LoanEventNotification('loan.approved', [
                'message' => "Your borrow request for '{$book->title_en}' has been approved.",
                'loan_id' => $loan->id,
                'book_id' => $book->id,
                'book_title' => $book->title_en,
                'by_user_id' => Auth::id(),
                'by_user_name' => Auth::user()->name,
                'url' => route('admin.loans.show', $loan),
            ]));
        } catch (\Throwable $e) { Log::error($e->getMessage()); }
        return back()->with('status','Loan approved and issued.');
    }

    // Admin declines a pending request
    public function decline(Loan $loan, Request $request)
    {
        if($loan->status !== 'pending'){
            return back()->with('error','Only pending requests can be declined.');
        }
        $validated = $request->validate([
            'decline_reason' => 'required|string|min:5',
        ]);
        $loan->update([
            'status' => 'declined',
            'decline_reason' => $validated['decline_reason'] ?? null,
        ]);
        ActivityLogger::log('loan.declined','Loan',$loan->id,[]);
        // Notify borrower about decline
        try {
            $borrower = $loan->user; $book = $loan->book;
            $borrower->notify(new LoanEventNotification('loan.declined', [
                'message' => "Your borrow request for '{$book->title_en}' has been declined.",
                'loan_id' => $loan->id,
                'book_id' => $book->id,
                'book_title' => $book->title_en,
                'by_user_id' => Auth::id(),
                'by_user_name' => Auth::user()->name,
                'url' => route('admin.loans.show', $loan),
            ]));
        } catch (\Throwable $e) { Log::error($e->getMessage()); }
        return back()->with('status','Loan request declined.');
    }

    // User requests return of an issued loan
    public function requestReturn(Loan $loan)
    {
        $user = Auth::user();
        if ($loan->user_id !== $user->id) {
            return back()->with('error','You can only request return for your own loans.');
        }
        if ($loan->status !== 'issued') {
            return back()->with('error','Only issued loans can be requested for return.');
        }
        $loan->update([
            'status' => 'return_requested',
        ]);
        ActivityLogger::log('loan.return_requested','Loan',$loan->id,[]);
        // Notify admins/librarians about return-request
        try {
            $admins = SystemUser::role(['Admin','Librarian'])->get();
            $book = $loan->book;
            foreach ($admins as $admin) {
                $admin->notify(new LoanEventNotification('loan.return_requested', [
                    'message' => "Return requested for '{$book->title_en}'",
                    'loan_id' => $loan->id,
                    'book_id' => $book->id,
                    'book_title' => $book->title_en,
                    'by_user_id' => $user->id,
                    'by_user_name' => $user->name,
                    'url' => '/admin/loans/' . $loan->id,
                ]));
            }
        } catch (\Throwable $e) { Log::error($e->getMessage()); }
        return back()->with('status','Return requested. Please hand the book to the librarian.');
    }
}
