<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\MembershipFee;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\Hub3BarcodeService;

class InvoiceService
{
    public function generateForFee(MembershipFee $fee): Invoice
    {
        $fee->loadMissing('player', 'club');

        $reference = now()->format('Y') . '-' . str_pad($fee->id, 6, '0', STR_PAD_LEFT);
        $number    = 'UPL-' . now()->format('Ymd') . '-' . str_pad($fee->id, 4, '0', STR_PAD_LEFT);

        $invoice = Invoice::updateOrCreate(
            ['membership_fee_id' => $fee->id],
            [
                'invoice_number'   => $number,
                'reference_number' => $reference,
                'amount'           => $fee->amount,
            ]
        );

        // HUB-3A barkod (data-URI), ako klub ima IBAN
        $barcode = null;
        if ($fee->club->iban) {
            $barcode = app(\App\Services\Hub3BarcodeService::class)->dataUri([
                'amount'      => (float) $fee->amount,
                'payer_name'  => $fee->player->first_name . ' ' . $fee->player->last_name,
                'payer_city'  => '',
                'payee_name'  => $fee->club->name,
                'payee_city'  => $fee->club->address ?? '',
                'iban'        => preg_replace('/\s+/', '', $fee->club->iban),
                'model'       => 'HR00',
                'reference'   => str_replace('-', '', $invoice->reference_number),
                'description' => 'Clanarina ' . $fee->season,
            ]);
        }

        // render PDF-a (master-detail: klub + igrač + članarina)
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.uplatnica', [
            'invoice' => $invoice,
            'fee'     => $fee,
            'player'  => $fee->player,
            'club'    => $fee->club,
            'barcode' => $barcode,
        ]);

        // spremi PDF na javni disk
        $path = 'invoices/' . $invoice->invoice_number . '.pdf';
        \Illuminate\Support\Facades\Storage::disk('public')->put($path, $pdf->output());

        $invoice->update(['pdf_path' => $path]);

        return $invoice;
    }
}