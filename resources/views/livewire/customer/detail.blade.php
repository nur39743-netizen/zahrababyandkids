<div>
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-3">
            <a href="/customers" class="text-pink-500 hover:text-pink-700 bg-white shadow-sm p-2 rounded-full border border-pink-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h2 class="text-xl font-bold text-pink-700">Detail Customer</h2>
        </div>
        <div class="flex gap-2">
            <a href="/customers/{{ $customer->id }}/edit" class="bg-blue-50 text-blue-600 px-3 py-1.5 rounded-lg text-sm font-bold border border-blue-100 hover:bg-blue-100 transition">Edit</a>
        </div>
    </div>

    @if(session()->has('success'))
    <div class="p-3 bg-green-50 text-green-700 rounded border border-green-100 text-sm">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white p-4 rounded-xl shadow-sm border border-pink-50 space-y-2">
        <div>
            <p class="text-xs text-gray-400 font-bold">Nama</p>
            <p class="font-bold text-gray-800">{{ $customer->nama_customer }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-400 font-bold">No WhatsApp</p>
            <p class="text-sm text-gray-700">{{ $customer->no_whatsapp ?: '-' }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-400 font-bold">Alamat</p>
            <p class="text-sm text-gray-700">{{ $customer->alamat ?: '-' }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-400 font-bold">Catatan</p>
            <p class="text-sm text-gray-700">{{ $customer->catatan ?: '-' }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-pink-50 overflow-hidden mt-2">
        <h3 class="font-bold text-white text-xs p-3 bg-pink-500 border-b border-gray-100 uppercase tracking-widest text-center">Riwayat Transaksi Customer</h3>
        <div class="divide-y divide-gray-50">
            @forelse($customer->transactions as $trx)
            <a href="/transactions/{{ $trx->id }}" class="block p-3 hover:bg-pink-50/30 transition">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-bold text-sm text-gray-800">{{ $trx->no_invoice }}</p>
                        <p class="text-[11px] text-gray-500 mt-0.5">{{ $trx->created_at->format('d M Y, H:i') }} • {{ $trx->payment_method }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-pink-600 text-sm">Rp{{ number_format($trx->total_netto, 0, ',', '.') }}</p>
                        <p class="text-[10px] text-gray-500">{{ $trx->status }}</p>
                    </div>
                </div>
            </a>
            @empty
            <div class="p-6 text-center text-sm text-gray-400">
                Customer ini belum memiliki transaksi.
            </div>
            @endforelse
        </div>
    </div>
</div>