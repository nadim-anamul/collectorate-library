<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $reason;

    public function __construct(User $user, string $reason)
    {
        $this->user = $user;
        $this->reason = $reason;
    }

    public function build()
    {
        return $this->subject('Library Account Application Update')
                    ->view('emails.user-rejected')
                    ->with([
                        'userName' => $this->user->name,
                        'userEmail' => $this->user->email,
                        'reason' => $this->reason,
                        'contactEmail' => config('mail.from.address'),
                    ]);
    }
}