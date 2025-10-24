<?php

namespace App\Mail;

use App\Models\Models\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoanApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $loan;

    public function __construct(Loan $loan)
    {
        $this->loan = $loan;
    }

    public function build()
    {
        $book = $this->loan->book;
        $user = $this->loan->user;
        
        return $this->subject('Book Request Approved - ' . $book->title_en)
                    ->view('emails.loan-approved')
                    ->with([
                        'userName' => $user->name,
                        'bookTitle' => $book->title_en,
                        'bookTitleBn' => $book->title_bn,
                        'issuedAt' => $this->loan->issued_at,
                        'dueAt' => $this->loan->due_at,
                        'loanId' => $this->loan->id,
                        'dashboardUrl' => route('dashboard'),
                    ]);
    }
}

