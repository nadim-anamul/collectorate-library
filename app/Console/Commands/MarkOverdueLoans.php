<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Models\Loan;
use Carbon\Carbon;

class MarkOverdueLoans extends Command
{
    protected $signature = 'loans:mark-overdue';

    protected $description = 'Mark issued loans as overdue when past due date';

    public function handle(): int
    {
        $today = Carbon::today();
        $affected = Loan::whereNull('returned_at')
            ->whereNotNull('due_at')
            ->whereDate('due_at', '<', $today->toDateString())
            ->whereNull('overdue_at')
            ->update(['overdue_at' => $today->toDateString()]);

        $this->info("Marked {$affected} loans as overdue.");
        return Command::SUCCESS;
    }
}


