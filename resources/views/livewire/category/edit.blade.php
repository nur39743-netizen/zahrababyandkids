<div>
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-pink-700">Edit Kategori</h2>
        <a href="/categories" class="text-gray-400 hover:text-pink-600 transition">Batal</a>
    </div>
    
    <div class="bg-white p-5 rounded-xl shadow-sm mb-6 border border-pink-100">
        <form wire:submit="save" class="space-y-4">
            <div>
                <label class="block text-xs font-semibold tracking-wide text-gray-500 mb-1">Nama Kategori</label>
                <input type="text" wire:model="nama_kategori" class="w-full rounded-lg border-pink-200 focus:ring-pink-500 focus:border-pink-500 text-sm px-3 py-2 border shadow-inner">
                @error('nama_kategori') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>
            
            <button type="submit" class="w-full bg-pink-500 hover:bg-pink-600 text-white px-4 py-2 rounded-lg text-sm font-bold shadow transition">Simpan Perubahan</button>
        </form>
    </div>
</div>
