<div>
    <div class="flex flex-col gap-3 sm:flex-row sm:justify-between sm:items-center mb-4">
        <h2 class="text-lg sm:text-xl font-bold text-pink-700">Daftar Produk</h2>
        <div class="flex gap-2 shrink-0">
            <a href="/products/trashed" class="bg-gray-50 text-gray-600 px-3 py-2 rounded-lg text-xs sm:text-sm font-semibold border border-gray-100 hover:bg-gray-100 transition">
                Terhapus
            </a>
            <a href="/products/create" class="bg-gradient-to-r from-pink-500 to-rose-400 text-white px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-semibold shadow hover:shadow-lg transition">
                + Tambah
            </a>
        </div>
    </div>

    <div class="mb-3">
        <input type="text" wire:model.live.debounce.500ms="search" placeholder="Cari nama, kode, atau owner..." class="w-full rounded-xl border-pink-200 focus:ring-pink-500 focus:border-pink-500 text-sm px-3 py-2.5 sm:px-4 sm:py-3 border shadow-sm">
    </div>

    <div class="flex overflow-x-auto gap-2 pb-2 mb-3 scrollbar-hide -mx-1 px-1">
        <button wire:click="selectOwner('')" class="whitespace-nowrap px-3 py-1.5 rounded-full text-[11px] font-semibold shadow-sm transition {{ $selectedOwner === '' ? 'bg-pink-500 text-white' : 'bg-white text-gray-500 border border-gray-100 hover:bg-pink-50' }}">Semua</button>
        <button wire:click="selectOwner('milik_sendiri')" class="whitespace-nowrap px-3 py-1.5 rounded-full text-[11px] font-semibold shadow-sm transition {{ $selectedOwner === 'milik_sendiri' ? 'bg-pink-500 text-white' : 'bg-white text-gray-500 border border-gray-100 hover:bg-pink-50' }}">Milik Sendiri</button>
        @foreach($owners as $own)
        <button wire:click="selectOwner('{{ $own->id }}')" class="whitespace-nowrap px-3 py-1.5 rounded-full text-[11px] font-semibold shadow-sm transition {{ $selectedOwner == $own->id ? 'bg-pink-500 text-white' : 'bg-yellow-50 text-yellow-800 border border-yellow-100 hover:bg-yellow-100' }}">{{ $own->nama_owner }}</button>
        @endforeach
    </div>

    <div class="space-y-2.5 sm:space-y-3">
        @foreach($products as $prod)
        <article class="group relative bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-pink-100/80 transition overflow-hidden">
            <a href="/products/{{ $prod->id }}" class="absolute inset-0 z-0 rounded-2xl" aria-label="Lihat detail {{ $prod->nama_produk }}"></a>
            <div class="flex gap-3 p-3 sm:p-3.5 relative z-10 pointer-events-none">
                <div class="shrink-0 pointer-events-auto">
                    @if($prod->foto)
                    <button
                        type="button"
                        class="w-[4.5rem] h-[4.5rem] sm:w-16 sm:h-16 rounded-xl border border-gray-100 overflow-hidden bg-gray-50 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-1 shadow-inner"
                        wire:click.stop="openPreview({{ $prod->id }})"
                        aria-label="Preview foto {{ $prod->nama_produk }}"
                    >
                        <img src="{{ asset('storage/' . $prod->foto) }}" alt="" class="w-full h-full object-cover pointer-events-none">
                    </button>
                    @else
                    <div class="w-[4.5rem] h-[4.5rem] sm:w-16 sm:h-16 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border border-gray-100 flex items-center justify-center">
                        <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    @endif
                </div>
                <div class="flex-1 min-w-0 flex flex-col gap-1.5">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0 flex-1">
                            <h3 class="font-semibold text-gray-900 text-sm sm:text-base leading-snug line-clamp-2 pr-1">{{ $prod->nama_produk }}</h3>
                            <p class="text-[11px] mt-0.5 flex items-center gap-1.5 min-w-0">
                                <span class="shrink-0 inline-flex items-center font-mono text-[10px] sm:text-[11px] font-bold tracking-wide text-pink-700 bg-pink-50 border border-pink-100 px-1.5 py-0.5 rounded-md">{{ $prod->kode_produk ?? '—' }}</span>
                                <span class="text-gray-300 shrink-0" aria-hidden="true">·</span>
                                <span class="min-w-0 truncate text-gray-500">
                                    {{ $prod->category->nama_kategori ?? 'Tanpa kategori' }}
                                    @if($prod->bahan)
                                    <span class="text-gray-300 shrink-0 px-0.5" aria-hidden="true">·</span>{{ $prod->bahan }}
                                    @endif
                                </span>
                            </p>
                        </div>
                        @if($prod->owner)
                        <span class="shrink-0 bg-amber-50 text-amber-800 text-[10px] px-2 py-0.5 rounded-md font-bold border border-amber-100/80 max-w-[5.5rem] truncate">{{ $prod->owner->nama_owner }}</span>
                        @else
                        <span class="shrink-0 bg-pink-50 text-pink-600 text-[10px] px-2 py-0.5 rounded-md font-bold border border-pink-100">Sendiri</span>
                        @endif
                    </div>
                    <div class="flex flex-wrap items-center gap-1.5">
                        <span class="text-[10px] text-gray-600 bg-gray-50 border border-gray-100 px-2 py-0.5 rounded-md">
                            Stok <strong class="text-pink-600">{{ $prod->items_sum_stok_akhir ?? 0 }}</strong>
                        </span>
                        @if($prod->gender === 'male')
                        <span class="text-[10px] font-bold bg-sky-100 text-sky-800 border border-sky-200/80 px-2 py-0.5 rounded-md" title="Laki-laki">L</span>
                        @elseif($prod->gender === 'female')
                        <span class="text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-200/80 px-2 py-0.5 rounded-md" title="Perempuan">P</span>
                        @else
                        <span class="text-[10px] font-bold bg-violet-100 text-violet-800 border border-violet-200/80 px-2 py-0.5 rounded-md" title="Unisex">U</span>
                        @endif
                        @php
                            $minJual = $prod->items_min_harga_jual;
                            $maxJual = $prod->items_max_harga_jual;
                        @endphp
                        @if($minJual !== null && $maxJual !== null)
                        <span class="text-[10px] font-semibold text-emerald-800 bg-emerald-50 border border-emerald-100 px-2 py-0.5 rounded-md whitespace-nowrap" title="Harga jual (ecer)">
                            @if((float) $minJual === (float) $maxJual)
                            Rp {{ number_format((float) $minJual, 0, ',', '.') }}
                            @else
                            Rp {{ number_format((float) $minJual, 0, ',', '.') }}–{{ number_format((float) $maxJual, 0, ',', '.') }}
                            @endif
                        </span>
                        @else
                        <span class="text-[10px] text-gray-400 bg-gray-50 border border-gray-100 px-2 py-0.5 rounded-md">Harga —</span>
                        @endif
                    </div>
                </div>
            </div>
        </article>
        @endforeach

        @if($products->isEmpty())
        <div class="text-center text-gray-400 py-10 px-4 bg-white rounded-2xl shadow-sm border border-pink-50">
            <svg class="w-12 h-12 mx-auto text-pink-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
            <p class="text-sm">Belum ada produk atau data tidak ditemukan.</p>
        </div>
        @else
        <div class="mt-4">
            {{ $products->links(data: ['scrollTo' => false]) }}
        </div>
        @endif
    </div>

    @if($previewUrl)
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
