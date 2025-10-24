<?php

namespace App\Mail;

use App\Models\Models\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoanOverdue extends Mailable
{
    use Queueable, SerializesModels;

    public $loan;
    public $daysOverdue;

    public function __construct(Loan $loan, int $daysOverdue)
    {
        $this->loan = $loan;
        $this->daysOverdue = $daysOverdue;
    }

    public function build()
    {
        $book = $this->loan->book;
        $user = $this->loan->user;
        
        return $this->subject('URGENT: Overdue Book - ' . $book->title_en)
                    ->view('emails.loan-overdue')
                    ->with([
                        'userName' => $user->name,
                        'bookTitle' => $book->title_en,
                        'bookTitleBn' => $book->title_bn,
                        'dueAt' => $this->loan->due_at,
                        'daysOverdue' => $this->daysOverdue,
                        'loanId' => $this->loan->id,
                        'dashboardUrl' => route('dashboard'),
                        'contactEmail' => config('mail.from.address'),
                    ]);
    }
}

