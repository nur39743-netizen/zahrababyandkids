<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <title>{{ $title ?? config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-pink-50 text-gray-800 antialiased min-h-screen flex flex-col font-sans">

    @auth
    <!-- Header (Optional Topbar) -->
    <header class="bg-white shadow relative z-20">
        <div class="max-w-md mx-auto px-4 py-3 flex justify-between items-center">
            <h1 class="text-xl font-bold font-serif text-pink-600">{{ $title ?? 'Zahrababyandkids' }}</h1>
            
            <form action="/logout" method="POST" class="m-0 p-0">
                @csrf
                <button type="submit" class="w-8 h-8 rounded-full bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-100 transition shadow-sm" title="Logout">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </button>
            </form>
        </div>
    </header>
    @endauth

    <!-- Main Content -->
    <main class="flex-1 w-full max-w-md mx-auto {{ auth()->check() ? 'p-4 mb-20' : '' }}">
        {{ $slot }}
    </main>

    @auth
    <!-- Bottom Action Bar -->
    <div class="fixed bottom-0 left-0 w-full z-50 bg-white border-t border-pink-100 shadow-[0_-2px_10px_rgba(0,0,0,0.05)]">
        <div class="max-w-md mx-auto flex justify-around items-center px-2 py-2">
            <a href="/" class="flex flex-col items-center p-2 {{ request()->is('/') ? 'text-pink-600 bg-pink-200' : 'text-gray-400' }} hover:text-pink-600 transition rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span class="text-[10px] mt-1 font-medium">Home</span>
            </a>

            <a href="/products" class="flex flex-col items-center p-2 {{ request()->is('products*') ? 'text-pink-600 bg-pink-200' : 'text-gray-400' }} hover:text-pink-600 transition rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                <span class="text-[10px] mt-1 font-medium">Produk</span>
            </a>

            <!-- Main Action Button (Center) -->
            <div class="relative -top-5">
                <a href="/pos" class="flex items-center justify-center w-14 h-14 {{ request()->is('pos*') ? 'bg-gradient-to-r from-pink-600 to-rose-500' : 'bg-gradient-to-r from-pink-500 to-rose-400' }} rounded-full text-white shadow-lg shadow-pink-300 ring-4 ring-pink-50 hover:bg-pink-600 transition transform hover:scale-105">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </a>
            </div>

            <a href="/categories" class="flex flex-col items-center p-2 {{ request()->is('categories*') ? 'text-pink-600 bg-pink-200' : 'text-gray-400' }} hover:text-pink-600 transition rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                </svg>
                <span class="text-[10px] mt-1 font-medium">Kategori</span>
            </a>

            <a href="/transactions" class="flex flex-col items-center p-2 {{ request()->is('transactions*') ? 'text-pink-600 bg-pink-200' : 'text-gray-400' }} hover:text-pink-600 transition rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span class="text-[10px] mt-1 font-medium">Transaksi</span>
            </a>
        </div>
    </div>
    @endauth

    @livewireScripts
</body>

</html>