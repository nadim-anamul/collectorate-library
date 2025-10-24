<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function build()
    {
        return $this->subject('Test Email - Library Management System')
                    ->view('emails.test-email')
                    ->with([
                        'testTime' => now()->format('Y-m-d H:i:s'),
                        'appName' => config('app.name', 'Library Management System'),
                    ]);
    }
}

