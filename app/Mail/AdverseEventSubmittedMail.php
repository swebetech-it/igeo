<?php

namespace App\Mail;

use App\Models\AdverseEventReport;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdverseEventSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public AdverseEventReport $report;

    public function __construct(AdverseEventReport $report)
    {
        $this->report = $report;
    }

    public function envelope(): Envelope
    {
        $prefix = $this->report->is_serious ? '[SERIOUS PV ALERT] ' : '[PV Report] ';

        return new Envelope(
            subject: $prefix . 'Adverse Event Report ' . $this->report->reference_number . ' - ' . $this->report->primary_product_name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.adverse-event-submitted',
        );
    }
}
