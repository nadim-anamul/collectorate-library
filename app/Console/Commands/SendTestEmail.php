<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestEmail;

class SendTestEmail extends Command
{
    protected $signature = 'mail:test {email}';
    protected $description = 'Send a test email to verify SMTP configuration';

    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info("Sending test email to {$email}...");
        
        try {
            Mail::to($email)->send(new TestEmail());
            $this->info("✓ Test email sent successfully!");
            $this->info("Please check {$email} for the test email.");
        } catch (\Exception $e) {
            $this->error("✗ Failed to send test email!");
            $this->error("Error: " . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}

