<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Models\Book;
use App\Models\Models\Loan;
use App\Models\Models\Member;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ActivityLogger;

class LoanController extends Controller
{
    public function index()
    {
        $loans = Loan::with(['member','book'])->latest()->paginate(20);
        return view('admin.loans.index', compact('loans'));
    }

    public function create()
    {
        $members = Member::orderBy('name')->get();
        $books = Book::orderBy('title_en')->get();
        return view('admin.loans.create', compact('members','books'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'book_id' => 'required|exists:books,id',
            'issued_at' => 'required|date',
            'due_at' => 'required|date|after_or_equal:issued_at',
        ]);

        $book = Book::findOrFail($validated['book_id']);
        if($book->available_copies < 1){
            return back()->withErrors(['book_id' => 'No available copies']);
        }

        $loan = Loan::create([
            'book_id' => $book->id,
            'member_id' => $validated['member_id'],
            'issued_by_user_id' => Auth::id(),
            'issued_at' => $validated['issued_at'],
            'due_at' => $validated['due_at'],
            'status' => 'issued',
        ]);

        $book->decrement('available_copies');
        ActivityLogger::log('loan.issued','Loan',$loan->id,['book_id' => $book->id, 'member_id' => $validated['member_id']]);
        return redirect()->route('admin.loans.index')->with('status','Book issued');
    }

    public function return(Loan $loan, Request $request)
    {
        if($loan->status === 'returned'){
            return back();
        }
        $returnedAt = Carbon::now();
        $lateDays = 0;
        $lateFee = 0;
        if($returnedAt->gt(Carbon::parse($loan->due_at))){
            $lateDays = Carbon::parse($loan->due_at)->diffInDays($returnedAt);
            $lateFee = $lateDays * 5; // 5 currency units per day
        }
        $loan->update([
            'returned_at' => $returnedAt->toDateString(),
            'late_days' => $lateDays,
            'late_fee' => $lateFee,
            'status' => 'returned',
        ]);

        $loan->book()->increment('available_copies');
        ActivityLogger::log('loan.returned','Loan',$loan->id,['late_fee' => $lateFee]);

        return back()->with('status','Book returned');
    }
}
