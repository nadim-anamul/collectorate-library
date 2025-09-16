<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $role;

    public function __construct(User $user, string $role)
    {
        $this->user = $user;
        $this->role = $role;
    }

    public function build()
    {
        return $this->subject('Library Account Approved - Welcome!')
                    ->view('emails.user-approved')
                    ->with([
                        'userName' => $this->user->name,
                        'userEmail' => $this->user->email,
                        'role' => $this->role,
                        'loginUrl' => route('login'),
                    ]);
    }
}