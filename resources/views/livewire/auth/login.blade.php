<div class="min-h-screen flex items-center justify-center p-4" style="background: linear-gradient(135deg, #fdf2f8, #fce7f3);">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-[0_8px_30px_rgba(0,0,0,0.04)] border border-pink-50 p-8">
        
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-gradient-to-tr from-pink-500 to-rose-400 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg shadow-pink-200">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold font-serif text-gray-800">Selamat Datang</h2>
            <p class="text-xs text-gray-500 mt-1">Silakan masuk ke akun Anda</p>
        </div>

        <form wire:submit.prevent="login" class="space-y-5">
            <div>
                <label for="email" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1">Email</label>
                <input type="email" id="email" wire:model="email" class="w-full bg-gray-50 border border-gray-200 text-gray-800 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent transition shadow-sm placeholder-gray-400" placeholder="admin@admin.com" required autofocus>
                @error('email') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1">Password</label>
                <input type="password" id="password" wire:model="password" class="w-full bg-gray-50 border border-gray-200 text-gray-800 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent transition shadow-sm placeholder-gray-400" placeholder="••••••••" required>
                @error('password') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center">
                <input id="remember" type="checkbox" wire:model="remember" class="w-4 h-4 text-pink-600 border-gray-300 rounded focus:ring-pink-500">
                <label for="remember" class="ml-2 block text-xs text-gray-600">Ingat Saya</label>
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-pink-600 to-rose-500 text-white font-bold rounded-xl py-3 shadow-lg shadow-pink-200 hover:from-pink-700 hover:to-rose-600 transition transform hover:-translate-y-0.5 active:translate-y-0 relative">
                <span wire:loading.remove wire:target="login">Masuk</span>
                <span wire:loading wire:target="login" class="flex items-center justify-center gap-2">
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Memproses...
                </span>
            </button>
        </form>

    </div>
</div>
