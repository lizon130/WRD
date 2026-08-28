<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * Daily 11:00 AM (Asia/Dhaka) Wash Report mail - runs from the server via
     * the standard cron entry (works 24/7, no IDE needed):
     *
     *     * * * * * cd /var/www/html/wrd && php artisan schedule:run >> /dev/null 2>&1
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('report:send-wash --period=daily')
            ->dailyAt('11:00')
            ->timezone('Asia/Dhaka');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
