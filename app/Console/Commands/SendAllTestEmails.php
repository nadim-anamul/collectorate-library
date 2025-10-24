<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestEmail;
use App\Mail\UserApproved;
use App\Mail\UserRejected;
use App\Mail\LoanIssued;
use App\Mail\LoanApproved;
use App\Mail\LoanDeclined;
use App\Mail\LoanDueSoon;
use App\Mail\LoanOverdue;
use App\Models\User;
use App\Models\Models\Loan;
use App\Models\Models\Book;
use Carbon\Carbon;

class SendAllTestEmails extends Command
{
    protected $signature = 'mail:test-all {email}';
    protected $description = 'Send all email templates with sample data to test email address';

    public function handle()
    {
        $email = $this->argument('email');
        $this->info("Sending all email templates to {$email}...");
        $this->newLine();

        // Create mock data
        $mockUser = new User([
            'name' => 'Test User',
            'email' => $email,
        ]);
        $mockUser->id = 1;
        $mockUser->exists = true;
        
        $mockBook = new Book([
            'title_en' => 'The Great Gatsby',
            'title_bn' => 'দ্য গ্রেট গ্যাটসবি',
            'isbn' => '978-0-7432-7356-5',
        ]);
        $mockBook->id = 1;
        $mockBook->exists = true;
        
        $mockLoan = new Loan([
            'book_id' => 1,
            'user_id' => 1,
            'issued_at' => Carbon::now()->format('Y-m-d'),
            'due_at' => Carbon::now()->addDays(14)->format('Y-m-d'),
            'status' => 'issued',
        ]);
        $mockLoan->id = 123;
        $mockLoan->exists = true;
        
        // Set relationships
        $mockLoan->setRelation('book', $mockBook);
        $mockLoan->setRelation('user', $mockUser);

        // 1. Test Email
        $this->sendEmail('Test Email', function() use ($email) {
            Mail::to($email)->send(new TestEmail());
        });

        // 2. User Approved Email
        $this->sendEmail('User Account Approved', function() use ($email, $mockUser) {
            Mail::to($email)->send(new UserApproved($mockUser, 'Member'));
        });

        // 3. User Rejected Email
        $this->sendEmail('User Account Rejected', function() use ($email, $mockUser) {
            Mail::to($email)->send(new UserRejected($mockUser, 'Incomplete registration information. Please ensure all required fields are filled correctly and reapply.'));
        });

        // 4. Loan Issued Email
        $this->sendEmail('Loan Issued', function() use ($email, $mockLoan) {
            Mail::to($email)->send(new LoanIssued($mockLoan));
        });

        // 5. Loan Approved Email
        $this->sendEmail('Loan Request Approved', function() use ($email, $mockLoan) {
            Mail::to($email)->send(new LoanApproved($mockLoan));
        });

        // 6. Loan Declined Email
        $this->sendEmail('Loan Request Declined', function() use ($email, $mockLoan) {
            Mail::to($email)->send(new LoanDeclined($mockLoan, 'Book is currently reserved for another member. Please try again in a few days or choose an alternative title.'));
        });

        // 7. Loan Due Soon Email (3 days)
        $this->sendEmail('Loan Due Soon (3 days)', function() use ($email, $mockLoan, $mockBook, $mockUser) {
            $dueLoan = clone $mockLoan;
            $dueLoan->id = 123;
            $dueLoan->due_at = Carbon::now()->addDays(3)->format('Y-m-d');
            $dueLoan->setRelation('book', $mockBook);
            $dueLoan->setRelation('user', $mockUser);
            Mail::to($email)->send(new LoanDueSoon($dueLoan, 3));
        });

        // 8. Loan Due Soon Email (1 day)
        $this->sendEmail('Loan Due Soon (1 day)', function() use ($email, $mockLoan, $mockBook, $mockUser) {
            $dueLoan = clone $mockLoan;
            $dueLoan->id = 123;
            $dueLoan->due_at = Carbon::now()->addDays(1)->format('Y-m-d');
            $dueLoan->setRelation('book', $mockBook);
            $dueLoan->setRelation('user', $mockUser);
            Mail::to($email)->send(new LoanDueSoon($dueLoan, 1));
        });

        // 9. Loan Overdue Email
        $this->sendEmail('Loan Overdue', function() use ($email, $mockLoan, $mockBook, $mockUser) {
            $overdueLoan = clone $mockLoan;
            $overdueLoan->id = 123;
            $overdueLoan->due_at = Carbon::now()->subDays(5)->format('Y-m-d');
            $overdueLoan->setRelation('book', $mockBook);
            $overdueLoan->setRelation('user', $mockUser);
            Mail::to($email)->send(new LoanOverdue($overdueLoan, 5));
        });

        $this->newLine();
        $this->info("✓ All email templates sent successfully to {$email}!");
        $this->info("Please check your inbox and spam folder.");
        $this->newLine();
        $this->info("Total emails sent: 9");

        return 0;
    }

    private function sendEmail(string $name, callable $callback)
    {
        try {
            $callback();
            $this->line("  ✓ {$name} - Sent");
        } catch (\Exception $e) {
            $this->error("  ✗ {$name} - Failed: " . $e->getMessage());
        }
    }
}

