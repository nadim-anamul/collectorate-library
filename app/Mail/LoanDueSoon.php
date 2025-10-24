<?php

namespace App\Mail;

use App\Models\Models\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoanDueSoon extends Mailable
{
    use Queueable, SerializesModels;

    public $loan;
    public $daysRemaining;

    public function __construct(Loan $loan, int $daysRemaining)
    {
        $this->loan = $loan;
        $this->daysRemaining = $daysRemaining;
    }

    public function build()
    {
        $book = $this->loan->book;
        $user = $this->loan->user;
        
        return $this->subject('Reminder: Book Due Soon - ' . $book->title_en)
                    ->view('emails.loan-due-soon')
                    ->with([
                        'userName' => $user->name,
                        'bookTitle' => $book->title_en,
                        'bookTitleBn' => $book->title_bn,
                        'dueAt' => $this->loan->due_at,
                        'daysRemaining' => $this->daysRemaining,
                        'loanId' => $this->loan->id,
                        'dashboardUrl' => route('dashboard'),
                    ]);
    }
}

