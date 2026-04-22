<div>
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-pink-700">Edit Produk</h2>
        <a href="/products/{{ $product->id }}" class="text-gray-400 hover:text-pink-600 transition">Batal</a>
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
                @if(is_object($foto))
                <img src="{{ $foto->temporaryUrl() }}" alt="Preview" class="mt-2 w-20 h-20 object-cover rounded-lg border">
                @else
                <img src="{{ asset('storage/' . $foto) }}" alt="Current" class="mt-2 w-20 h-20 object-cover rounded-lg border">
                @endif
                @endif
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold tracking-wide text-gray-500 mb-1">Kode Produk</label>
                    <input type="text" value="{{ $product->kode_produk }}" disabled class="w-full rounded-lg border-gray-200 bg-gray-50 text-sm px-3 py-2 border">
                </div>
                <div>
                    <label class="block text-xs font-semibold tracking-wide text-gray-500 mb-1">Kategori <span class="text-red-500">*</span></label>
                    <select wire:model="category_id" required class="w-full rounded-lg border-pink-200 focus:ring-pink-500 focus:border-pink-500 text-sm px-3 py-2 border shadow-inner">
                        <option value="">Pilih...</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
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

        <!-- Matrix Editor -->
        <div class="bg-white p-5 rounded-xl shadow-sm border border-pink-50 space-y-4">
            <h3 class="font-bold text-pink-700 text-sm mb-2 border-b border-pink-50 pb-2">Harga & Stok (Seluruh Item)</h3>

            <!-- Update Seragam -->
            @if(count($items) > 1)
            <div class="bg-yellow-50 p-3 rounded-lg border border-yellow-200 grid grid-cols-4 gap-2 items-end">
                <div class="col-span-4 text-xs font-bold text-yellow-700 mb-1">Set Harga Seragam (Opsional)</div>
                <div>
                    <label class="block text-[10px] text-gray-500 mb-1">Modal</label>
                    <input type="number" wire:model="bulk_modal" class="w-full rounded text-xs px-2 py-1 border-yellow-300 focus:border-yellow-500 focus:ring-yellow-500">
                </div>
                <div>
                    <label class="block text-[10px] text-gray-500 mb-1">Sell</label>
                    <input type="number" wire:model="bulk_sell" class="w-full rounded text-xs px-2 py-1 border-yellow-300 focus:border-yellow-500 focus:ring-yellow-500 text-blue-600">
                </div>
                <div>
                    <label class="block text-[10px] text-gray-500 mb-1">Jual</label>
                    <input type="number" wire:model="bulk_jual" class="w-full rounded text-xs px-2 py-1 border-yellow-300 focus:border-yellow-500 focus:ring-yellow-500 text-pink-600">
                </div>
                <div>
                    <button type="button" wire:click="applyBulkPrice" class="w-full bg-yellow-500 text-white text-xs font-bold py-1.5 rounded hover:bg-yellow-600 transition">Terapkan</button>
                </div>
            </div>
            @endif

            <div class="flex justify-between items-center mb-2">
                <span class="text-xs font-semibold text-gray-600">Kelola Item Produk</span>
                <button type="button" wire:click="addItem" class="bg-green-500 text-white text-xs px-3 py-1 rounded hover:bg-green-600 transition">+ Tambah Item</button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left whitespace-nowrap min-w-max">
                    <thead class="text-gray-500 border-b border-gray-100">
                        <tr>
                            <th class="py-2 sticky left-0 bg-white z-10">Item/Varian</th>
                            <th class="py-2 px-1">Foto</th>
                            <th class="py-2 px-1">Modal</th>
                            <th class="py-2 px-1 text-blue-600">Sell/Grsr</th>
                            <th class="py-2 px-1 text-pink-600">Jual</th>
                            <th class="py-2 px-1">Stok</th>
                            <th class="py-2 px-1">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($items as $index => $row)
                        <tr>
                            <td class="py-2 font-medium text-gray-700 sticky left-0 bg-white z-10">
                                {{ $row['v1'] }} {{ $row['v2'] ? ' / '.$row['v2'] : '' }}
                            </td>
                            <td class="py-2 px-1">
                                <input type="file" wire:model="item_fotos.{{$index}}" accept="image/*" class="w-16 text-[10px]">
                                @if(isset($item_fotos[$index]))
                                @if(is_object($item_fotos[$index]))
                                <img src="{{ $item_fotos[$index]->temporaryUrl() }}" alt="Preview" class="mt-1 w-8 h-8 object-cover rounded">
                                @elseif($item_fotos[$index])
                                <img src="{{ asset('storage/' . $item_fotos[$index]) }}" alt="Current" class="mt-1 w-8 h-8 object-cover rounded">
                                @endif
                                @endif
                            </td>
                            <td class="py-2 px-1"><input type="number" wire:model="items.{{$index}}.modal" class="w-16 rounded text-[10px] px-1 py-1 border-gray-300"></td>
                            <td class="py-2 px-1"><input type="number" wire:model="items.{{$index}}.sell" class="w-16 rounded text-[10px] px-1 py-1 border-gray-300 text-blue-600"></td>
                            <td class="py-2 px-1"><input type="number" wire:model="items.{{$index}}.jual" class="w-16 rounded text-[10px] px-1 py-1 border-gray-300 text-pink-600"></td>
                            <td class="py-2 px-1"><input type="number" wire:model="items.{{$index}}.stok" class="w-14 rounded text-[10px] px-1 py-1 border-gray-300 text-center"></td>
                            <td class="py-2 px-1">
                                <button type="button" wire:click="deleteItem({{ $index }})" wire:confirm="Apakah Anda yakin ingin menghapus item ini?" class="bg-red-500 text-white text-xs px-2 py-1 rounded hover:bg-red-600 transition">Hapus</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <button type="submit" class="w-full bg-gradient-to-r from-pink-500 to-rose-400 hover:from-pink-600 hover:to-rose-500 text-white py-3 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5">
            Simpan Perubahan
        </button>
    </form>

    <!-- Modal for Adding Item -->
    @if($showAddItemModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h3 class="text-lg font-bold mb-4">Tambah Item Produk</h3>
            <form wire:submit="addItemFromModal">
                @if($variant_attributes->count() > 0)
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ $variant_attributes[0]->name }}</label>
                    <select wire:model="selectedVariant1" class="w-full border border-gray-300 rounded px-3 py-2">
                        <option value="">Pilih {{ $variant_attributes[0]->name }}</option>
                        @foreach($variant_attributes[0]->options as $option)
                        <option value="{{ $option->value }}">{{ $option->value }}</option>
                        @endforeach
                    </select>
                    @error('selectedVariant1') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                @if($variant_attributes->count() > 1)
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ $variant_attributes[1]->name }}</label>
                    <select wire:model="selectedVariant2" class="w-full border border-gray-300 rounded px-3 py-2">
                        <option value="">Pilih {{ $variant_attributes[1]->name }}</option>
                        @foreach($variant_attributes[1]->options as $option)
                        <option value="{{ $option->value }}">{{ $option->value }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                @else
                <p class="text-sm text-gray-600">Tidak ada varian yang tersedia untuk produk ini.</p>
                @endif
                <div class="flex justify-end space-x-2">
                    <button type="button" wire:click="$set('showAddItemModal', false)" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Tambah</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>