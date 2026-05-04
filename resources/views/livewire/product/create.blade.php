<div>
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-pink-700">Tambah Produk</h2>
        <a href="/products" class="text-gray-400 hover:text-pink-600 transition">Batal</a>
    </div>

    <form wire:submit="save" class="space-y-6">
        <!-- Basic Info -->
        <div class="bg-white p-5 rounded-xl shadow-sm border border-pink-50 space-y-4">
            <div>
                <label class="block text-xs font-semibold tracking-wide text-gray-500 mb-1">Nama Produk <span class="text-red-500">*</span></label>
                <input type="text" wire:model="nama_produk" required class="w-full rounded-lg border-pink-200 focus:ring-pink-500 focus:border-pink-500 text-sm px-3 py-2 border shadow-inner">
            </div>

            <div>
                <label class="block text-xs font-semibold tracking-wide text-gray-500 mb-1">Foto Produk</label>
                <input type="file" wire:model="foto" accept="image/*" class="w-full rounded-lg border-pink-200 focus:ring-pink-500 focus:border-pink-500 text-sm px-3 py-2 border shadow-inner">
                @if($foto)
                <img src="{{ $foto->temporaryUrl() }}" alt="Preview" class="mt-2 w-20 h-20 object-cover rounded-lg border">
                @endif
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold tracking-wide text-gray-500 mb-1">Kategori <span class="text-red-500">*</span></label>
                    <select wire:model="category_id" required class="w-full rounded-lg border-pink-200 focus:ring-pink-500 focus:border-pink-500 text-sm px-3 py-2 border shadow-inner">
                        <option value="">Pilih...</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold tracking-wide text-gray-500 mb-1">Supplier</label>
                    <select wire:model="supplier_id" class="w-full rounded-lg border-pink-200 focus:ring-pink-500 focus:border-pink-500 text-sm px-3 py-2 border shadow-inner">
                        <option value="">Pilih Supplier...</option>
                        @foreach($suppliers as $sup)
                        <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold tracking-wide text-gray-500 mb-1">Status Kepemilikan</label>
                    <select wire:model="owner_id" class="w-full rounded-lg border-pink-200 focus:ring-pink-500 focus:border-pink-500 text-sm px-3 py-2 border shadow-inner">
                        <option value="">Milik Sendiri</option>
                        @foreach($owners as $own)
                        <option value="{{ $own->id }}">{{ $own->nama_owner }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold tracking-wide text-gray-500 mb-1">Gender Produk <span class="text-red-500">*</span></label>
                    <select wire:model="gender" class="w-full rounded-lg border-pink-200 focus:ring-pink-500 focus:border-pink-500 text-sm px-3 py-2 border shadow-inner">
                        <option value="male">Laki-laki / Male</option>
                        <option value="female">Perempuan / Female</option>
                        <option value="unisex">Unisex / Netral</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold tracking-wide text-gray-500 mb-1">Bahan</label>
                    <input type="text" wire:model="bahan" placeholder="Contoh: Katun, Rayon, Denim" class="w-full rounded-lg border-pink-200 focus:ring-pink-500 focus:border-pink-500 text-sm px-3 py-2 border shadow-inner">
                </div>
            </div>
        </div>

        <!-- Mode Varian Toggle -->
        <div class="bg-white p-4 rounded-xl shadow-sm border border-pink-50 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-gray-800 text-sm">Gunakan Varian</h3>
                <p class="text-[10px] text-gray-400">Pilih jika produk punya ukuran/warna berbeda.</p>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" wire:model.live="has_variant" class="sr-only peer">
                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-pink-500"></div>
            </label>
        </div>

        @if(!$has_variant)
        <!-- Harga Tunggal -->
        <div class="bg-white p-5 rounded-xl shadow-sm border border-pink-50 space-y-4">
            <h3 class="font-bold text-pink-700 text-sm mb-2 border-b border-pink-50 pb-2">Harga & Stok Tunggal</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold tracking-wide text-gray-500 mb-1">Harga Modal</label>
                    <input type="number" wire:model="harga_modal" class="w-full rounded-lg border-pink-200 focus:ring-pink-500 focus:border-pink-500 text-sm px-3 py-2 border shadow-inner" onfocus="if(this.value=='0')this.value=''" onblur="if(this.value=='')this.value='0'">
                </div>
                <div>
                    <label class="block text-xs font-semibold tracking-wide text-gray-500 mb-1">Stok Awal</label>
                    <input type="number" wire:model="stok_akhir" class="w-full rounded-lg border-pink-200 focus:ring-pink-500 focus:border-pink-500 text-sm px-3 py-2 border shadow-inner" onfocus="if(this.value=='0')this.value=''" onblur="if(this.value=='')this.value='0'">
                </div>
                <div>
                    <label class="block text-xs font-semibold tracking-wide text-gray-500 mb-1">Harga Sell (Grosir)</label>
                    <input type="number" wire:model="harga_sell" class="w-full rounded-lg border-pink-200 focus:ring-pink-500 focus:border-pink-500 text-sm px-3 py-2 border shadow-inner text-blue-600" onfocus="if(this.value=='0')this.value=''" onblur="if(this.value=='')this.value='0'">
                </div>
                <div>
                    <label class="block text-xs font-semibold tracking-wide text-gray-500 mb-1">Harga Jual (Retail)</label>
                    <input type="number" wire:model="harga_jual" class="w-full rounded-lg border-pink-200 focus:ring-pink-500 focus:border-pink-500 text-sm px-3 py-2 border shadow-inner text-pink-600" onfocus="if(this.value=='0')this.value=''" onblur="if(this.value=='')this.value='0'">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold tracking-wide text-gray-500 mb-1">Foto Item</label>
                <input type="file" wire:model="item_fotos.0" accept="image/*" class="w-full rounded-lg border-pink-200 focus:ring-pink-500 focus:border-pink-500 text-sm px-3 py-2 border shadow-inner">
                @if(isset($item_fotos[0]))
                <img src="{{ $item_fotos[0]->temporaryUrl() }}" alt="Preview" class="mt-2 w-20 h-20 object-cover rounded-lg border">
                @endif
            </div>
        </div>
        @else
        <!-- Varian Generator -->
        <div class="bg-white p-5 rounded-xl shadow-sm border border-pink-50 space-y-4">
            <div class="flex justify-between items-center mb-2 border-b border-pink-50 pb-2">
                <h3 class="font-bold text-pink-700 text-sm">Pilih Atribut Varian</h3>
                <a href="/variants" class="text-[10px] bg-pink-100 text-pink-600 px-2 py-1 rounded font-bold hover:bg-pink-200">Kelola Master Varian</a>
            </div>

            <div class="space-y-3 bg-pink-50/30 p-3 rounded-lg border border-pink-100">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Varian 1 (Contoh: Warna) <span class="text-red-500">*</span></label>
                    <select wire:model.live="variant1_id" class="w-full rounded-lg border-pink-200 text-sm px-3 py-2 focus:border-pink-500 focus:ring-pink-500 shadow-inner">
                        <option value="">-- Pilih Atribut Varian 1 --</option>
                        @foreach($variant_attributes as $attr)
                        @if($attr->id != $variant2_id)
                        <option value="{{ $attr->id }}">{{ $attr->name }}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
                @if($variant1_id)
                <div class="mt-2 flex flex-wrap gap-2">
                    @php $options1 = $variant_attributes->firstWhere('id', $variant1_id)->options ?? []; @endphp
                    @foreach($options1 as $opt)
                    <label class="inline-flex items-center space-x-1 border border-pink-200 px-2 py-1 rounded text-xs bg-white cursor-pointer hover:bg-pink-50 transition">
                        <input type="checkbox" wire:model.live="variant1_options.{{ $opt->id }}" class="rounded text-pink-500 focus:ring-pink-500">
                        <span class="text-gray-700 font-medium">{{ $opt->value }}</span>
                    </label>
                    @endforeach
                </div>
                @endif
            </div>

            <div class="space-y-3 bg-pink-50/30 p-3 rounded-lg border border-pink-100">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Varian 2 (Opsional, Contoh: Ukuran)</label>
                    <select wire:model.live="variant2_id" class="w-full rounded-lg border-pink-200 text-sm px-3 py-2 focus:border-pink-500 focus:ring-pink-500 shadow-inner">
                        <option value="">-- Pilih Atribut Varian 2 --</option>
                        @foreach($variant_attributes as $attr)
                        @if($attr->id != $variant1_id)
                        <option value="{{ $attr->id }}">{{ $attr->name }}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
                @if($variant2_id)
                <div class="mt-2 flex flex-wrap gap-2">
                    @php $options2 = $variant_attributes->firstWhere('id', $variant2_id)->options ?? []; @endphp
                    @foreach($options2 as $opt)
                    <label class="inline-flex items-center space-x-1 border border-pink-200 px-2 py-1 rounded text-xs bg-white cursor-pointer hover:bg-pink-50 transition">
                        <input type="checkbox" wire:model.live="variant2_options.{{ $opt->id }}" class="rounded text-pink-500 focus:ring-pink-500">
                        <span class="text-gray-700 font-medium">{{ $opt->value }}</span>
                    </label>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        @if(count($matrix) > 0)
        <!-- Matrix -->
        <div class="bg-white p-5 rounded-xl shadow-sm border border-pink-50 space-y-4">
            <h3 class="font-bold text-pink-700 text-sm mb-2 border-b border-pink-50 pb-2">Matriks Varian</h3>

            <!-- Update Seragam -->
            <div class="bg-yellow-50 p-3 rounded-lg border border-yellow-200 grid grid-cols-4 gap-2 items-end">
                <div class="col-span-4 text-xs font-bold text-yellow-700 mb-1">Set Harga Seragam</div>
                <div>
                    <label class="block text-[10px] text-gray-500 mb-1">Modal</label>
                    <input type="number" wire:model="bulk_modal" class="w-full rounded text-xs px-2 py-1 border-yellow-300 focus:border-yellow-500 focus:ring-yellow-500" onfocus="if(this.value=='0')this.value=''" onblur="if(this.value=='')this.value='0'">
                </div>
                <div>
                    <label class="block text-[10px] text-gray-500 mb-1">Sell</label>
                    <input type="number" wire:model="bulk_sell" class="w-full rounded text-xs px-2 py-1 border-yellow-300 focus:border-yellow-500 focus:ring-yellow-500 text-blue-600" onfocus="if(this.value=='0')this.value=''" onblur="if(this.value=='')this.value='0'">
                </div>
                <div>
                    <label class="block text-[10px] text-gray-500 mb-1">Jual</label>
                    <input type="number" wire:model="bulk_jual" class="w-full rounded text-xs px-2 py-1 border-yellow-300 focus:border-yellow-500 focus:ring-yellow-500 text-pink-600" onfocus="if(this.value=='0')this.value=''" onblur="if(this.value=='')this.value='0'">
                </div>
                <div>
                    <button type="button" wire:click="applyBulkPrice" class="w-full bg-yellow-500 text-white text-xs font-bold py-1.5 rounded hover:bg-yellow-600 transition">Terapkan</button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left whitespace-nowrap">
                    <thead class="text-gray-500 border-b border-gray-100">
                        <tr>
                            <th class="py-2 sticky left-0 bg-white z-10">Varian</th>
                            <th class="py-2 px-1">Foto</th>
                            <th class="py-2 px-1">Modal</th>
                            <th class="py-2 px-1 text-blue-600">Sell</th>
                            <th class="py-2 px-1 text-pink-600">Jual</th>
                            <th class="py-2 px-1">Stok</th>
                            <th class="py-2 px-1">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($matrix as $index => $row)
                        <tr>
                            <td class="py-2 font-medium text-gray-700 sticky left-0 bg-white z-10">
                                {{ $row['v1_val'] }} {{ $row['v2_val'] ? ' / '.$row['v2_val'] : '' }}
                            </td>
                            <td class="py-2 px-1">
                                <input type="file" wire:model="item_fotos.{{$index}}" accept="image/*" class="w-16 text-[10px]">
                                @if(isset($item_fotos[$index]))
                                <img src="{{ $item_fotos[$index]->temporaryUrl() }}" alt="Preview" class="mt-1 w-8 h-8 object-cover rounded">
                                @endif
                            </td>
                            <td class="py-2 px-1"><input type="number" wire:model="matrix.{{$index}}.modal" class="w-16 rounded text-[10px] px-1 py-1 border-gray-300" onfocus="if(this.value=='0')this.value=''" onblur="if(this.value=='')this.value='0'"></td>
                            <td class="py-2 px-1"><input type="number" wire:model="matrix.{{$index}}.sell" class="w-16 rounded text-[10px] px-1 py-1 border-gray-300 text-blue-600" onfocus="if(this.value=='0')this.value=''" onblur="if(this.value=='')this.value='0'"></td>
                            <td class="py-2 px-1"><input type="number" wire:model="matrix.{{$index}}.jual" class="w-16 rounded text-[10px] px-1 py-1 border-gray-300 text-pink-600" onfocus="if(this.value=='0')this.value=''" onblur="if(this.value=='')this.value='0'"></td>
                            <td class="py-2 px-1"><input type="number" wire:model="matrix.{{$index}}.stok" class="w-14 rounded text-[10px] px-1 py-1 border-gray-300 text-center" onfocus="if(this.value=='0')this.value=''" onblur="if(this.value=='')this.value='0'"></td>
                            <td class="py-2 px-1">
                                <button type="button" wire:click="deleteItem({{ $index }})" wire:confirm="Apakah Anda yakin ingin menghapus item ini?" class="bg-red-500 text-white text-xs px-2 py-1 rounded hover:bg-red-600 transition">Hapus</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
        @endif

        <button type="submit" class="w-full bg-gradient-to-r from-pink-500 to-rose-400 hover:from-pink-600 hover:to-rose-500 text-white py-3 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5">
            Simpan Produk
        </button>
    </form>
</div>