<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota #{{ $transaction->no_invoice }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { background: white; }
            /* hide the actions and any children reliably */
            .no-print, .no-print * { display: none !important; visibility: hidden !important; }
            .print-shadow-none { box-shadow: none !important; border: none !important; }
        }
        @media screen {
            .no-print { display: block; visibility: visible; }
        }
    </style>
</head>

<body class="bg-pink-50/30 text-gray-800 font-sans antialiased py-8 min-h-screen">

    <div class="max-w-2xl mx-auto bg-white p-8 sm:p-10 rounded-3xl shadow-xl print-shadow-none border border-pink-100">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b-2 border-pink-50 pb-6 mb-8 gap-4">
            <div>
                <h1 class="text-4xl font-black text-pink-600 tracking-tight">Zahra<span class="text-gray-800">babyandkids</span></h1>
                <p class="text-gray-500 text-sm mt-1 font-medium">Pusat Perlengkapan Bayi & Anak</p>
            </div>
            <div class="text-left sm:text-right">
                <div class="inline-block px-4 py-1.5 bg-pink-100 text-pink-700 rounded-full text-xs font-bold tracking-widest uppercase mb-2">
                    Nota Transaksi
                </div>
                <p class="text-gray-800 font-bold text-lg">#{{ $transaction->no_invoice }}</p>
                <p class="text-gray-500 text-sm mt-0.5">{{ $transaction->created_at->format('d F Y, H:i') }}</p>
            </div>
        </div>

        <!-- Customer & Payment Info -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
            <div class="bg-gray-50 p-5 rounded-2xl border border-gray-100">
                <p class="text-xs text-gray-500 uppercase tracking-wider font-bold mb-2">Informasi Pelanggan</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-pink-100 rounded-full flex items-center justify-center text-pink-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-gray-800">{{ $transaction->customer ? $transaction->customer->nama_customer : 'Pelanggan Umum' }}</p>
                        <p class="text-xs text-gray-500">Customer</p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 p-5 rounded-2xl border border-gray-100">
                <p class="text-xs text-gray-500 uppercase tracking-wider font-bold mb-2">Status Pembayaran</p>
                <div class="flex flex-col gap-2">
                    <div>
                        @if(($transaction->status_pembayaran ?? 'lunas') === 'lunas')
                        <span class="inline-block bg-green-100 text-green-700 font-bold text-xs px-3 py-1 rounded-lg">LUNAS</span>
                        @else
                        <span class="inline-block bg-amber-100 text-amber-700 font-bold text-xs px-3 py-1 rounded-lg">BELUM LUNAS</span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-700 font-medium flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        Metode: <span class="font-bold uppercase">{{ $transaction->payment_method }}</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="mb-8 overflow-hidden rounded-2xl border border-gray-200">
            <table class="w-full text-left border-collapse bg-white">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="py-4 px-5 text-xs text-gray-500 uppercase tracking-wider font-bold w-1/2">Produk</th>
                        <th class="py-4 px-5 text-xs text-gray-500 uppercase tracking-wider font-bold text-center w-1/6">Qty</th>
                        <th class="py-4 px-5 text-xs text-gray-500 uppercase tracking-wider font-bold text-right w-1/3">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($transaction->items as $item)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="py-4 px-5">
                            <p class="font-bold text-gray-800 text-sm">{{ $item->nama_produk_history }}</p>
                            @if($item->varian_history && $item->varian_history != 'Standard')
                            <p class="inline-block bg-pink-50 text-pink-600 text-[10px] font-bold px-2 py-0.5 rounded mt-1">{{ $item->varian_history }}</p>
                            @endif
                            <p class="text-xs text-gray-400 mt-1 font-medium">@ Rp{{ number_format($item->harga_jual_history, 0, ',', '.') }}</p>
                        </td>
                        <td class="py-4 px-5 text-center font-bold text-gray-700">{{ $item->qty }}</td>
                        <td class="py-4 px-5 text-right font-bold text-gray-800">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Summary -->
        <div class="flex justify-end mb-10">
            <div class="w-full sm:w-2/3 lg:w-1/2 bg-gray-50 p-6 rounded-2xl border border-gray-100 space-y-3">
                <div class="flex justify-between text-gray-600 text-sm font-medium">
                    <span>Total Bruto</span>
                    <span class="text-gray-800 font-bold">Rp{{ number_format($transaction->total_bruto, 0, ',', '.') }}</span>
                </div>
                @if($transaction->total_diskon > 0)
                <div class="flex justify-between text-red-500 text-sm font-medium">
                    <span>Diskon</span>
                    <span class="font-bold">-Rp{{ number_format($transaction->total_diskon, 0, ',', '.') }}</span>
                </div>
                @endif
                @if($transaction->status_ongkir == 'Customer' && $transaction->biaya_ongkir > 0)
                <div class="flex justify-between text-gray-600 text-sm font-medium">
                    <span>Ongkos Kirim</span>
                    <span class="text-gray-800 font-bold">Rp{{ number_format($transaction->biaya_ongkir, 0, ',', '.') }}</span>
                </div>
                @endif
                @if($transaction->status_packing == 'Customer' && $transaction->biaya_packing > 0)
                <div class="flex justify-between text-gray-600 text-sm font-medium">
                    <span>Biaya Packing</span>
                    <span class="text-gray-800 font-bold">Rp{{ number_format($transaction->biaya_packing, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="pt-4 border-t border-gray-200 mt-4">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-bold text-gray-800">Total Bayar</span>
                        <span class="text-2xl font-black text-pink-600">Rp{{ number_format($transaction->total_netto, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center text-gray-500 text-xs sm:text-sm pt-8 border-t-2 border-dashed border-gray-200">
            <p class="font-medium">Terima kasih atas kepercayaan Anda berbelanja di <span class="font-bold text-pink-600">Zahrababyandkids</span>.</p>
        </div>
    </div>

    <!-- Actions (No Print) -->
    @if(!request()->route('token'))
    <div class="max-w-2xl mx-auto mt-8 flex flex-col sm:flex-row justify-center gap-4 no-print px-4">
        <button onclick="window.print()" class="bg-pink-600 hover:bg-pink-700 text-white px-8 py-3 rounded-2xl font-bold shadow-lg shadow-pink-200 transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Simpan PDF / Cetak
        </button>
        <a href="/transactions" class="bg-white hover:bg-gray-50 text-gray-700 px-8 py-3 rounded-2xl font-bold shadow-sm border-2 border-gray-200 transition flex items-center justify-center text-center">
            Kembali
        </a>
    </div>
    @endif

</body>

</html>