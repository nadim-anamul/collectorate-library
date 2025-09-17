<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Models\Book;
use App\Models\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Simplify: no separate Member. Use user-centric loans.
        // Current active loans (issued and not returned)
        $currentLoans = Loan::where('user_id', $user->id)
                           ->where('status', 'issued')
                           ->whereNull('returned_at')
                           ->with(['book.category', 'book.authors', 'book.publisher', 'book.language'])
                           ->latest()
                           ->get();

        // Pending borrow requests
        $pendingRequests = Loan::where('user_id', $user->id)
                               ->where('status', 'pending')
                               ->with(['book.category', 'book.authors', 'book.publisher', 'book.language'])
                               ->latest()
                               ->get();

        // Returned history
        $loanHistory = Loan::where('user_id', $user->id)
                          ->where('status', 'returned')
                          ->whereNotNull('returned_at')
                          ->with(['book.category', 'book.authors', 'book.publisher', 'book.language'])
                          ->latest()
                          ->limit(5)
                          ->get();

        // Recommended books (that user hasn't borrowed yet)
        $borrowedBookIds = Loan::where('user_id', $user->id)->pluck('book_id');
        $recommendedBooks = Book::whereNotIn('id', $borrowedBookIds)
                               ->where('available_copies', '>', 0)
                               ->with(['category', 'tags', 'language', 'primaryAuthor', 'publisher'])
                               ->inRandomOrder()
                               ->limit(6)
                               ->get();

        // Stats
        $stats = [
            'current_loans' => $currentLoans->count(),
            'total_borrowed' => Loan::where('user_id', $user->id)
                                     ->whereIn('status', ['issued','returned'])
                                     ->count(),
            'overdue_books' => $currentLoans->where('due_at', '<', now())->count(),
        ];

        return view('member.dashboard', compact('currentLoans', 'pendingRequests', 'loanHistory', 'recommendedBooks', 'stats', 'user'));
    }

    public function history()
    {
        $user = Auth::user();
        $q = request('q');
        $status = request('status');
        $issuedFrom = request('issued_from');
        $issuedTo = request('issued_to');
        $dueFrom = request('due_from');
        $dueTo = request('due_to');

        $query = Loan::where('user_id', $user->id)
                     ->whereIn('status', ['issued','returned','declined'])
                     ->with(['book.category','book.primaryAuthor','book.language'])
                     ->latest();

        if ($q) {
            $query->where(function($sub) use ($q){
                $sub->whereHas('book', function($b) use ($q){
                    $b->where('title_en','like',"%{$q}%")
                      ->orWhere('title_bn','like',"%{$q}%")
                      ->orWhere('isbn','like',"%{$q}%");
                });
            });
        }
        if ($status && in_array($status, ['issued','returned','declined'])) {
            $query->where('status',$status);
        }
        if ($issuedFrom) { $query->whereDate('issued_at','>=',$issuedFrom); }
        if ($issuedTo) { $query->whereDate('issued_at','<=',$issuedTo); }
        if ($dueFrom) { $query->whereDate('due_at','>=',$dueFrom); }
        if ($dueTo) { $query->whereDate('due_at','<=',$dueTo); }

        $history = $query->paginate(12)->appends(request()->query());
        return view('member.history', compact('history','user','q','status','issuedFrom','issuedTo','dueFrom','dueTo'));
    }
}