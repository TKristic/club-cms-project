<?php

namespace App\Console\Commands;

use App\Mail\MembershipFeeInvoiceMail;
use App\Models\Invoice;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class TestInvoiceMail extends Command
{
    protected $signature = 'test:invoice-mail {email=test@test.com}';
    protected $description = 'Pošalji test uplatnicu mailom';

    public function handle(): int
    {
        $invoice = Invoice::latest()->first();

        if (! $invoice) {
            $this->error('Nema nijedne uplatnice u bazi.');
            return self::FAILURE;
        }

        $this->info('Invoice: ' . $invoice->invoice_number);
        $this->info('PDF path: ' . ($invoice->pdf_path ?? 'NEMA'));
        $this->info('PDF postoji: ' . (($invoice->pdf_path && Storage::disk('public')->exists($invoice->pdf_path)) ? 'DA' : 'NE'));

        try {
            Mail::to($this->argument('email'))->send(new MembershipFeeInvoiceMail($invoice));
            $this->info('POSLANO OK');
            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error($e->getMessage());
            $this->line('@ ' . $e->getFile() . ':' . $e->getLine());
            return self::FAILURE;
        }
    }
}