<p>Poštovani,</p>

<p>U privitku se nalazi uplatnica za članarinu broj
   <strong>{{ $invoice->invoice_number }}</strong>.</p>

<p>
    Iznos: {{ number_format($invoice->amount, 2, ',', '.') }} EUR<br>
    Poziv na broj: {{ $invoice->reference_number }}
</p>

<p>Uplatu možete izvršiti skeniranjem 2D barkoda (HUB-3A) u mobilnoj bankarskoj aplikaciji.</p>

<p>Srdačan pozdrav,<br>
   {{ optional($invoice->membershipFee->club)->name ?? 'Klub' }}</p>