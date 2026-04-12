<div class="max-w-md mx-auto" x-data="{ searchOpen: false }">
    <div class="bg-white rounded-xl shadow-lg border border-pink-100">
        <!-- Header -->
        <div class="bg-gradient-to-r from-pink-500 to-rose-400 p-4 rounded-t-xl text-white flex justify-between items-center shadow-inner">
            <h2 class="font-bold text-lg">🛒 Keranjang Kasir</h2>
            <span class="bg-white/20 px-2 py-1 rounded text-xs font-bold backdrop-blur-sm">{{ count($cart) }} Items</span>
        </div>

        <!-- Search Product with Alpine Dropdown -->
        <div class="p-3 bg-gray-50 border-b border-gray-100 relative">
            <input type="text" 
                   wire:model.live.debounce.300ms="search" 
                   @focus="searchOpen = true" 
                   @click="searchOpen = true"
                   placeholder="🔍 Cari nama barang / kode..." 
                   class="w-full rounded-xl border-pink-200 focus:ring-pink-500 focus:border-pink-500 text-sm px-4 py-3 shadow-inner bg-white">
            
            <!-- Dropdown -->
            <div x-cloak x-show="searchOpen" @click.away="searchOpen = false" x-transition class="absolute z-50 left-0 right-0 mt-2 mx-3 bg-white border border-pink-100 shadow-xl rounded-xl max-h-80 overflow-y-auto">
                <div class="p-2 space-y-2">
                    <div class="flex justify-between items-center px-2 pt-1 sticky top-0 bg-white">
                        <span class="text-[10px] font-bold text-gray-400">HASIL PENCARIAN</span>
                        <button type="button" @click="searchOpen = false" class="text-[10px] text-pink-500 font-bold bg-pink-50 px-3 py-1 rounded hover:bg-pink-100">Tutup</button>
                    </div>
                    @foreach($products as $prod)
                        <div class="border-b border-gray-50 pb-2 mb-2 last:border-0 last:mb-0 last:pb-0">
                            <div class="px-2 mb-1">
                                <h3 class="font-bold text-gray-800 text-xs">{{ $prod->nama_produk }}</h3>
                            </div>
                            <div class="space-y-1">
                            @foreach($prod->items as $item)
                                @if($item->stok_akhir > 0)
                                <div class="bg-gray-50 rounded-lg p-2 mx-1 flex justify-between items-center border border-gray-100 hover:border-pink-200 hover:bg-pink-50/50 transition">
                                    <div>
                                        <p class="text-[10px] font-bold text-gray-700">
                                            {{ $item->variantOption1 ? $item->variantOption1->value : 'Standard' }}
                                            {{ $item->variantOption2 ? '/ '.$item->variantOption2->value : '' }}
                                        </p>
                                        <p class="text-[9px] text-gray-500">Stok: <strong class="text-green-600">{{ $item->stok_akhir }}</strong> | Ecer: Rp{{ number_format($item->harga_jual,0,',','.') }}</p>
                                    </div>
                                    <!-- We hide dropdown on click so user can search again smoothly -->
                                    <button wire:click="addToCart({{ $item->id }})" @click.stop="searchOpen = false; $wire.set('search', '')" class="bg-white border border-pink-200 text-pink-600 hover:bg-pink-500 hover:text-white px-3 py-1 rounded-lg text-xs font-bold shadow-sm transition active:scale-95">+</button>
                                </div>
                                @endif
                            @endforeach
                            </div>
                        </div>
                    @endforeach
                    @if($products->isEmpty())
                        <div class="text-center text-gray-400 py-4 text-xs">Produk tidak terdaftar atau habis stok.</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Bagian Item Keranjang -->
        <div class="p-3 max-h-[40vh] overflow-y-auto scrollbar-hide space-y-3 bg-gray-50">
            @forelse($cart as $i => $c)
            <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-100 relative">
                <button wire:click="removeCart({{ $i }})" class="absolute -right-2 -top-2 bg-red-100 text-red-600 rounded-full w-6 h-6 flex items-center justify-center font-bold text-xs hover:bg-red-500 hover:text-white transition">&times;</button>
                
                <div class="flex justify-between items-start mb-2">
                    <div class="pr-4">
                        <h4 class="text-sm font-bold text-gray-800 leading-tight">{{ $c['nama'] }}</h4>
                        <p class="text-[10px] font-semibold text-gray-500 bg-gray-100 px-1 inline-block rounded">{{ $c['varian'] }}</p>
                    </div>
                </div>
                
                <div class="flex gap-2 text-xs mt-2 bg-pink-50/50 p-2 rounded border border-pink-50">
                    <div class="flex-1 flex flex-col">
                        <span class="text-[9px] text-gray-400 mb-0.5">Edit Hrg/Pcs</span>
                        <input type="number" wire:model.live.debounce.500ms="cart.{{$i}}.harga_aktif" class="w-full rounded text-[10px] px-1.5 py-1 border-gray-300 focus:border-pink-500 focus:ring-0">
                    </div>
                    <div class="flex-1 flex flex-col">
                        <span class="text-[9px] text-gray-400 mb-0.5">Diskon/Pcs</span>
                        <input type="number" wire:model.live.debounce.500ms="cart.{{$i}}.diskon_item" placeholder="Rp" class="w-full rounded text-[10px] px-1.5 py-1 border-gray-300 focus:border-pink-500 text-blue-600 focus:ring-0">
                    </div>
                    
                    <div class="flex items-end pb-0.5">
                        <div class="flex items-center gap-1 bg-white border border-gray-200 rounded p-0.5">
                            <button wire:click="updateQty({{ $i }}, 'minus')" class="w-6 h-6 flex items-center justify-center bg-gray-100 hover:bg-gray-200 rounded text-gray-600 font-bold">-</button>
                            <span class="w-5 text-center text-xs font-bold text-gray-800">{{ $c['qty'] }}</span>
                            <button wire:click="updateQty({{ $i }}, 'plus')" class="w-6 h-6 flex items-center justify-center bg-pink-100 hover:bg-pink-200 rounded text-pink-600 font-bold">+</button>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center text-[10px] mt-2">
                   <div class="space-x-1">
                       <button wire:click="setHargaRetail({{ $i }})" class="bg-white border border-pink-200 px-1.5 py-0.5 rounded text-pink-600 hover:bg-pink-50 {{ $c['harga_aktif'] == $c['harga_jual'] ? 'ring-1 ring-pink-500 bg-pink-50' : '' }}">Ecer</button>
                       <button wire:click="setHargaGrosir({{ $i }})" class="bg-white border border-blue-200 px-1.5 py-0.5 rounded text-blue-600 hover:bg-blue-50 {{ $c['harga_aktif'] == $c['harga_grosir'] ? 'ring-1 ring-blue-500 bg-blue-50' : '' }}">Grosir</button>
                   </div>
                   <p class="font-bold text-gray-800 text-[11px]">Rp{{ number_format(($c['harga_aktif'] - (int)$c['diskon_item']) * $c['qty'], 0, ',', '.') }}</p>
                </div>
            </div>
            @empty
            <div class="flex flex-col items-center justify-center py-8 opacity-50">
                <div class="text-4xl mb-2">📦</div>
                <p class="text-xs font-medium text-gray-500">Keranjang masih kosong</p>
            </div>
            @endforelse
        </div>

        @if(session()->has('error'))
            <div class="bg-red-50 text-red-500 text-[10px] p-2 text-center border-y border-red-100 font-medium">{{ session('error') }}</div>
        @endif
        @error('cart')
            <div class="bg-red-50 text-red-500 text-[10px] p-2 text-center border-y border-red-100 font-medium">{{ $message }}</div>
        @enderror

        <!-- Customer & Biaya -->
        <div class="p-3 border-t border-gray-100 bg-white space-y-3">
            
            <div class="bg-pink-50/50 p-3 rounded-lg border border-pink-100 space-y-2">
                <div class="flex justify-between items-center">
                    <label class="text-[11px] font-bold text-gray-600">Pelanggan</label>
                    <select wire:change="setCustomer($event.target.value)" class="text-[10px] py-1 pl-2 pr-6 rounded border-pink-200 bg-white focus:ring-pink-500 w-3/5">
                        <option value="">Pelanggan Baru (Umum)</option>
                        @foreach($customers as $cust)
                        <option value="{{ $cust->id }}">{{ $cust->nama_customer }}</option>
                        @endforeach
                    </select>
                </div>
                
                @if(!$customer_id)
                <div class="grid grid-cols-2 gap-2">
                    <input type="text" wire:model="customer_name" placeholder="Nama (Opsional)" class="text-[10px] rounded border-gray-200 px-2 py-1.5 focus:border-pink-500">
                    <input type="text" wire:model="customer_wa" placeholder="No WhatsApp" class="text-[10px] rounded border-gray-200 px-2 py-1.5 focus:border-pink-500">
                    <input type="text" wire:model="customer_alamat" placeholder="Alamat Singkat" class="text-[10px] rounded border-gray-200 px-2 py-1.5 focus:border-pink-500 col-span-2">
                </div>
                @else
                <div class="text-[10px] text-gray-500 italic">
                    Memakai data pelanggan tersimpan. (WA: {{ $customer_wa ?? '-' }})
                </div>
                @endif
            </div>

            <!-- Discount & Additional Info -->
            <div class="grid grid-cols-2 gap-3">
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold text-gray-500">Diskon Global (Rp)</label>
                    <input type="number" wire:model.live.debounce.500ms="global_discount" placeholder="0" class="text-[11px] rounded w-full border-gray-200 px-2 py-1.5 focus:border-pink-500">
                </div>
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold text-gray-500">Pembayaran</label>
                    <select wire:model="payment_method" class="text-[11px] rounded w-full border-gray-200 px-2 py-1.5 focus:border-pink-500">
                        <option value="Cash">Cash / Tunai</option>
                        <option value="Transfer BCA">Transfer BCA</option>
                        <option value="Transfer BRI">Transfer BRI</option>
                        <option value="Transfer Mandiri">Transfer Mandiri</option>
                    </select>
                </div>
            </div>

            <!-- Ongkir & Packing Fix -->
            <div class="grid grid-cols-2 gap-3 pt-2 border-t border-gray-50 border-dashed">
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold text-gray-500">Ongkir</label>
                    <select wire:model.live="status_ongkir" class="text-[10px] w-full rounded border-gray-200 px-2 py-1 bg-gray-50 focus:border-pink-500">
                        <option value="Customer">Dibayar Pembeli</option>
                        <option value="Admin">Ditanggung Toko</option>
                    </select>
                    <input type="number" wire:model.live.debounce.500ms="biaya_ongkir" placeholder="Nominal Rp" class="text-[10px] rounded w-full border-gray-200 px-2 py-1 focus:border-pink-500">
                </div>
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold text-gray-500">Packing</label>
                    <select wire:model.live="status_packing" class="text-[10px] w-full rounded border-gray-200 px-2 py-1 bg-gray-50 focus:border-pink-500">
                        <option value="Customer">Dibayar Pembeli</option>
                        <option value="Admin">Ditanggung Toko</option>
                    </select>
                    <input type="number" wire:model.live.debounce.500ms="biaya_packing" placeholder="Nominal Rp" class="text-[10px] rounded w-full border-gray-200 px-2 py-1 focus:border-pink-500">
                </div>
            </div>
        </div>

        <!-- Total & Action -->
        <div class="bg-gray-800 p-4 rounded-b-xl text-white">
            <div class="flex justify-between text-xs text-gray-300 mb-1">
                <span>Total Bruto</span>
                <span class="font-mono">Rp{{ number_format($this->subtotal, 0, ',', '.') }}</span>
            </div>
            @if($global_discount > 0)
            <div class="flex justify-between text-xs text-orange-400 mb-1">
                <span>Diskon Global</span>
                <span class="font-mono">- Rp{{ number_format($global_discount, 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="flex justify-between items-center mt-3 pt-3 border-t border-gray-700">
                <span class="text-xs font-bold text-gray-400">TOTAL NETTO</span>
                <span class="text-xl font-bold text-pink-400 leading-none font-mono">Rp{{ number_format($this->totalNetto, 0, ',', '.') }}</span>
            </div>
            
            <button wire:click="processCheckout" class="w-full mt-4 bg-gradient-to-r from-pink-500 to-rose-400 hover:from-pink-600 hover:to-rose-500 py-3.5 rounded-xl font-bold text-sm shadow-sm transition active:scale-95 text-center">
                Proses Transaksi & Cetak
            </button>
        </div>
    </div>
</div>
