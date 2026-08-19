<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class WashReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $fromDate;
    public $toDate;
    public $pdfPath;
    public $recipientEmail;
    public $formattedFrom;
    public $formattedTo;

    /**
     * Create a new message instance.
     */
    public function __construct($fromDate, $toDate, $pdfPath, $recipientEmail)
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->pdfPath = $pdfPath;
        $this->recipientEmail = $recipientEmail;
        $this->formattedFrom = Carbon::parse($fromDate)->format('d-m-Y');
        $this->formattedTo = Carbon::parse($toDate)->format('d-m-Y');
    }

    /**
     * Build the message.
     */
    // public function build()
    // {
    //     $subject = "Wash Report Dashboard - {$this->formattedFrom} to {$this->formattedTo}";

    //     $email = $this->subject($subject)
    //         ->view('emails.wash-report')
    //         ->with([
    //             'fromDate' => $this->fromDate,
    //             'toDate' => $this->toDate,
    //             'formattedFrom' => $this->formattedFrom,
    //             'formattedTo' => $this->formattedTo,
    //         ]);

    //     // Attach PDF if it exists
    //     if (file_exists($this->pdfPath)) {
    //         $email->attach($this->pdfPath, [
    //             'as' => 'Wash_Report_' . $this->formattedFrom . '_to_' . $this->formattedTo . '.pdf',
    //             'mime' => 'application/pdf',
    //         ]);

    //         \Log::info('PDF attached successfully: ' . $this->pdfPath);
    //     } else {
    //         \Log::error('PDF file not found for attachment: ' . $this->pdfPath);
    //     }

    //     return $email;
    // }


    public function build()
    {
        $subject = "Wash Report Dashboard - {$this->formattedFrom} to {$this->formattedTo}";

        // IMPORTANT:
        // Do not set ->to() or ->cc() here.
        // SendWashReport command will send one email per recipient using Mail::to($recipient)->send(...).
        // If we also set to/cc here, Laravel will again use .env recipients and the one-by-one sending fix will not work properly.
        $email = $this->subject($subject)
            ->view('emails.wash-report')
            ->with([
                'fromDate' => $this->fromDate,
                'toDate' => $this->toDate,
                'formattedFrom' => $this->formattedFrom,
                'formattedTo' => $this->formattedTo,
            ]);

        // Attach PDF if it exists
        if (file_exists($this->pdfPath)) {
            $email->attach($this->pdfPath, [
                'as' => 'Wash_Report_' . $this->formattedFrom . '_to_' . $this->formattedTo . '.pdf',
                'mime' => 'application/pdf',
            ]);
        }

        return $email;
    }
}
