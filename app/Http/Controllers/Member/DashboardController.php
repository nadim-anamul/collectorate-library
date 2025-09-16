<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Models\Book;
use App\Models\Models\Loan;
use App\Models\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Find the member record for this user (if exists)
        $member = Member::where('email', $user->email)->first();
        
        if ($member) {
            // Get user's current loans
            $currentLoans = Loan::where('member_id', $member->id)
                               ->whereNull('returned_at')
                               ->with(['book.category', 'book.authors', 'book.publisher', 'book.language'])
                               ->latest()
                               ->get();
            
            // Get user's loan history
            $loanHistory = Loan::where('member_id', $member->id)
                              ->whereNotNull('returned_at')
                              ->with(['book.category', 'book.authors', 'book.publisher', 'book.language'])
                              ->latest()
                              ->limit(5)
                              ->get();
            
            // Get recommended books (popular books user hasn't borrowed)
            $borrowedBookIds = Loan::where('member_id', $member->id)->pluck('book_id');
            $recommendedBooks = Book::whereNotIn('id', $borrowedBookIds)
                                   ->where('available_copies', '>', 0)
                                   ->with(['category', 'authors', 'publisher', 'language'])
                                   ->inRandomOrder()
                                   ->limit(6)
                                   ->get();
            
            // Statistics
            $stats = [
                'current_loans' => $currentLoans->count(),
                'total_borrowed' => Loan::where('member_id', $member->id)->count(),
                'overdue_books' => $currentLoans->where('due_at', '<', now())->count(),
            ];
        } else {
            // User doesn't have a member record yet
            $currentLoans = collect();
            $loanHistory = collect();
            $recommendedBooks = Book::where('available_copies', '>', 0)
                                   ->with(['category', 'authors', 'publisher', 'language'])
                                   ->inRandomOrder()
                                   ->limit(6)
                                   ->get();
            
            $stats = [
                'current_loans' => 0,
                'total_borrowed' => 0,
                'overdue_books' => 0,
            ];
        }
        
        return view('member.dashboard', compact('currentLoans', 'loanHistory', 'recommendedBooks', 'stats', 'user', 'member'));
    }
}