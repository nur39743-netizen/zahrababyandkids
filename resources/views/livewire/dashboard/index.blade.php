<div class="max-w-md mx-auto space-y-6 pb-6 mt-2">

    <!-- Hero / Welcome Area -->
    <div style="background: linear-gradient(135deg, #ec4899, #f43f5e);" class="p-6 rounded-3xl text-white shadow-xl relative overflow-hidden rounded-2xl">
        <!-- Abstract Shapes -->

        <div class="relative z-10 hidden lg:block"></div>
        <div class="relative z-10">
            <h2 class="text-2xl font-bold font-serif mb-1 tracking-wide text-white">Halo, Admin 👋</h2>
            <p class="text-xs text-pink-100 opacity-20 mb-5 font-medium tracking-wide">Ringkasan performa toko hari ini.</p>

            <div class="bg-white/20 backdrop-blur-md rounded-2xl p-5 border border-white/30 shadow-[0_4px_30px_rgba(0,0,0,0.1)]">
                <p class="text-[10px] text-pink-50 font-bold mb-1 tracking-widest uppercase flex items-center gap-1">
                    Pendapatan Hari Ini
                </p>
                <h3 class="text-3xl font-bold font-mono tracking-tight text-white">Rp{{ number_format($omsetHariIni, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div>
        <div class="flex items-center justify-between mb-3 px-1">
            <h3 class="text-[11px] font-bold text-gray-500 uppercase tracking-widest">Akses Cepat</h3>
        </div>
        <div class="grid grid-cols-4 gap-3">
            <a href="/pos" class="bg-white p-3 rounded-[1.25rem] shadow-[0_2px_10px_rgba(0,0,0,0.03)] flex flex-col items-center justify-start gap-2 hover:bg-pink-50 transition transform hover:-translate-y-1 group rounded-2xl">
                <div class="w-12 h-12 rounded-full bg-pink-50 text-pink-600 flex items-center justify-center group-hover:bg-pink-100 transition shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <span class="text-[9px] font-bold text-gray-600 text-center uppercase tracking-wide">Kasir</span>
            </a>

            <a href="/transactions" class="bg-white p-3 rounded-[1.25rem] shadow-[0_2px_10px_rgba(0,0,0,0.03)] flex flex-col items-center justify-start gap-2 hover:bg-blue-50 transition transform hover:-translate-y-1 group rounded-2xl">
                <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-100 transition shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <span class="text-[9px] font-bold text-gray-600 text-center uppercase tracking-wide">Riwayat</span>
            </a>

            <a href="/variants" class="bg-white p-3 rounded-[1.25rem] shadow-[0_2px_10px_rgba(0,0,0,0.03)] flex flex-col items-center justify-start gap-2 hover:bg-purple-50 transition transform hover:-translate-y-1 group rounded-2xl">
                <div class="w-12 h-12 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center group-hover:bg-purple-100 transition shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143z"></path>
                    </svg>
                </div>
                <span class="text-[9px] font-bold text-gray-600 text-center uppercase tracking-wide">Varian</span>
            </a>

            <!-- Laporan (Fase 3 final) -->
            <a href="#" class="bg-gray-50 p-3 rounded-[1.25rem] flex flex-col items-center justify-start gap-2 opacity-50 cursor-not-allowed">
                <div class="w-12 h-12 rounded-full bg-gray-200 text-gray-400 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <span class="text-[9px] font-bold text-gray-400 text-center uppercase tracking-wide">Laporan</span>
            </a>
        </div>
    </div>

    <!-- Monthly Filter Header -->
    <div class="flex items-center justify-between border-t border-pink-100/50 pt-5 px-1 mt-2">
        <h3 class="text-[11px] font-bold text-gray-500 uppercase tracking-widest flex items-center gap-1.5">
            <svg class="w-4 h-4 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            Performa Rekap
        </h3>
        <div>
            <input type="month" wire:model.live="filterMonth" class="text-[10px] bg-white border border-gray-200 text-gray-600 font-bold rounded-lg px-2 py-1.5 shadow-sm focus:border-pink-500 focus:ring-1 focus:ring-pink-500 outline-none transition">
        </div>
    </div>

    <!-- Monthly Statistics -->
    <div class="grid grid-cols-2 gap-3">
        <div class="bg-white p-4 rounded-2xl shadow-[0_2px_10px_rgba(0,0,0,0.03)] border border-gray-100/50 relative overflow-hidden group hover:border-pink-200 transition flex flex-col justify-between h-28">
            <div class="flex items-center justify-between mb-2">
                <div class="w-8 h-8 rounded-full bg-pink-50 text-pink-500 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mb-0.5">Total Omset</p>
                <p class="text-sm font-bold text-gray-800 tracking-tight leading-none bg-clip-text text-transparent bg-gradient-to-r from-gray-800 to-gray-600">Rp{{ number_format($omsetBulanIni, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl shadow-[0_2px_10px_rgba(0,0,0,0.03)] border border-gray-100/50 relative overflow-hidden group hover:border-green-200 transition flex flex-col justify-between h-28">
            <div class="flex items-center justify-between mb-2">
                <div class="w-8 h-8 rounded-full bg-green-50 text-green-500 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mb-0.5">Est Laba Bersih</p>
                <p class="text-sm font-bold tracking-tight leading-none bg-clip-text text-transparent bg-gradient-to-r from-green-600 to-emerald-500">Rp{{ number_format($labaBersihBulanIni, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl shadow-[0_2px_10px_rgba(0,0,0,0.03)] border border-gray-100/50 relative overflow-hidden group hover:border-blue-200 transition flex flex-col justify-between h-28">
            <div class="flex items-center justify-between mb-2">
                <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mb-0.5">Transaksi Belanja</p>
                <p class="text-xl font-bold text-gray-800 tracking-tight leading-none">{{ $totalTransaksiBulanIni }} <span class="text-[10px] font-bold text-gray-400">Trx</span></p>
            </div>
        </div>

        <a href="/products" class="bg-white p-4 rounded-2xl shadow-[0_2px_10px_rgba(0,0,0,0.03)] border border-red-100/50 relative overflow-hidden group hover:bg-red-50 transition flex flex-col justify-between h-28 cursor-pointer">
            <div class="flex items-center justify-between mb-2">
                <div class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center group-hover:scale-110 transition shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-[9px] text-red-500 font-bold uppercase tracking-widest mb-0.5">Stok Menipis</p>
                <p class="text-xl font-bold text-red-600 tracking-tight leading-none">{{ $barangMenipis }} <span class="text-[10px] font-bold text-red-400">Item &le; 5</span></p>
            </div>
        </a>
    </div>
</div>