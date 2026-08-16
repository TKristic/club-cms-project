<?php

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MembershipFeeInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Invoice $invoice) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Uplatnica za članarinu');
    }

    public function content(): Content
    {
        return new Content(view: 'mail.invoice');
    }

    public function attachments(): array
    {
        return [
            Attachment::fromStorageDisk('public', $this->invoice->pdf_path)
                ->as($this->invoice->invoice_number . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}