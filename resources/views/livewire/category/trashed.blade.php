<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-bold text-pink-700">Kategori Terhapus</h2>
            <p class="text-sm text-gray-500">Daftar kategori yang sudah dinonaktifkan.</p>
        </div>
        <a href="/categories" class="bg-white text-gray-600 px-3 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 transition">Kembali ke Kategori</a>
    </div>

    @if(session()->has('message'))
    <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-700 rounded">
        {{ session('message') }}
    </div>
    @endif

    <div class="bg-white p-5 rounded-xl shadow-sm border border-pink-50">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-gray-500 border-b border-pink-50 bg-pink-50/50">
                    <tr>
                        <th class="py-2 px-2">Nama Kategori</th>
                        <th class="py-2 px-2">Total Produk</th>
                        <th class="py-2 px-2">Dihapus Pada</th>
                        <th class="py-2 px-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($categories as $category)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="py-3 px-2 font-medium text-gray-800">{{ $category->nama_kategori }}</td>
                        <td class="py-3 px-2 text-gray-600">{{ $category->products_count }} Produk</td>
                        <td class="py-3 px-2 text-gray-600">{{ $category->deleted_at->format('d M Y H:i') }}</td>
                        <td class="py-3 px-2 text-center space-x-2">
                            <button wire:click="restore({{ $category->id }})" wire:confirm="Apakah Anda yakin ingin mengembalikan kategori ini?" class="bg-blue-500 text-white text-xs px-3 py-1 rounded hover:bg-blue-600 transition">Kembalikan</button>
                            <button wire:click="forceDelete({{ $category->id }})" wire:confirm="PERINGATAN: Ini akan menghapus kategori dan semua produk terkait secara permanen. Lanjutkan?" class="bg-red-500 text-white text-xs px-3 py-1 rounded hover:bg-red-600 transition">Hapus Permanen</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-gray-500">Tidak ada kategori terhapus.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>