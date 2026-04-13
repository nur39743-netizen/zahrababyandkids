<div class="max-w-lg mx-auto space-y-4">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold text-pink-700">Detail Owner</h2>
            <p class="text-xs text-gray-500">Produk berdasarkan owner terpilih</p>
        </div>
        <div class="flex gap-2">
            <a href="/owners/{{ $owner->id }}/edit" class="bg-blue-50 text-blue-600 px-3 py-1.5 rounded-lg text-sm font-bold border border-blue-100 hover:bg-blue-100 transition">Edit Owner</a>
            <a href="/owners" class="text-gray-400 hover:text-pink-600 transition text-sm font-bold bg-white px-3 py-1.5 rounded-lg border border-gray-100 shadow-sm">&larr; Kembali</a>
        </div>
    </div>

    <div class="bg-white p-4 rounded-xl shadow-sm border border-pink-50">
        <p class="text-xs text-gray-400 font-bold">Nama Owner</p>
        <p class="font-bold text-gray-800">{{ $owner->nama_owner }}</p>
        <p class="text-xs text-gray-500 mt-2">Total produk: <span class="font-semibold">{{ $owner->products->count() }}</span></p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-pink-50 overflow-hidden">
        <h3 class="font-bold text-gray-600 text-xs p-3 bg-gray-50 border-b border-gray-100 uppercase tracking-widest text-center">Daftar Produk</h3>
        <div class="divide-y divide-gray-50">
            @forelse($owner->products as $product)
            <a href="/products/{{ $product->id }}" class="block p-3 hover:bg-pink-50/30 transition">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-bold text-sm text-gray-800">{{ $product->nama_produk }}</p>
                        <p class="text-[11px] text-gray-500 mt-0.5">Kode: {{ $product->kode_produk ?: '-' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[11px] text-pink-600 font-semibold">{{ $product->items_count }} Varian</p>
                    </div>
                </div>
            </a>
            @empty
            <div class="p-6 text-center text-sm text-gray-400">
                Belum ada produk pada owner ini.
            </div>
            @endforelse
        </div>
    </div>
</div>
