<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk #{{ $transaction->no_invoice }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            color: #000;
            width: 58mm; /* Standard thermal width */
            margin: 0 auto;
            padding: 10px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .border-bottom { border-bottom: 1px dashed #000; margin-bottom: 5px; padding-bottom: 5px; }
        .border-top { border-top: 1px dashed #000; margin-top: 5px; padding-top: 5px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { font-size: 11px; padding: 2px 0; vertical-align: top; }
        .item-name { font-size: 11px; font-weight: bold; }
        .item-variant { font-size: 9px; }
        .total-section { font-size: 12px; }
        
        .no-print { display: block; text-align: center; margin-top: 15px; }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print();">

    <div class="text-center border-bottom">
        <h2 style="margin: 0; font-size: 16px;">Zahrababyandkids</h2>
        <p style="margin: 2px 0; font-size: 10px;">Struk Transaksi / POS</p>
    </div>

    <div class="border-bottom" style="font-size: 10px;">
        <table style="margin-bottom: 5px;">
            <tr><td>No</td><td>: {{ $transaction->no_invoice }}</td></tr>
            <tr><td>Tgl</td><td>: {{ $transaction->created_at->format('d/m/Y H:i') }}</td></tr>
            <tr><td>Cust</td><td>: {{ $transaction->customer ? $transaction->customer->nama_customer : '-' }}</td></tr>
            <tr><td>Kasir</td><td>: Admin</td></tr>
        </table>
    </div>

    <div class="border-bottom">
        <table>
            @foreach($transaction->items as $item)
            <tr>
                <td colspan="3" class="item-name">
                    {{ $item->nama_produk_history }}
                    @if($item->varian_history && $item->varian_history != 'Standard')
                        <br><span class="item-variant">[{{ $item->varian_history }}]</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td>{{ $item->qty }} x</td>
                <td>{{ number_format($item->harga_jual_history, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </table>
    </div>

    <div class="border-bottom total-section">
        <table>
            <tr>
                <td>Total Bruto</td>
                <td class="text-right">{{ number_format($transaction->total_bruto, 0, ',', '.') }}</td>
            </tr>
            @if($transaction->total_diskon > 0)
            <tr>
                <td>Diskon</td>
                <td class="text-right">-{{ number_format($transaction->total_diskon, 0, ',', '.') }}</td>
            </tr>
            @endif
            @if($transaction->status_ongkir == 'Customer' && $transaction->biaya_ongkir > 0)
            <tr>
                <td>Ongkir</td>
                <td class="text-right">{{ number_format($transaction->biaya_ongkir, 0, ',', '.') }}</td>
            </tr>
            @endif
            @if($transaction->status_packing == 'Customer' && $transaction->biaya_packing > 0)
            <tr>
                <td>Packing</td>
                <td class="text-right">{{ number_format($transaction->biaya_packing, 0, ',', '.') }}</td>
            </tr>
            @endif
            <tr class="font-bold border-top">
                <td>Total Netto</td>
                <td class="text-right">{{ number_format($transaction->total_netto, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="text-center" style="margin-top: 10px; font-size: 10px;">
        <p style="margin: 0;">Bayar: {{ $transaction->payment_method }}</p>
        <p style="margin: 5px 0;">Terima Kasih!</p>
    </div>

    <div class="no-print">
        <a href="/pos" style="text-decoration: none; padding: 5px 10px; background: #ddd; color: #333; border-radius: 3px; font-size: 10px; display: inline-block;">Kembali ke Kasir</a>
    </div>

</body>
</html>
