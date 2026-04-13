<div class="max-w-lg mx-auto">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-pink-700">Update Customer</h2>
        <a href="/customers/{{ $customer->id }}" class="text-gray-500 hover:text-pink-600 text-sm font-semibold">Batal</a>
    </div>

    <form wire:submit.prevent="save" class="space-y-4 bg-white rounded-xl shadow-sm border border-pink-50 p-5">
        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1">Nama Customer</label>
            <input type="text" wire:model="nama_customer" class="w-full rounded-lg border-gray-200 text-sm px-3 py-2">
            @error('nama_customer') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1">No WhatsApp</label>
            <input type="text" wire:model="no_whatsapp" class="w-full rounded-lg border-gray-200 text-sm px-3 py-2" placeholder="08xxxx">
            @error('no_whatsapp') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1">Alamat</label>
            <textarea wire:model="alamat" rows="3" class="w-full rounded-lg border-gray-200 text-sm px-3 py-2"></textarea>
            @error('alamat') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1">Catatan</label>
            <textarea wire:model="catatan" rows="3" class="w-full rounded-lg border-gray-200 text-sm px-3 py-2"></textarea>
            @error('catatan') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="w-full bg-gradient-to-r from-pink-500 to-rose-400 text-white py-3 rounded-xl font-bold text-sm shadow hover:shadow-lg transition">
            Simpan Perubahan
        </button>
    </form>
</div>
