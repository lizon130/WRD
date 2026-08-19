<?php

namespace App\Console\Commands;

use App\Http\Controllers\WashReportDashboardController;
use App\Mail\WashReportMail;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class SendWashReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:send-wash 
                            {--from= : From date (Y-m-d)} 
                            {--to= : To date (Y-m-d)}
                            {--emails= : Comma separated email addresses}
                            {--period=daily : Report period (daily/weekly/monthly)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate and send Wash Report PDF via email';

    /**
     * Default email recipients
     */
    protected $defaultEmails = [
        'recipient1@example.com',
        'recipient2@example.com',
        // Add more default emails here
    ];

    public function handle()
    {
        try {
            $this->info('Starting Wash Report generation...');

            // Determine date range based on period or provided dates
            $fromDate = $this->option('from');
            $toDate = $this->option('to');
            $period = $this->option('period');

            if (!$fromDate || !$toDate) {
                switch ($period) {
                    case 'daily':
                        $toDate = Carbon::yesterday()->toDateString();
                        $fromDate = Carbon::yesterday()->toDateString();
                        break;
                    case 'weekly':
                        $toDate = Carbon::yesterday()->toDateString();
                        $fromDate = Carbon::yesterday()->subDays(6)->toDateString();
                        break;
                    case 'monthly':
                        $toDate = Carbon::yesterday()->toDateString();
                        $fromDate = Carbon::yesterday()->subDays(29)->toDateString();
                        break;
                    default:
                        $toDate = Carbon::yesterday()->toDateString();
                        $fromDate = Carbon::yesterday()->toDateString();
                        break;
                }
            }

            $this->info("Generating report for period: {$fromDate} to {$toDate}");

            // Check if recipients exist from --emails option or .env
            $recipientSource = $this->option('emails') ?: env('WASH_REPORT_RECIPIENTS');
            if (!$recipientSource) {
                $this->error('No recipients found! Please set WASH_REPORT_RECIPIENTS in .env file or pass --emails=.');
                return 1;
            }

            $recipients = array_values(array_filter(array_map('trim', explode(',', $recipientSource)), function ($email) {
                return filter_var($email, FILTER_VALIDATE_EMAIL);
            }));

            if (empty($recipients)) {
                $this->error('No valid recipients found! Please check WASH_REPORT_RECIPIENTS email format.');
                return 1;
            }

            $this->info('Recipients: ' . implode(',', $recipients));

            // Generate PDF
            $pdfPath = $this->generatePdf($fromDate, $toDate);

            if (!$pdfPath) {
                $this->error('Failed to generate PDF!');
                return 1;
            }

            $this->info("PDF generated successfully: {$pdfPath}");

            try {
                // Check if PDF exists
                if (!file_exists($pdfPath)) {
                    throw new \Exception("PDF file does not exist at path: {$pdfPath}");
                }

                $formattedFrom = Carbon::parse($fromDate)->format('d-m-Y');
                $formattedTo = Carbon::parse($toDate)->format('d-m-Y');
                $subject = "Wash Report Dashboard - {$formattedFrom} to {$formattedTo}";
                $attachmentName = "Wash_Report_{$formattedFrom}_to_{$formattedTo}.pdf";

                $successCount = 0;
                $failedCount = 0;

                foreach ($recipients as $recipient) {
                    try {
                        $this->info("Sending email to: {$recipient}");

                        Mail::send('emails.wash-report', [
                            'fromDate' => $fromDate,
                            'toDate' => $toDate,
                            'formattedFrom' => $formattedFrom,
                            'formattedTo' => $formattedTo,
                        ], function ($message) use ($recipient, $subject, $pdfPath, $attachmentName) {
                            $message->to($recipient)
                                ->subject($subject);

                            if (file_exists($pdfPath)) {
                                $message->attach($pdfPath, [
                                    'as' => $attachmentName,
                                    'mime' => 'application/pdf',
                                ]);
                            }
                        });

                        $successCount++;
                        $this->info("✓ Sent successfully to: {$recipient}");
                        Log::info('Wash Report email sent successfully', [
                            'email' => $recipient,
                            'from_date' => $fromDate,
                            'to_date' => $toDate,
                        ]);
                    } catch (\Exception $e) {
                        $failedCount++;
                        $this->error("✗ Failed to send to {$recipient}: " . $e->getMessage());
                        Log::error('Wash Report email failed for recipient', [
                            'email' => $recipient,
                            'error' => $e->getMessage(),
                            'from_date' => $fromDate,
                            'to_date' => $toDate,
                        ]);
                    }
                }

                $this->info("Email sending completed. Success: {$successCount}, Failed: {$failedCount}");
                Log::info('Wash Report email sending completed', [
                    'success_count' => $successCount,
                    'failed_count' => $failedCount,
                    'recipients' => $recipients,
                ]);
            } catch (\Exception $e) {
                $this->error('✗ Failed to send email: ' . $e->getMessage());
                Log::error('Wash Report email failed: ' . $e->getMessage());
            }

            // Clean up - delete the temporary PDF file
            if (file_exists($pdfPath)) {
                unlink($pdfPath);
                $this->info('Temporary PDF file deleted');
            }

            Log::info('Wash Report Process Completed', [
                'from_date' => $fromDate,
                'to_date' => $toDate,
                'recipients' => $recipients
            ]);

            return 0;
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            Log::error('Wash Report Command Error: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Generate PDF report
     */
    /**
     * Generate PDF report using controller method
     */
    private function generatePdf($fromDate, $toDate)
    {
        try {
            // Create controller instance
            $controller = new \App\Http\Controllers\WashReportDashboardController();

            // Use the controller's generatePdfReport method
            $pdfPath = $controller->generatePdfReport($fromDate, $toDate);

            return $pdfPath;
        } catch (\Exception $e) {
            Log::error('PDF Generation Error in command: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Calculate unit totals
     */
    private function calculateUnitTotals($unitData)
    {
        $totals = [
            'machines' => 0,
            'capacity_kg' => 0,
            'direct' => 0,
            'indirect' => 0,
            'total' => 0,
            'work_hours' => 0,
            'smv' => 0,
            'received' => 0,
            'delivery' => 0,
            'forecast_target' => 0,
            'deviation' => 0,
            'deviation_percent' => 0,
            'wash_ratio' => 0,
            'rewash_percent' => 0,
            'first_wash_qty' => 0,
            'acid_wash_qty' => 0,
            'final_wash_qty' => 0,
            'rewash_qty' => 0,
            'rework_dry_proc' => 0,
            'in_hand_balance' => 0
        ];

        foreach ($unitData as $u) {
            if (is_array($u)) {
                $u = (object) $u;
            }

            $totals['machines'] += (int)($u->machines ?? 0);
            $totals['capacity_kg'] += (float)($u->capacity_kg ?? 0);
            $totals['direct'] += (int)($u->direct ?? 0);
            $totals['indirect'] += (int)($u->indirect ?? 0);
            $totals['total'] += (int)($u->total ?? 0);
            $totals['work_hours'] += (float)($u->work_hours ?? 0);
            $totals['smv'] += (float)($u->smv ?? 0);
            $totals['received'] += (int)($u->received ?? 0);
            $totals['delivery'] += (int)($u->delivery ?? 0);
            $totals['forecast_target'] += (float)($u->forecast_target ?? 0);
            $totals['deviation'] += (float)($u->deviation ?? 0);
            $totals['deviation_percent'] += (float)($u->deviation_percent ?? 0);
            $totals['wash_ratio'] += (float)($u->wash_ratio ?? 0);
            $totals['rewash_percent'] += (float)($u->rewash_percent ?? 0);
            $totals['first_wash_qty'] += (int)($u->first_wash_qty ?? 0);
            $totals['acid_wash_qty'] += (int)($u->acid_wash_qty ?? 0);
            $totals['final_wash_qty'] += (int)($u->final_wash_qty ?? 0);
            $totals['rewash_qty'] += (int)($u->rewash_qty ?? 0);
            $totals['rework_dry_proc'] += (int)($u->rework_dry_proc ?? 0);
            $totals['in_hand_balance'] += (int)($u->in_hand_balance ?? 0);
        }

        return $totals;
    }

    /**
     * Calculate first dry totals
     */
    private function calculateFirstDryTotals($data)
    {
        $totals = [
            'whisker_target' => 0,
            'whisker_prod' => 0,
            'handbrush_target' => 0,
            'handbrush_prod' => 0,
            'target' => 0,
            'prod' => 0,
            'deviation' => 0,
            'defect' => 0,
            'manpower' => 0
        ];

        foreach ($data as $d) {
            $totals['whisker_target'] += (int)($d->whisker_target ?? 0);
            $totals['whisker_prod'] += (int)($d->whisker_production ?? 0);
            $totals['handbrush_target'] += (int)($d->handbrush_target ?? 0);
            $totals['handbrush_prod'] += (int)($d->handbrush_production ?? 0);
            $totals['target'] += (int)($d->firstdryfinal_target ?? 0);
            $totals['prod'] += (int)($d->firstdryfinal_production ?? 0);
            $totals['deviation'] += (int)($d->firstdryfinal_deviation ?? 0);
            $totals['defect'] += (int)($d->total_defect_qty ?? 0);
            $totals['manpower'] += (int)($d->manPower ?? 0);
        }

        return $totals;
    }

    /**
     * Calculate second dry totals
     */
    private function calculateSecondDryTotals($data)
    {
        $totals = [
            'laser_target' => 0,
            'laser_prod' => 0,
            'ppspray_target' => 0,
            'ppspray_prod' => 0,
            'target' => 0,
            'prod' => 0,
            'deviation' => 0,
            'defect' => 0,
            'manpower' => 0
        ];

        foreach ($data as $d) {
            $totals['laser_target'] += (int)($d->laser_target ?? 0);
            $totals['laser_prod'] += (int)($d->laser_production ?? 0);
            $totals['ppspray_target'] += (int)($d->ppspray_target ?? 0);
            $totals['ppspray_prod'] += (int)($d->ppspray_production ?? 0);
            $totals['target'] += (int)($d->seconddryfinal_target ?? 0);
            $totals['prod'] += (int)($d->seconddryfinal_production ?? 0);
            $totals['deviation'] += (int)($d->seconddryfinal_deviation ?? 0);
            $totals['defect'] += (int)($d->total_defect_qty ?? 0);
            $totals['manpower'] += (int)($d->manPower ?? 0);
        }

        return $totals;
    }

    /**
     * Calculate transfer totals
     */
    private function calculateTransferTotals($data)
    {
        $totals = [
            'existing_mc' => 0,
            'used_mc' => 0,
            'current_mg' => 0,
            'current_pcs' => 0,
            'current_kg' => 0
        ];

        foreach ($data as $t) {
            $totals['existing_mc'] += (int)($t->existing_mc ?? 0);
            $totals['used_mc'] += (int)($t->used_mc ?? 0);

            // Parse formatted numbers
            $totals['current_mg'] += (int)filter_var($t->current_mg_target ?? 0, FILTER_SANITIZE_NUMBER_FLOAT);
            $totals['current_pcs'] += (int)filter_var($t->current_capacity_pieces ?? 0, FILTER_SANITIZE_NUMBER_FLOAT);
            $totals['current_kg'] += (int)filter_var($t->current_capacity_kg ?? 0, FILTER_SANITIZE_NUMBER_FLOAT);
        }

        return $totals;
    }

    /**
     * Calculate dryer totals
     */
    private function calculateDryerTotals($data)
    {
        $totals = [
            'num_dryer' => 0,
            'first_wash' => 0,
            'cold' => 0,
            'meas' => 0,
            'final_wash' => 0,
            'total' => 0,
            'deviation' => 0
        ];

        foreach ($data as $d) {
            $totals['num_dryer'] += (int)($d->num_dryer ?? 0);
            $totals['first_wash'] += (float)($d->first_wash_dryer ?? 0);
            $totals['cold'] += (float)($d->cold_dryer ?? 0);
            $totals['meas'] += (float)($d->measurement_correction ?? 0);
            $totals['final_wash'] += (float)($d->final_wash_dryer ?? 0);
            $totals['total'] += (float)($d->total_dryer ?? 0);

            // Parse deviation from HTML if needed
            if (isset($d->deviation)) {
                $dev = is_string($d->deviation) ? strip_tags($d->deviation) : $d->deviation;
                $totals['deviation'] += (float)str_replace(',', '', $dev);
            }
        }

        return $totals;
    }
}
