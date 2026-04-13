<div>
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-3">
            <a href="/products" class="text-pink-500 hover:text-pink-700 bg-white shadow-sm p-2 rounded-full border border-pink-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h2 class="text-xl font-bold text-pink-700">Produk Terhapus</h2>
        </div>
    </div>

    @if (session()->has('message'))
    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
        {{ session('message') }}
    </div>
    @endif

    <div class="bg-white p-5 rounded-xl shadow-sm border border-pink-50">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-gray-500 border-b border-pink-50 bg-pink-50/50">
                    <tr>
                        <th class="py-2 px-2">Nama Produk</th>
                        <th class="py-2 px-2">Kode</th>
                        <th class="py-2 px-2">Kategori</th>
                        <th class="py-2 px-2">Dihapus Pada</th>
                        <th class="py-2 px-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($trashedProducts as $product)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="py-3 px-2 text-gray-800 font-medium">{{ $product->nama_produk }}</td>
                        <td class="py-3 px-2 text-gray-600">{{ $product->kode_produk }}</td>
                        <td class="py-3 px-2 text-gray-600">{{ $product->category ? $product->category->nama_kategori : '-' }}</td>
                        <td class="py-3 px-2 text-gray-600">{{ $product->deleted_at->format('d M Y H:i') }}</td>
                        <td class="py-3 px-2 text-center">
                            <button wire:click="restore({{ $product->id }})" wire:confirm="Apakah Anda yakin ingin mengembalikan produk ini?" class="bg-blue-500 text-white text-xs px-3 py-1 rounded hover:bg-blue-600 transition mr-2">Kembalikan</button>
                            <button wire:click="forceDelete({{ $product->id }})" wire:confirm="PERINGATAN: Ini akan menghapus produk dan semua data transaksi terkait secara permanen. Apakah Anda yakin?" class="bg-red-500 text-white text-xs px-3 py-1 rounded hover:bg-red-600 transition">Hapus Permanen</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-500">Tidak ada produk terhapus.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>