<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Sends the daily Wash Report email and re-schedules itself for the next day.
 *
 * The schedule lives 100% inside Laravel (the `jobs` table) - no Windows Task
 * Scheduler / cron entry is needed. The queue worker keeps it alive:
 *
 *     php artisan queue:work --timeout=900
 *
 * The job is auto-(re)armed by AppServiceProvider via Queue::looping(), so even
 * if the jobs table is ever cleared, the worker re-arms the chain automatically.
 */
class SendWashReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** Daily send time (app timezone - config/app.php => Asia/Dhaka) */
    public const RUN_AT = '11:00';

    /** Must be longer than the job needs (PDF + SMTP to several recipients) */
    public $timeout = 900;

    /** Cache keys used to prevent duplicate runs / duplicate queued jobs */
    protected const LAST_SENT_KEY = 'wash_report:last_sent_date';
    protected const ARMED_UNTIL_KEY = 'wash_report:armed_until';

    public function handle(): void
    {
        try {
            // Idempotency guard: never send the daily report twice on the same day
            if (Cache::get(self::LAST_SENT_KEY) === now()->toDateString()) {
                Log::info('Wash Report skipped - already sent today.');
            } else {
                Artisan::call('report:send-wash', ['--period' => 'daily']);

                Cache::put(self::LAST_SENT_KEY, now()->toDateString(), now()->addDays(2));
            }
        } catch (\Throwable $e) {
            // Never let an error break the chain - always re-schedule below
            Log::error('Wash Report job error: ' . $e->getMessage());
        } finally {
            self::arm();
        }
    }

    /**
     * Queue the next occurrence (if not already queued).
     * Safe to call as often as you like - it will not stack duplicates.
     *
     * If RUN_AT was changed in code, this automatically re-times the schedule:
     * the stale pending copy is removed and a fresh one is queued for the new
     * time. Only requirement: restart the queue worker after changing RUN_AT.
     */
    public static function arm(): void
    {
        // Guard against accidental immediate execution when the app still
        // runs on the "sync" queue driver (delayed dispatch on sync = runs NOW).
        if (config('queue.default') === 'sync') {
            Log::warning('SendWashReportJob not armed: QUEUE_CONNECTION is "sync". Set QUEUE_CONNECTION=database.');

            return;
        }

        $nextRun = self::nextRunAt();

        try {
            $pendingJobs = DB::table('jobs')
                ->where('payload', 'like', '%SendWashReportJob%')
                ->whereNull('reserved_at')
                ->orderBy('available_at')
                ->get();

            $futureJob = $pendingJobs->firstWhere('available_at', '>', now()->getTimestamp());
            $isDue = $pendingJobs->contains(fn ($job) => $job->available_at <= now()->getTimestamp());

            // Correctly armed: a future occurrence is queued for the intended time
            if ($futureJob && (int) $futureJob->available_at === $nextRun->getTimestamp()) {
                return;
            }

            // Today's occurrence is due/overdue - the worker is about to process
            // it and its finally-block will re-arm. NEVER delete a due job.
            if ($isDue) {
                return;
            }

            // Schedule changed (RUN_AT) or armed job went missing: (re)arm.
            if ($futureJob) {
                DB::table('jobs')->where('id', $futureJob->id)->delete();
            }

            self::dispatch()->delay($nextRun);

            // Re-arm window expires shortly after the scheduled run, so if the job
            // somehow never executes, the worker will queue a fresh one.
            Cache::put(self::ARMED_UNTIL_KEY, $nextRun->toDateTimeString(), $nextRun->copy()->addDay());

            Log::info("Wash Report scheduled. Next run at {$nextRun->format('Y-m-d H:i')} ({$nextRun->timezoneName}).");
        } catch (\Throwable $e) {
            Log::warning('Wash Report arming failed: ' . $e->getMessage());
        }
    }

    /**
     * Next 11:00 AM occurrence (today if still in the future, otherwise tomorrow).
     */
    public static function nextRunAt(): Carbon
    {
        $nextRun = now()->setTimeFromTimeString(self::RUN_AT);

        if ($nextRun->isPast()) {
            $nextRun->addDay();
        }

        return $nextRun;
    }
}
