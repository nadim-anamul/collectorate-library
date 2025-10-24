<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Models\Loan;
use App\Mail\LoanDueSoon;
use App\Mail\LoanOverdue;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class CheckLoansDue extends Command
{
    protected $signature = 'loans:check-due';
    protected $description = 'Check for loans that are due soon or overdue and send email reminders';

    public function handle()
    {
        $this->info('Checking for due and overdue loans...');
        
        $dueSoonCount = $this->checkDueSoon();
        $overdueCount = $this->checkOverdue();
        
        $this->info("✓ Sent {$dueSoonCount} due soon reminder(s)");
        $this->info("✓ Sent {$overdueCount} overdue notice(s)");
        $this->info('Done!');
        
        return 0;
    }
    
    private function checkDueSoon()
    {
        $count = 0;
        $today = Carbon::today();
        
        // Check loans due in 1, 2, or 3 days
        foreach ([1, 2, 3] as $days) {
            $targetDate = $today->copy()->addDays($days);
            
            $loans = Loan::where('status', 'issued')
                ->whereNull('returned_at')
                ->whereDate('due_at', $targetDate->toDateString())
                ->with(['user', 'book'])
                ->get();
            
            foreach ($loans as $loan) {
                try {
                    Mail::to($loan->user->email)->send(new LoanDueSoon($loan, $days));
                    $this->line("  - Sent due soon reminder to {$loan->user->email} for book #{$loan->book_id} (due in {$days} days)");
                    $count++;
                } catch (\Exception $e) {
                    $this->error("  ✗ Failed to send due soon email to {$loan->user->email}: " . $e->getMessage());
                }
            }
        }
        
        return $count;
    }
    
    private function checkOverdue()
    {
        $count = 0;
        $today = Carbon::today();
        
        // Get all overdue loans
        $loans = Loan::where('status', 'issued')
            ->whereNull('returned_at')
            ->where('due_at', '<', $today->toDateString())
            ->with(['user', 'book'])
            ->get();
        
        foreach ($loans as $loan) {
            $dueDate = Carbon::parse($loan->due_at);
            $daysOverdue = $today->diffInDays($dueDate);
            
            try {
                Mail::to($loan->user->email)->send(new LoanOverdue($loan, $daysOverdue));
                $this->line("  - Sent overdue notice to {$loan->user->email} for book #{$loan->book_id} ({$daysOverdue} days overdue)");
                $count++;
            } catch (\Exception $e) {
                $this->error("  ✗ Failed to send overdue email to {$loan->user->email}: " . $e->getMessage());
            }
        }
        
        return $count;
    }
}

