<?php

namespace App\Mail;

use App\Models\Models\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoanDeclined extends Mailable
{
    use Queueable, SerializesModels;

    public $loan;
    public $reason;

    public function __construct(Loan $loan, string $reason = null)
    {
        $this->loan = $loan;
        $this->reason = $reason ?? 'No specific reason provided';
    }

    public function build()
    {
        $book = $this->loan->book;
        $user = $this->loan->user;
        
        return $this->subject('Book Request Declined - ' . $book->title_en)
                    ->view('emails.loan-declined')
                    ->with([
                        'userName' => $user->name,
                        'bookTitle' => $book->title_en,
                        'bookTitleBn' => $book->title_bn,
                        'reason' => $this->reason,
                        'dashboardUrl' => route('dashboard'),
                        'contactEmail' => config('mail.from.address'),
                    ]);
    }
}

