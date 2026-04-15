<div class="max-w-md mx-auto space-y-4">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-2">
        <h2 class="text-xl font-bold text-pink-700">Histori Transaksi</h2>
        <a href="/transactions/trashed" class="bg-gray-50 text-gray-700 px-4 py-2 rounded-lg text-sm font-semibold border border-gray-200 hover:bg-gray-100 transition">Transaksi Terhapus</a>
    </div>

    <!-- Filter -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-pink-50 flex items-center justify-between">
        <label class="text-xs font-semibold text-gray-500">Bulan Transaksi</label>
        <input type="month" wire:model.live="month" class="rounded-lg border-pink-200 text-sm px-3 py-1.5 focus:border-pink-500 shadow-inner w-32 border">
    </div>

    @if(session()->has('success'))
    <div class="p-3 bg-green-50 text-green-600 rounded-lg text-sm text-center border border-green-100 font-bold">
        {{ session('success') }}
    </div>
    @endif

    <!-- List -->
    <div class="space-y-3">
        @forelse($transactions as $trx)
        <div class="bg-white rounded-xl shadow-sm border border-pink-50 overflow-hidden relative">
            <div class="p-4 bg-gradient-to-r from-pink-50 to-white flex justify-between items-center border-b border-gray-50">
                <div>
                    <h3 class="font-bold text-gray-800 text-sm leading-none">{{ $trx->no_invoice }}</h3>
                    <p class="text-[10px] text-gray-400 mt-1">
                        {{ optional($trx->transaction_date)->format('d/m/Y') ?: $trx->created_at->format('d/m/Y') }}
                        • {{ $trx->created_at->format('H:i') }}
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-bold text-pink-600">Rp{{ number_format($trx->total_netto, 0, ',', '.') }}</p>
                    <span class="bg-green-100 text-green-700 font-bold text-[9px] px-2 py-0.5 rounded">{{ $trx->payment_method }}</span>
                </div>
            </div>

            <div class="p-3 flex justify-between items-center text-xs">
                <div class="text-gray-600">
                    <p class="font-bold text-[11px] text-gray-500">👤 {{ $trx->customer ? $trx->customer->nama_customer : 'Pelanggan Umum' }}</p>
                </div>
                <div class="flex space-x-2">
                    <a href="/transactions/{{ $trx->id }}" class="bg-blue-50 text-blue-600 px-3 py-1.5 rounded-lg font-bold hover:bg-blue-100 transition">Detail</a>
                    <a href="/pos/print/{{ $trx->id }}" target="_blank" class="bg-pink-50 text-pink-600 px-3 py-1.5 rounded-lg font-bold hover:bg-pink-100 transition flex items-center">
                        🖨️ Cetak
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center text-gray-400 py-10 bg-white rounded-xl border border-pink-50 shadow-sm">
            <p class="text-sm font-bold">Belum ada transaksi</p>
            <p class="text-xs mt-1">Gunakan filter bulan yang berbeda atau lakukan transaksi baru di menu kasir.</p>
        </div>
        @endforelse
    </div>

    <div>
        {{ $transactions->links(data: ['scrollTo' => false]) }}
    </div>
</div>