<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * NOTE: The daily 11:00 Wash Report mail is NO LONGER scheduled here.
     * It is fully managed by App\Jobs\SendWashReportJob - a self-rescheduling
     * queue job. Just keep the queue worker running:
     *
     *     php artisan queue:work --timeout=900
     *
     * No Windows Task Scheduler / cron entry is required.
     */
    protected function schedule(Schedule $schedule): void
    {
        //
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
