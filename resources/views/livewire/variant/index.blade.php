<div>
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-pink-700">Master Data Varian</h2>
        <a href="/products" class="text-gray-400 hover:text-pink-600 transition">Kembali</a>
    </div>

    <!-- Tambah Atribut -->
    <div class="bg-white p-4 rounded-xl shadow-sm mb-6 border border-pink-100">
        <form wire:submit="createAttribute" class="flex gap-2">
            <input type="text" wire:model="nama_attribute" placeholder="Nama Atribut Baru (Cth: Warna, Ukuran)" class="flex-1 rounded-lg border-pink-200 focus:ring-pink-500 focus:border-pink-500 text-sm px-3 py-2 border shadow-inner">
            <button type="submit" class="bg-pink-500 hover:bg-pink-600 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow hover:shadow-lg transition">Tambah Atribut</button>
        </form>
        @error('nama_attribute') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
    </div>

    <div class="space-y-4">
        @foreach($variantData as $attr)
        <div class="bg-white p-4 rounded-xl shadow-sm border border-pink-50">
            <div class="flex justify-between items-center border-b border-pink-50 pb-2 mb-3">
                <h3 class="font-bold text-gray-800">{{ $attr->name }}</h3>
                <button wire:click="deleteAttribute({{ $attr->id }})" class="text-pink-400 hover:text-red-500 transition text-xs" onclick="return confirm('Hapus atribut ini beserta semua nilainya?')">Hapus Atribut</button>
            </div>
            
            <div class="flex flex-wrap gap-2 mb-3">
                @foreach($attr->options as $opt)
                <div class="bg-gray-100 text-gray-700 text-xs px-3 py-1 rounded pl-3 pr-1 flex items-center border border-gray-200">
                    <span>{{ $opt->value }}</span>
                    <button wire:click="deleteOption({{ $opt->id }})" class="ml-2 text-gray-400 hover:text-red-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>
                @endforeach
            </div>

            <form wire:submit="createOption({{ $attr->id }})" class="flex gap-2 w-full mt-2">
                <input type="text" wire:model="new_options.{{ $attr->id }}" placeholder="Input isi untuk {{ $attr->name }}..." class="w-full rounded text-sm px-2 py-1.5 border border-pink-200 focus:ring-pink-500 focus:border-pink-500">
                <button type="submit" class="bg-pink-100 text-pink-600 hover:bg-pink-200 px-3 py-1.5 rounded text-sm font-semibold transition">Tambah</button>
            </form>
        </div>
        @endforeach

        @if($variantData->isEmpty())
        <div class="text-center text-gray-400 py-6 text-sm bg-white rounded-xl shadow-sm">
            Belum ada atribut varian.
        </div>
        @endif
    </div>
</div>
