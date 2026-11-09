<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-6">
        <h3 class="text-lg font-bold text-slate-900 tracking-tight">Masuk ke Akun</h3>
        <p class="text-xs text-slate-500 mt-1">Masukkan email dan kata sandi Anda.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                Email
            </label>
            <input 
                id="email" 
                type="email" 
                name="email" 
                value="{{ old('email') }}" 
                required 
                autofocus 
                autocomplete="username"
                class="w-full border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                Kata Sandi
            </label>
            <input 
                id="password" 
                type="password" 
                name="password" 
                required 
                autocomplete="current-password"
                class="w-full border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button 
                type="submit" 
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm py-2.5 px-4 rounded-lg shadow-sm transition active:scale-[0.99]"
            >
                Masuk
            </button>
        </div>

        <!-- Tautan Registrasi / Buat Akun Baru -->
        @if (Route::has('register'))
        <div class="pt-3 text-center border-t border-slate-100">
            <p class="text-xs text-slate-500">
                Belum punya akun? 
                <a href="{{ route('register') }}" class="text-blue-600 hover:underline font-semibold ms-1">
                    Daftar Akun
                </a>
            </p>
        </div>
        @endif
    </form>
</x-guest-layout>