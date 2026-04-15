<div class="max-w-lg mx-auto space-y-4">
    <div class="flex justify-between items-center mb-4">
        <div>
            <h2 class="text-xl font-bold text-pink-700">Edit Transaksi</h2>
            <p class="text-sm text-gray-500">No. Invoice {{ $transaction->no_invoice }}</p>
        </div>
        <a href="/transactions/{{ $transaction->id }}" class="text-gray-500 hover:text-pink-600 text-sm font-semibold">Batal</a>
    </div>

    @if(session()->has('success'))
    <div class="p-3 bg-green-50 text-green-700 rounded border border-green-100 text-sm">
        {{ session('success') }}
    </div>
    @endif

    <form wire:submit.prevent="saveTransaction" class="space-y-4 bg-white rounded-xl shadow-sm border border-pink-50 p-5">
        @error('update')
        <div class="p-3 bg-red-50 text-red-700 rounded border border-red-100 text-sm">
            {{ $message }}
        </div>
        @enderror
        <div class="grid grid-cols-1 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Pelanggan</label>
                <select wire:model="customer_id" class="w-full rounded-lg border-gray-200 text-sm px-3 py-2">
                    <option value="">Pelanggan Umum</option>
                    @foreach($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->nama_customer }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Metode Pembayaran</label>
                <select wire:model="payment_method" class="w-full rounded-lg border-gray-200 text-sm px-3 py-2">
                    @foreach($paymentMethods as $method)
                    <option value="{{ $method }}">{{ $method }}</option>
                    @endforeach
                </select>
                @error('payment_method') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Tanggal Transaksi</label>
                <input type="date" wire:model="transaction_date" class="w-full rounded-lg border-gray-200 text-sm px-3 py-2">
                @error('transaction_date') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Status Ongkir</label>
                    <select wire:model="status_ongkir" class="w-full rounded-lg border-gray-200 text-sm px-3 py-2">
                        @foreach($shippingOptions as $option)
                        <option value="{{ $option }}">{{ $option }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Status Packing</label>
                    <select wire:model="status_packing" class="w-full rounded-lg border-gray-200 text-sm px-3 py-2">
                        @foreach($packingOptions as $option)
                        <option value="{{ $option }}">{{ $option }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Status Transaksi</label>
                <select wire:model="status" class="w-full rounded-lg border-gray-200 text-sm px-3 py-2">
                    @foreach($statusOptions as $option)
                    <option value="{{ $option }}">{{ $option }}</option>
                    @endforeach
                </select>
                @error('status') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Catatan</label>
                <textarea wire:model="catatan" rows="4" class="w-full rounded-lg border-gray-200 text-sm px-3 py-2"></textarea>
            </div>
        </div>

        <!-- Edit Items -->
        <div class="border-t border-gray-200 pt-4">
            <h3 class="font-bold text-gray-700 text-sm mb-3">Edit Item Transaksi</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left border border-gray-200 rounded">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="py-2 px-2">Produk</th>
                            <th class="py-2 px-2">Qty</th>
                            <th class="py-2 px-2">Harga</th>
                            <th class="py-2 px-2">Diskon</th>
                            <th class="py-2 px-2">Subtotal</th>
                            <th class="py-2 px-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($items as $index => $item)
                        <tr>
                            <td class="py-2 px-2">
                                <div class="font-medium text-gray-800">{{ $item['nama_produk_history'] }}</div>
                                <div class="text-xs text-gray-500">{{ $item['varian_history'] }}</div>
                            </td>
                            <td class="py-2 px-2">{{ $item['qty'] }}</td>
                            <td class="py-2 px-2 text-gray-600">Rp{{ number_format($item['harga_jual_history'], 0, ',', '.') }}</td>
                            <td class="py-2 px-2">Rp{{ number_format($item['diskon_item'] ?: 0, 0, ',', '.') }}</td>
                            <td class="py-2 px-2 font-bold text-pink-600">
                                Rp{{ number_format(($item['harga_jual_history'] - ($item['diskon_item'] ?: 0)) * $item['qty'], 0, ',', '.') }}
                            </td>
                            <td class="py-2 px-2">
                                <div class="flex items-center gap-1">
                                    <button type="button" wire:click="editItem({{ $index }})" @click.prevent class="text-blue-400 hover:text-blue-600 transition p-2 hover:bg-blue-50 rounded-full" title="Edit item">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button type="button" wire:click="removeItem({{ $index }})" wire:confirm="Hapus item ini dari transaksi?" @click.prevent class="text-pink-400 hover:text-red-500 transition p-2 hover:bg-pink-50 rounded-full" title="Hapus item">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <button type="submit" class="w-full bg-gradient-to-r from-pink-500 to-rose-400 text-white py-3 rounded-xl font-bold text-sm shadow hover:shadow-lg transition">Simpan Perubahan</button>
    </form>

    <button type="button" wire:click="openAddModal" class="mt-4 bg-pink-600 text-white px-4 py-2 rounded text-sm hover:bg-pink-700">Tambah Item Baru</button>

    <!-- Edit Item Modal -->
    @if($showEditModal)
    <div class="fixed inset-0 bg-gradient-to-r from-pink-50 to-white bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-bold mb-4">Edit Item</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Cari Produk/Varian</label>
                    <input type="text" wire:model.live.debounce.300ms="editSearch" placeholder="Cari nama produk atau varian..." class="w-full rounded border-gray-300 px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Ganti Produk Item</label>
                    <select wire:model.live="editProductItemId" class="w-full rounded border-gray-300 px-3 py-2">
                        @foreach($editProductItems as $productItem)
                        <option value="{{ $productItem->id }}">
                            {{ $productItem->product->nama_produk }} - {{ $productItem->variantString() }} (Stok: {{ $productItem->stok_akhir }})
                        </option>
                        @endforeach
                    </select>
                    @error('editProductItemId') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Qty</label>
                    <input type="number" wire:model="editQty" min="1" max="{{ $editMaxQty }}" class="w-full rounded border-gray-300 px-3 py-2">
                    <p class="text-[11px] text-gray-400 mt-1">Maksimal qty: {{ $editMaxQty }}</p>
                    @error('editQty') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Diskon per Item</label>
                    <input type="number" wire:model="editDiskon" min="0" class="w-full rounded border-gray-300 px-3 py-2">
                    @error('editDiskon') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" wire:click="cancelEdit" class="px-4 py-2 text-gray-600 border rounded">Batal</button>
                <button type="button" wire:click="saveEdit" class="px-4 py-2 bg-pink-600 text-white rounded">Simpan</button>
            </div>
        </div>
    </div>
    @endif

    <!-- Add Item Modal -->
    @if($showAddModal)
    <div class="fixed inset-0 bg-pink-100 bg-opacity-50 flex items-center justify-center z-50" x-data="{ searchOpen: false }">
        <div class="bg-white rounded-lg p-6 w-full max-w-lg">
            <h3 class="text-lg font-bold mb-4">Tambah Item Baru</h3>
            <div class="space-y-4">
                <!-- Search Product with Alpine Dropdown -->
                <div class="relative">
                    <input type="text" wire:model.live.debounce.300ms="search" @focus="searchOpen = true" @click="searchOpen = true" placeholder="🔍 Cari nama barang..." class="w-full rounded border-gray-300 px-3 py-2">

                    <!-- Dropdown -->
                    <div x-cloak x-show="searchOpen" @click.away="searchOpen = false" x-transition class="absolute z-50 left-0 right-0 mt-2 bg-white border border-gray-200 shadow-xl rounded max-h-80 overflow-y-auto">
                        <div class="p-2 space-y-2">
                            <div class="flex justify-between items-center px-2 pt-1 sticky top-0 bg-white">
                                <span class="text-xs font-bold text-gray-400">HASIL PENCARIAN</span>
                                <button type="button" @click="searchOpen = false" class="text-xs text-pink-500 font-bold bg-pink-50 px-3 py-1 rounded hover:bg-pink-100">Tutup</button>
                            </div>
                            @foreach($products as $prod)
                            <div class="border-b border-gray-50 pb-2 mb-2 last:border-0 last:mb-0 last:pb-0">
                                <div class="px-2 mb-1">
                                    <h3 class="font-bold text-gray-800 text-sm">{{ $prod->nama_produk }}</h3>
                                </div>
                                <div class="space-y-1">
                                    @foreach($prod->items as $item)
                                    @if($item->stok_akhir > 0)
                                    <div class="bg-gray-50 rounded p-2 mx-1 flex justify-between items-center border border-gray-100 hover:border-pink-200 hover:bg-pink-50/50 transition">
                                        <div>
                                            <p class="text-xs font-bold text-gray-700">
                                                {{ $item->variantOption1 ? $item->variantOption1->value : 'Standard' }}
                                                {{ $item->variantOption2 ? '/ '.$item->variantOption2->value : '' }}
                                            </p>
                                            <p class="text-xs text-gray-500">Stok: <strong class="text-green-600">{{ $item->stok_akhir }}</strong> | Harga: Rp{{ number_format($item->harga_jual,0,',','.') }}</p>
                                        </div>
                                        <button type="button" wire:click="selectProduct({{ $item->id }})" @click.stop="searchOpen = false" class="bg-white border border-pink-200 text-pink-600 hover:bg-pink-500 hover:text-white px-3 py-1 rounded text-xs font-bold shadow-sm transition">+</button>
                                    </div>
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                            @if($products->isEmpty())
                            <div class="text-center text-gray-400 py-4 text-xs">Produk tidak ditemukan atau habis stok.</div>
                            @endif
                        </div>
                    </div>
                </div>

                @if($selectedProduct)
                <div class="p-3 bg-gray-50 rounded">
                    <div class="font-medium">{{ $selectedProduct->product->nama_produk }}</div>
                    <div class="text-sm text-gray-500">{{ $selectedProduct->variantString() }} - Stok: {{ $selectedProduct->stok_akhir }}</div>
                    <div class="flex gap-2 mt-2">
                        <label for="">Jumlah</label>
                        <input type="number" wire:model="newItemQty" min="1" max="{{ $selectedProduct->stok_akhir }}" class="w-20 rounded border-gray-300 px-2 py-1" placeholder="Qty">
                    </div>
                    <div class="flex gap-2 mt-2">
                        <label for="">Diskon</label>
                        <input type="number" wire:model="newItemDiskon" min="0" class="w-24 rounded border-gray-300 px-2 py-1" placeholder="Diskon">
                        <button type="button" wire:click="addItem" class="bg-pink-600 text-white px-3 py-1 rounded text-sm">Tambah</button>
                    </div>
                    @error('add') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                    @error('newItemQty') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                    @error('newItemDiskon') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>
                @endif
            </div>
            <div class="flex justify-end mt-4">
                <button type="button" wire:click="closeAddModal" class="px-4 py-2 text-gray-600 border rounded">Tutup</button>
            </div>
        </div>
    </div>
    @endif
</div>