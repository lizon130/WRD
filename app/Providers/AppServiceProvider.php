<?php

namespace App\Providers;

use App\Jobs\SendWashReportJob;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Every queue worker loop makes sure the daily Wash Report job is
        // scheduled - fully managed inside Laravel, no OS cron/Task Scheduler.
        Queue::looping(function () {
            SendWashReportJob::arm();
        });
    }
}
