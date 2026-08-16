<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; }
        .header { border-bottom: 3px solid {{ $club->primary_color ?? '#1e3a8a' }}; padding-bottom: 10px; margin-bottom: 20px; }
        .title { font-size: 20px; font-weight: bold; color: {{ $club->primary_color ?? '#1e3a8a' }}; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        td, th { padding: 6px 8px; border: 1px solid #ddd; text-align: left; }
        th { background: #f3f4f6; }
        .amount { font-size: 18px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">{{ $club->name }}</div>
        <div>Uplatnica za članarinu</div>
    </div>

    <p><strong>Broj uplatnice:</strong> {{ $invoice->invoice_number }}</p>
    <p><strong>Poziv na broj:</strong> {{ $invoice->reference_number }}</p>

    <table>
        <tr><th>Član (igrač)</th><td>{{ $player->first_name }} {{ $player->last_name }}</td></tr>
        <tr><th>Sezona</th><td>{{ $fee->season }}</td></tr>
        <tr><th>Dospijeće</th><td>{{ $fee->due_date->format('d.m.Y.') }}</td></tr>
        <tr><th>Primatelj</th><td>{{ $club->name }}@if($club->address), {{ $club->address }}@endif</td></tr>
        <tr><th>Iznos</th><td class="amount">{{ number_format($fee->amount, 2, ',', '.') }} EUR</td></tr>
    </table>

    <p style="margin-top:30px; font-size:11px; color:#666;">
        @if(!empty($barcode))
            <div style="margin-top: 30px; text-align: center;">
                <p style="font-size: 11px; color: #666; margin-bottom: 6px;">
                    Skenirajte u mobilnoj bankarskoj aplikaciji:
                </p>
                <img src="{{ $barcode }}" style="height: 70px;">
            </div>
        @else
            <p style="margin-top: 30px; font-size: 11px; color: #c00;">
                IBAN kluba nije postavljen — barkod nije generiran. Unesite IBAN u postavkama kluba.
            </p>
        @endif
    </p>
</body>
</html>