<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>PO {{ $purchase->po_number }}</title>
    <style>
        * { box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { padding: 30px; color: #111; }
        h1 { font-size: 22px; margin: 0 0 10px; }
        h2 { font-size: 14px; color: #555; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 8px 10px; border: 1px solid #ccc; font-size: 12px; }
        th { background: #f3f4f6; text-align: left; }
        .right { text-align: right; }
        .no-border td { border: none; padding: 4px 0; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #4f46e5; padding-bottom: 10px; }
        .total { font-size: 14px; font-weight: bold; color: #4f46e5; }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <div>
            <h1>PURCHASE ORDER</h1>
            <p style="margin:0;font-size:12px;color:#555;">Stock UMKM</p>
        </div>
        <div style="text-align:right;">
            <div><strong>{{ $purchase->po_number }}</strong></div>
            <div>{{ optional($purchase->po_date)->format('d M Y') }}</div>
        </div>
    </div>

    <h2>Supplier</h2>
    <div>
        <strong>{{ $purchase->supplier?->name }}</strong><br>
        {{ $purchase->supplier?->address }}<br>
        {{ $purchase->supplier?->phone }} · {{ $purchase->supplier?->email }}
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th><th>Produk</th><th class="right">Qty</th><th class="right">Harga</th><th class="right">Diskon</th><th class="right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchase->items as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item->product?->name }} ({{ $item->product?->code }})</td>
                    <td class="right">{{ $item->quantity_ordered }} {{ $item->product?->unit?->symbol }}</td>
                    <td class="right">Rp {{ number_format((float) $item->unit_price, 0, ',', '.') }}</td>
                    <td class="right">Rp {{ number_format((float) $item->discount, 0, ',', '.') }}</td>
                    <td class="right">Rp {{ number_format((float) $item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="no-border" style="margin-top:20px;width:350px;margin-left:auto;">
        <tr><td>Subtotal</td><td class="right">Rp {{ number_format((float) $purchase->subtotal, 0, ',', '.') }}</td></tr>
        <tr><td>Diskon</td><td class="right">- Rp {{ number_format((float) $purchase->discount, 0, ',', '.') }}</td></tr>
        <tr><td>Pajak</td><td class="right">Rp {{ number_format((float) $purchase->tax, 0, ',', '.') }}</td></tr>
        <tr class="total"><td>Total</td><td class="right">Rp {{ number_format((float) $purchase->total, 0, ',', '.') }}</td></tr>
    </table>

    <div style="margin-top:40px;display:flex;justify-content:space-between;">
        <div style="text-align:center;">
            <div style="height:50px;"></div>
            <div style="border-top:1px solid #000;padding-top:5px;">Dibuat oleh<br><strong>{{ $purchase->user?->name }}</strong></div>
        </div>
        <div style="text-align:center;">
            <div style="height:50px;"></div>
            <div style="border-top:1px solid #000;padding-top:5px;">Disetujui<br><strong>&nbsp;</strong></div>
        </div>
    </div>
</body>
</html>
