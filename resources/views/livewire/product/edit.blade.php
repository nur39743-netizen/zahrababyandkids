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

            <div>
                <label class="block text-xs font-semibold tracking-wide text-gray-500 mb-1">Status Kepemilikan</label>
                <select wire:model="owner_id" class="w-full rounded-lg border-pink-200 focus:ring-pink-500 focus:border-pink-500 text-sm px-3 py-2 border shadow-inner">
                    <option value="">Milik Sendiri</option>
                    @foreach($owners as $own)
                    <option value="{{ $own->id }}">Konsinyasi: {{ $own->nama_owner }}</option>
                    @endforeach
                </select>
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

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left whitespace-nowrap">
                    <thead class="text-gray-500 border-b border-gray-100">
                        <tr>
                            <th class="py-2">Item/Varian</th>
                            <th class="py-2 px-1">Modal</th>
                            <th class="py-2 px-1 text-blue-600">Sell/Grsr</th>
                            <th class="py-2 px-1 text-pink-600">Jual</th>
                            <th class="py-2 px-1">Stok</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($items as $id => $row)
                        <tr>
                            <td class="py-2 font-medium text-gray-700">
                                {{ $row['v1'] }} {{ $row['v2'] ? ' / '.$row['v2'] : '' }}
                            </td>
                            <td class="py-2 px-1"><input type="number" wire:model="items.{{$id}}.modal" class="w-16 rounded text-[10px] px-1 py-1 border-gray-300"></td>
                            <td class="py-2 px-1"><input type="number" wire:model="items.{{$id}}.sell" class="w-16 rounded text-[10px] px-1 py-1 border-gray-300 text-blue-600"></td>
                            <td class="py-2 px-1"><input type="number" wire:model="items.{{$id}}.jual" class="w-16 rounded text-[10px] px-1 py-1 border-gray-300 text-pink-600"></td>
                            <td class="py-2 px-1"><input type="number" wire:model="items.{{$id}}.stok" class="w-14 rounded text-[10px] px-1 py-1 border-gray-300 text-center"></td>
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
</div>
