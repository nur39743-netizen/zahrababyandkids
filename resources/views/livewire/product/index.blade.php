<div>
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-pink-700">Daftar Produk</h2>
        <div class="flex gap-2">
            <a href="/products/trashed" class="bg-gray-50 text-gray-600 px-3 py-2 rounded-lg text-sm font-semibold border border-gray-100 hover:bg-gray-100 transition">
                Terhapus
            </a>
            <a href="/products/create" class="bg-gradient-to-r from-pink-500 to-rose-400 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow hover:shadow-lg transition">
                + Tambah
            </a>
        </div>
    </div>

    <div class="mb-4">
        <input type="text" wire:model.live.debounce.500ms="search" placeholder="Cari nama, kode, atau owner..." class="w-full rounded-xl border-pink-200 focus:ring-pink-500 focus:border-pink-500 text-sm px-4 py-3 border shadow-sm">
    </div>

    <!-- Scrollable Filter Chips -->
    <div class="flex overflow-x-auto gap-2 pb-2 mb-4 scrollbar-hide">
        <button wire:click="selectOwner('')" class="whitespace-nowrap px-4 py-2 rounded-full text-xs font-semibold shadow-sm transition {{ $selectedOwner === '' ? 'bg-pink-500 text-white' : 'bg-white text-gray-500 border border-gray-100 hover:bg-pink-50' }}">Semua</button>
        <button wire:click="selectOwner('milik_sendiri')" class="whitespace-nowrap px-4 py-2 rounded-full text-xs font-semibold shadow-sm transition {{ $selectedOwner === 'milik_sendiri' ? 'bg-pink-500 text-white' : 'bg-white text-gray-500 border border-gray-100 hover:bg-pink-50' }}">Milik Sendiri</button>
        @foreach($owners as $own)
        <button wire:click="selectOwner('{{ $own->id }}')" class="whitespace-nowrap px-4 py-2 rounded-full text-xs font-semibold shadow-sm transition {{ $selectedOwner == $own->id ? 'bg-pink-500 text-white' : 'bg-yellow-50 text-yellow-700 border border-yellow-100 hover:bg-yellow-100' }}">{{ $own->nama_owner }}</button>
        @endforeach
    </div>

    <div class="space-y-4">
        @foreach($products as $prod)
        <div class="block bg-white p-4 rounded-xl shadow-sm border border-pink-50 relative hover:shadow-md transition">
            <a href="/products/{{ $prod->id }}" class="absolute inset-0 z-0 rounded-xl" aria-label="Lihat detail {{ $prod->nama_produk }}"></a>
            <div class="flex items-start gap-4 relative z-10 pointer-events-none">
                <div class="flex-shrink-0 pointer-events-auto">
                    @if($prod->foto)
                    <button
                        type="button"
                        class="w-16 h-16 rounded-lg border border-gray-200 overflow-hidden focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2"
                        wire:click.stop="openPreview({{ $prod->id }})"
                        aria-label="Preview foto {{ $prod->nama_produk }}"
                    >
                        <img src="{{ asset('storage/' . $prod->foto) }}" alt="" class="w-full h-full object-cover pointer-events-none">
                    </button>
                    @else
                    <div class="w-16 h-16 bg-gray-100 rounded-lg border border-gray-200 flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    @endif
                </div>
                <div class="flex-1">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h3 class="font-bold text-gray-800 text-lg leading-tight">{{ $prod->nama_produk }}</h3>
                            <p class="text-xs text-gray-400 mt-1">Kode: {{ $prod->kode_produk ?? '-' }}
                                @if($prod->category) • {{ $prod->category->nama_kategori }} @endif
                            </p>
                        </div>
                        @if($prod->owner)
                        <span class="bg-yellow-100 text-yellow-700 text-[10px] px-2 py-1 rounded font-bold border border-yellow-200">{{ $prod->owner->nama_owner }}</span>
                        @else
                        <span class="bg-pink-100 text-pink-600 text-[10px] px-2 py-1 rounded font-bold border border-pink-200">Milik Sendiri</span>
                        @endif
                    </div>

                    <div class="mt-3 flex justify-between items-center text-xs">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-gray-500 border border-gray-100 bg-gray-50 px-2 py-1 rounded">
                                Stok: <strong class="text-pink-600">{{ $prod->items_sum_stok_akhir ?? 0 }}</strong>
                            </span>
                            <span class="text-gray-500 border border-gray-100 bg-gray-50 px-2 py-1 rounded">
                                {{ $prod->gender === 'male' ? 'Male' : ($prod->gender === 'female' ? 'Female' : 'Unisex') }}
                            </span>
                            @if($prod->bahan)
                            <span class="text-gray-500 border border-gray-100 bg-gray-50 px-2 py-1 rounded">
                                Bahan: <strong>{{ $prod->bahan }}</strong>
                            </span>
                            @endif
                            @if($prod->supplier)
                            <span class="text-gray-500 border border-gray-100 bg-gray-50 px-2 py-1 rounded">
                                Supplier: <strong>{{ $prod->supplier->name }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        @if($products->isEmpty())
        <div class="text-center text-gray-400 py-10 bg-white rounded-xl shadow-sm border border-pink-50">
            <svg class="w-12 h-12 mx-auto text-pink-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
            <p>Belum ada produk atau data tidak ditemukan.</p>
        </div>
        @else
        <div class="mt-4">
            {{ $products->links(data: ['scrollTo' => false]) }}
        </div>
        @endif
    </div>

    @if($previewUrl)
    <!-- Preview foto produk (klik thumbnail) — Livewire, tanpa Alpine -->
    <div
        class="fixed inset-0 z-[100] flex items-center justify-center p-4"
        role="dialog"
        aria-modal="true"
        aria-label="Preview foto produk"
        wire:click="closePreview"
        wire:key="product-photo-preview"
    >
        <div class="absolute inset-0 bg-black/70" aria-hidden="true"></div>
        <div class="relative max-w-full max-h-[90vh] inline-block" wire:click.stop>
            <button
                type="button"
                class="absolute -top-3 -right-3 z-10 w-9 h-9 rounded-full bg-white text-gray-700 shadow-lg border border-gray-200 flex items-center justify-center text-xl font-light leading-none hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-pink-500"
                wire:click="closePreview"
                aria-label="Tutup preview"
            >&times;</button>
            <img src="{{ $previewUrl }}" alt="{{ $previewAlt }}" class="max-w-full max-h-[85vh] w-auto h-auto object-contain rounded-lg shadow-2xl border border-white/20">
        </div>
    </div>
    @endif
</div>