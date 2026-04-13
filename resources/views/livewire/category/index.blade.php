<div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
        <div>
            <h2 class="text-xl font-bold text-pink-700">Manajemen Kategori</h2>
        </div>
        <a href="/categories/trashed" class="bg-gray-50 text-gray-700 px-4 py-2 rounded-lg border border-gray-200 hover:bg-gray-100 transition text-sm font-semibold">Kategori Terhapus</a>
    </div>

    <div class="bg-white p-4 rounded-xl shadow-sm mb-6 border border-pink-100">
        <form wire:submit="save" class="flex gap-2">
            <input type="text" wire:model="nama_kategori" placeholder="Nama Kategori Baru" class="flex-1 rounded-lg border-pink-200 focus:ring-pink-500 focus:border-pink-500 text-sm px-3 py-2 border shadow-inner">
            <button type="submit" class="bg-pink-500 hover:bg-pink-600 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow hover:shadow-lg transition">Tambah</button>
        </form>
        @error('nama_kategori') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
    </div>

    <div class="space-y-3">
        @foreach($categories as $cat)
        <div class="bg-white p-4 rounded-xl shadow-sm flex justify-between items-center border border-pink-50 hover:shadow-md transition">
            <a href="/categories/{{ $cat->id }}" class="flex-1">
                <p class="font-bold text-gray-800 hover:text-pink-600 transition">{{ $cat->nama_kategori }}</p>
                <p class="text-xs text-gray-400 mt-1"><span class="bg-pink-50 text-pink-600 px-2 py-0.5 rounded-full">{{ $cat->products_count }} Produk</span></p>
            </a>
            <div class="flex gap-2">
                <a href="/categories/{{ $cat->id }}/edit" class="text-blue-400 hover:text-blue-600 transition p-2 hover:bg-blue-50 rounded-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </a>
                <button wire:click="delete({{ $cat->id }})" class="text-pink-400 hover:text-red-500 transition p-2 hover:bg-pink-50 rounded-full" onclick="return confirm('Hapus kategori ini?')">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
        </div>
        @endforeach
        @if($categories->isEmpty())
        <div class="text-center text-gray-400 py-8 text-sm">
            Belum ada kategori.
        </div>
        @endif
    </div>
</div>