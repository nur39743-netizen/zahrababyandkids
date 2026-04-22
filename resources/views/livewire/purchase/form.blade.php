<div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
        <div>
            <h2 class="text-xl font-bold text-pink-700">
                {{ $purchase_id ? 'Edit Transaksi Pembelian' : 'Transaksi Pembelian Baru' }}
            </h2>
        </div>
        <a href="/purchases" class="text-gray-500 hover:text-gray-700 transition font-semibold text-sm">
            &larr; Kembali
        </a>
    </div>

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <form wire:submit="save" class="grid gap-6 lg:grid-cols-3">
        <!-- Informasi Umum -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-5 rounded-xl shadow-sm border border-pink-100">
                <h3 class="font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Informasi Pembelian</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Supplier *</label>
                        <select wire:model="supplier_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm px-3 py-2 border" required>
                            <option value="">-- Pilih Supplier --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                        @error('supplier_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">No. Nota / Referensi</label>
                        <input type="text" wire:model="reference_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm px-3 py-2 border" placeholder="Contoh: PO-2023...">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal *</label>
                        <input type="date" wire:model="purchase_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm px-3 py-2 border" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status Barang *</label>
                        <select wire:model="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm px-3 py-2 border">
                            <option value="pending">Pending (Draft)</option>
                            <option value="ordered">Dipesan (Booking)</option>
                            <option value="received">Diterima (Stok Masuk)</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Jika 'Diterima', stok akan bertambah secara otomatis.</p>
                    </div>
                </div>
            </div>

            <!-- Keranjang -->
            <div class="bg-white p-5 rounded-xl shadow-sm border border-pink-100">
                <h3 class="font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Rincian Barang</h3>
                
                <!-- Pencarian -->
                <div class="relative mb-4">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama barang..." class="w-full rounded-lg border-pink-200 focus:ring-pink-500 focus:border-pink-500 text-sm px-4 py-2.5 border shadow-sm">
                    
                    @if(count($searchResults) > 0)
                    <div class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                        @foreach($searchResults as $res)
                        <button type="button" wire:click="addItem({{ $res->id }})" class="w-full text-left px-4 py-3 border-b hover:bg-pink-50 transition flex justify-between items-center">
                            <div>
                                <p class="font-bold text-gray-800 text-sm">{{ $res->product->nama_produk }}</p>
                                <p class="text-xs text-gray-500">{{ $res->variantString() }}</p>
                            </div>
                            <span class="text-xs font-semibold text-pink-600 bg-pink-100 px-2 py-1 rounded">Stok: {{ $res->stok_akhir }}</span>
                        </button>
                        @endforeach
                    </div>
                    @endif
                </div>

                <!-- List Keranjang -->
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-600">
                            <tr>
                                <th class="px-3 py-2">Barang</th>
                                <th class="px-3 py-2 w-24">Jml</th>
                                <th class="px-3 py-2 w-32">Harga Satuan</th>
                                <th class="px-3 py-2 w-32 text-right">Subtotal</th>
                                <th class="px-3 py-2 w-10"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($items as $index => $item)
                            <tr>
                                <td class="px-3 py-3">
                                    <p class="font-bold text-gray-800">{{ $item['name'] }}</p>
                                    <p class="text-xs text-gray-500">{{ $item['variant'] }}</p>
                                </td>
                                <td class="px-3 py-3">
                                    <input type="number" wire:model.lazy="items.{{ $index }}.quantity" wire:change="calculateSubtotal({{ $index }})" min="1" class="w-full rounded border-gray-300 text-sm px-2 py-1 focus:ring-pink-500 focus:border-pink-500">
                                </td>
                                <td class="px-3 py-3">
                                    <input type="number" wire:model.lazy="items.{{ $index }}.unit_cost" wire:change="calculateSubtotal({{ $index }})" min="0" class="w-full rounded border-gray-300 text-sm px-2 py-1 focus:ring-pink-500 focus:border-pink-500">
                                </td>
                                <td class="px-3 py-3 text-right font-semibold text-gray-700">
                                    {{ number_format($item['subtotal'], 0, ',', '.') }}
                                </td>
                                <td class="px-3 py-3 text-center">
                                    <button type="button" wire:click="removeItem({{ $index }})" class="text-red-400 hover:text-red-600 p-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                            @if(count($items) === 0)
                            <tr>
                                <td colspan="5" class="text-center py-6 text-gray-400">Keranjang kosong. Cari barang di atas.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                @error('items') <span class="text-red-500 text-xs mt-2 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Pembayaran & Simpan -->
        <div class="space-y-6">
            <div class="bg-white p-5 rounded-xl shadow-sm border border-pink-100">
                <h3 class="font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Rincian Pembayaran</h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-600">Total Harga Barang</span>
                        <span class="font-semibold text-gray-800">Rp {{ number_format($this->totalAmount, 0, ',', '.') }}</span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Ongkos Kirim</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" wire:model.lazy="shipping_cost" class="pl-10 block w-full rounded-md border-gray-300 focus:border-pink-500 focus:ring-pink-500 sm:text-sm px-3 py-2 border text-right">
                        </div>
                    </div>

                    <div class="flex justify-between items-center py-3 border-y border-dashed border-gray-200">
                        <span class="font-bold text-gray-800">Total Tagihan</span>
                        <span class="font-bold text-pink-700 text-lg">Rp {{ number_format($this->totalAmount + (float)$shipping_cost, 0, ',', '.') }}</span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jumlah Dibayar (DP / Lunas)</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" wire:model.lazy="amount_paid" class="pl-10 block w-full rounded-md border-gray-300 focus:border-pink-500 focus:ring-pink-500 sm:text-sm px-3 py-2 border text-right font-semibold text-blue-600">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status Pembayaran</label>
                        <select wire:model.live="payment_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm px-3 py-2 border font-bold
                            {{ $payment_status == 'paid' ? 'text-green-600 bg-green-50' : '' }}
                            {{ $payment_status == 'partial' ? 'text-yellow-600 bg-yellow-50' : '' }}
                            {{ $payment_status == 'unpaid' ? 'text-red-600 bg-red-50' : '' }}">
                            <option value="unpaid" class="text-red-600 font-bold">BELUM BAYAR</option>
                            <option value="partial" class="text-yellow-600 font-bold">BELUM LUNAS (DP)</option>
                            <option value="paid" class="text-green-600 font-bold">LUNAS</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Catatan Internal</label>
                        <textarea wire:model="notes" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm px-3 py-2 border"></textarea>
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" class="w-full bg-pink-600 hover:bg-pink-700 text-white font-bold py-3 px-4 rounded-xl shadow transition">
                        Simpan Transaksi
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
