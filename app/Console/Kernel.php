<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Check and send email reminders for due and overdue loans every day at 8 AM
        $schedule->command('loans:check-due')->dailyAt('08:00');
        
        $schedule->command('loans:mark-overdue')->dailyAt('01:00');
        // Optional: backfill notification URLs nightly (safe no-op if already correct)
        $schedule->command('notifications:backfill-loan-urls')->dailyAt('02:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
