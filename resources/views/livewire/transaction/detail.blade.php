<div>
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-3">
            <a href="/transactions" class="text-pink-500 hover:text-pink-700 bg-white shadow-sm p-2 rounded-full border border-pink-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h2 class="text-xl font-bold text-pink-700">Detail Transaksi</h2>
            <div class="flex flex-wrap gap-2">
                <a href="/transactions/{{ $transaction->id }}/edit" class="text-blue-400 hover:text-blue-600 transition p-2 hover:bg-blue-50 rounded-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg></a>
                <button wire:click="delete" wire:confirm="Apakah Anda yakin ingin menghapus transaksi ini?" class="bg-red-50 text-red-600 px-3 py-1.5 rounded-lg text-sm font-bold border border-red-100 hover:bg-red-100 transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg></button>
            </div>
        </div>
    </div>

    <!-- Header Transaksi -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-pink-50 space-y-3 relative overflow-hidden">
        <div class="absolute -right-4 -top-4 w-16 h-16 bg-green-50 rounded-full flex items-center justify-center opacity-50">
            <span class="text-green-500 text-2xl rotate-12">✓</span>
        </div>

        <div class="flex justify-between items-start border-b border-gray-50 pb-3 relative z-10">
            <div>
                <p class="text-xs text-gray-400 font-bold mb-0.5">No. Invoice</p>
                <h3 class="font-bold text-gray-800 tracking-wide">{{ $transaction->no_invoice }}</h3>
            </div>
            <div class="text-right">
                <p class="text-[10px] text-gray-400 font-bold mb-0.5">Tanggal Checkout</p>
                <p class="font-bold text-gray-700 text-sm">
                    {{ optional($transaction->transaction_date)->format('d M Y') ?: $transaction->created_at->format('d M Y') }},
                    {{ $transaction->created_at->format('H:i') }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 pt-1 relative z-10">
            <div>
                <p class="text-[10px] text-gray-400 font-bold">Data Pelanggan</p>
                <p class="font-bold text-sm text-gray-700 leading-tight mt-0.5">{{ $transaction->customer ? $transaction->customer->nama_customer : 'Pelanggan Umum' }}</p>
                <p class="text-[10px] text-gray-500 mt-0.5">{{ $transaction->customer ? $transaction->customer->no_whatsapp : '-' }}</p>
            </div>
            <div class="text-right">
                <p class="text-[10px] text-gray-400 font-bold">Metode Bayar / Status</p>
                <p class="font-bold text-xs text-green-600 mt-1 uppercase">{{ $transaction->payment_method }}</p>
                <p class="text-[10px] font-bold text-gray-500 mt-0.5">{{ $transaction->status }}</p>
            </div>
        </div>

        <div class="pt-3 border-t border-gray-50">
            <a href="/pos/print/{{ $transaction->id }}" target="_blank" class="w-full flex justify-center items-center bg-pink-50 text-pink-600 px-4 py-2.5 rounded-lg font-bold hover:bg-pink-100 transition text-sm">
                🖨️ Cetak Ulang Struk
            </a>
        </div>
    </div>

    <!-- Rincian Produk -->
    <div class="bg-white rounded-xl shadow-sm border border-pink-50 overflow-hidden">
        <h3 class="font-bold text-gray-600 text-xs p-3 bg-gray-50 border-b border-gray-100 uppercase tracking-widest text-center">Rincian Pembelian</h3>
        <div class="divide-y divide-gray-50">
            @foreach($transaction->items as $item)
            <div class="p-3">
                <div class="flex justify-between items-start">
                    <div class="pr-2">
                        <p class="font-bold text-[13px] text-gray-700 leading-tight">{{ $item->nama_produk_history }}</p>
                        <p class="text-[9px] text-gray-500 font-semibold bg-gray-100 border border-gray-200 px-1 inline-block mt-1 rounded">{{ $item->varian_history }}</p>
                    </div>
                    <p class="font-bold text-sm text-gray-800">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</p>
                </div>
                <div class="flex justify-between mt-2 text-xs">
                    <span class="text-gray-500 bg-gray-50 px-1 py-0.5 rounded border border-gray-100">{{ $item->qty }} x Rp{{ number_format($item->harga_jual_history, 0, ',', '.') }}</span>
                    <!-- Laba -->
                    <span class="text-green-600 text-[10px] font-bold bg-green-50 px-1 py-0.5 rounded border border-green-100">Laba: Rp{{ number_format(($item->harga_jual_history - $item->harga_modal_history) * $item->qty, 0, ',', '.') }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Ringkasan Harga -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-pink-50 space-y-2 text-sm font-medium">
        <div class="flex justify-between text-gray-500 border-b border-gray-50 pb-2">
            <span>Total Harga Barang (Bruto)</span>
            <span>Rp{{ number_format($transaction->total_bruto, 0, ',', '.') }}</span>
        </div>

        @if($transaction->total_diskon > 0)
        <div class="flex justify-between text-orange-500 border-b border-gray-50 pb-2">
            <span>Diskon Global Trx</span>
            <span>- Rp{{ number_format($transaction->total_diskon, 0, ',', '.') }}</span>
        </div>
        @endif

        <div class="flex justify-between text-[11px] {{ $transaction->status_ongkir == 'Admin' ? 'text-gray-400 line-through' : 'text-gray-500' }}">
            <span>Biaya Ongkir ({{ $transaction->status_ongkir == 'Admin' ? 'Ditanggung Toko' : 'Ditanggung Pembeli' }})</span>
            <span>Rp{{ number_format($transaction->biaya_ongkir, 0, ',', '.') }}</span>
        </div>

        <div class="flex justify-between text-[11px] {{ $transaction->status_packing == 'Admin' ? 'text-gray-400 line-through' : 'text-gray-500' }}">
            <span>Biaya Packing ({{ $transaction->status_packing == 'Admin' ? 'Ditanggung Toko' : 'Ditanggung Pembeli' }})</span>
            <span>Rp{{ number_format($transaction->biaya_packing, 0, ',', '.') }}</span>
        </div>

        <div class="flex justify-between font-bold text-lg text-pink-600 pt-3 border-t border-gray-200 mt-2">
            <span>TOTAL NETTO</span>
            <span>Rp{{ number_format($transaction->total_netto, 0, ',', '.') }}</span>
        </div>
    </div>
</div>