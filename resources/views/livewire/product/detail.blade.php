<div>
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-3">
            <a href="/products" class="text-pink-500 hover:text-pink-700 bg-white shadow-sm p-2 rounded-full border border-pink-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h2 class="text-xl font-bold text-pink-700">Detail Produk</h2>
        </div>
    </div>

    <div class="flex justify-between items-center mb-6">
        <a href="/products/{{ $product->id }}/edit" class="bg-blue-50 text-blue-600 px-3 py-1.5 rounded text-sm font-semibold border border-blue-100 hover:bg-blue-100 transition">
            Edit
        </a>
        <button wire:click="delete" wire:confirm="Apakah Anda yakin ingin menonaktifkan produk ini? Produk akan dipindahkan ke daftar terhapus." class="bg-red-50 text-red-600 px-3 py-1.5 rounded text-sm font-semibold border border-red-100 hover:bg-red-100 transition ml-2">
            Nonaktifkan
        </button>
        <a href="/products/trashed" class="bg-gray-50 text-gray-600 px-3 py-1.5 rounded text-sm font-semibold border border-gray-100 hover:bg-gray-100 transition ml-2">
            Terhapus
        </a>
    </div>

    <div class="bg-white p-5 rounded-xl shadow-sm border border-pink-50 mb-4 relative">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h3 class="font-bold text-gray-800 text-2xl leading-tight">{{ $product->nama_produk }}</h3>
                <p class="text-sm text-gray-500 mt-1">Kode: <span class="font-semibold text-gray-700">{{ $product->kode_produk }}</span></p>
                <p class="text-sm text-gray-500">Kategori: <span class="font-semibold text-gray-700">{{ $product->category ? $product->category->nama_kategori : '-' }}</span></p>
                <p class="text-sm text-gray-500">Gender: <span class="font-semibold text-gray-700">{{ $product->gender === 'male' ? 'Laki-laki / Male' : ($product->gender === 'female' ? 'Perempuan / Female' : 'Unisex / Netral') }}</span></p>
                <p class="text-sm text-gray-500">Bahan: <span class="font-semibold text-gray-700">{{ $product->bahan ?: '-' }}</span></p>
                <p class="text-sm text-gray-500 mt-2">
                    Terjual (total):
                    <span class="font-semibold text-sky-700">{{ number_format((int) ($product->total_terjual ?? 0), 0, ',', '.') }}</span>
                    <span class="text-gray-400 text-xs font-normal">unit</span>
                </p>
            </div>
            @if($product->owner)
            <span class="bg-yellow-100 text-yellow-700 text-xs px-3 py-1.5 rounded font-bold border border-yellow-200">Titipan: {{ $product->owner->nama_owner }}</span>
            @else
            <span class="bg-pink-100 text-pink-600 text-xs px-3 py-1.5 rounded font-bold border border-pink-200">Milik Sendiri</span>
            @endif
        </div>

        <div class="mt-4 pt-4 border-t border-gray-100">
            <h4 class="font-semibold text-pink-700 text-sm mb-3">Daftar Varian & Harga</h4>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-gray-500 border-b border-pink-50 bg-pink-50/50">
                        <tr>
                            <th class="py-2 px-2 rounded-tl-lg">Varian</th>
                            <th class="py-2 px-2 text-right">Modal</th>
                            <th class="py-2 px-2 text-right">Harga Grosir</th>
                            <th class="py-2 px-2 text-right">Harga Retail</th>
                            <th class="py-2 px-2 text-center">Terjual</th>
                            <th class="py-2 px-2 text-center rounded-tr-lg">Stok</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($product->items as $item)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-3 px-2 text-gray-800 font-medium whitespace-nowrap">
                                {{ $item->variantOption1 ? $item->variantOption1->value : 'Standard' }}
                                {{ $item->variantOption2 ? ' / '.$item->variantOption2->value : '' }}
                            </td>
                            <td class="py-3 px-2 text-right text-gray-600">Rp{{ number_format($item->harga_modal,0,',','.') }}</td>
                            <td class="py-3 px-2 text-right text-blue-600 font-medium">Rp{{ number_format($item->harga_sell,0,',','.') }}</td>
                            <td class="py-3 px-2 text-right font-bold text-pink-600">Rp{{ number_format($item->harga_jual,0,',','.') }}</td>
                            <td class="py-3 px-2 text-center">
                                <span class="bg-sky-50 text-sky-800 px-2 py-1 rounded border border-sky-100 text-xs font-semibold">{{ (int) ($item->terjual ?? 0) }}</span>
                            </td>
                            <td class="py-3 px-2 text-center">
                                <span class="bg-gray-100 px-3 py-1 rounded border border-gray-200 shadow-sm">{{ $item->stok_akhir }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>