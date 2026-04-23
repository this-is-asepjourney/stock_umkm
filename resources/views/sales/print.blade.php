<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Struk {{ $sale->sale_number }}</title>
    <style>
        * { box-sizing: border-box; font-family: 'Courier New', monospace; }
        body { width: 80mm; padding: 8px; margin: 0 auto; color: #000; font-size: 12px; }
        .center { text-align: center; }
        .right { text-align: right; }
        .left { text-align: left; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 2px 0; }
        .sep { border-top: 1px dashed #000; margin: 6px 0; }
        .total { font-weight: bold; font-size: 14px; }
        h1 { font-size: 14px; margin: 0; }
        @media print { body { width: 80mm; } }
    </style>
</head>
<body onload="window.print()">
    <div class="center">
        <h1>Stock UMKM</h1>
        <div style="font-size:11px;">Toko Anda</div>
    </div>
    <div class="sep"></div>
    <table>
        <tr><td>No</td><td class="right">{{ $sale->sale_number }}</td></tr>
        <tr><td>Tgl</td><td class="right">{{ optional($sale->sale_date)->format('d/m/Y') }} {{ $sale->created_at->format('H:i') }}</td></tr>
        <tr><td>Kasir</td><td class="right">{{ $sale->user?->name }}</td></tr>
        <tr><td>Cust</td><td class="right">{{ $sale->customer_name ?? 'Umum' }}</td></tr>
    </table>
    <div class="sep"></div>

    @foreach ($sale->items as $item)
        <div>{{ $item->product?->name }}</div>
        <table>
            <tr>
                <td>{{ $item->quantity }} {{ $item->product?->unit?->symbol }} x Rp {{ number_format((float) $item->unit_price, 0, ',', '.') }}</td>
                <td class="right">Rp {{ number_format((float) $item->subtotal, 0, ',', '.') }}</td>
            </tr>
        </table>
    @endforeach

    <div class="sep"></div>
    <table>
        <tr><td>Subtotal</td><td class="right">Rp {{ number_format((float) $sale->subtotal, 0, ',', '.') }}</td></tr>
        <tr><td>Diskon</td><td class="right">-Rp {{ number_format((float) $sale->discount, 0, ',', '.') }}</td></tr>
        <tr><td>Pajak</td><td class="right">Rp {{ number_format((float) $sale->tax, 0, ',', '.') }}</td></tr>
        <tr class="total"><td>TOTAL</td><td class="right">Rp {{ number_format((float) $sale->total, 0, ',', '.') }}</td></tr>
        <tr><td>Bayar</td><td class="right">{{ strtoupper($sale->payment_method) }}</td></tr>
    </table>
    <div class="sep"></div>
    <div class="center" style="font-size:11px;">Terima kasih atas kunjungan Anda!<br>Simpan struk ini sebagai bukti pembelian.</div>
</body>
</html>
