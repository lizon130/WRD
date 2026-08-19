<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */


    protected function schedule(Schedule $schedule): void
    {

        \Log::info('Scheduler running at: ' . now());

        // TEST ENTRY - Runs every minute
        //    $schedule->command('report:send-wash --period=daily')
        //         ->everyMinute();


            $schedule->command('report:send-wash --period=daily')
                ->dailyAt('11:00');
            }

    /**
     * Register the commands for the application.artisan schedule:run
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
