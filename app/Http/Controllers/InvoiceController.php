<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    public function download(Invoice $invoice)
    {
        abort_unless($invoice->pdf_path && Storage::disk('public')->exists($invoice->pdf_path), 404);

        return Storage::disk('public')->download($invoice->pdf_path, $invoice->invoice_number . '.pdf');
    }
}
