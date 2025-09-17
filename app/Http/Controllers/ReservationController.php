<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Models\Book;
use App\Models\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function store(Book $book, Request $request)
    {
        $user = Auth::user();
        if (!$user) { return redirect()->route('login'); }
        if (($user->status ?? 'pending') !== 'approved') {
            return back()->with('error','Only approved users can reserve books.');
        }

        if ($book->available_copies > 0) {
            return back()->with('status','Book is available. You can request to borrow it directly.');
        }

        $exists = Reservation::where('book_id',$book->id)
            ->where('user_id',$user->id)
            ->where('status','active')
            ->first();
        if ($exists) {
            return back()->with('status','You already have an active reservation for this book.');
        }

        Reservation::create([
            'book_id' => $book->id,
            'user_id' => $user->id,
            'status' => 'active',
            'queued_at' => Carbon::now(),
        ]);

        return back()->with('status','Reservation placed. We will notify you when the book is available.');
    }
}


