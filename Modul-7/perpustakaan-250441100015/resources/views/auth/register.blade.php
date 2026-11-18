<x-guest-layout>
    <!-- Title -->
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-800">Daftar Akun Baru 🚀</h2>
        <p class="text-sm text-gray-500 mt-1">Lengkapi data di bawah ini untuk membuat akun perpustakaan</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">
                Nama Lengkap
            </label>
            <div class="relative">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </span>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                    autocomplete="name"
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl text-sm text-gray-800
                              focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                              transition-all duration-200 bg-gray-50 focus:bg-white
                              @error('name') border-red-300 @enderror"
                    placeholder="Nama lengkap Anda">
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">
                Alamat Email
            </label>
            <div class="relative">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                    </svg>
                </span>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                    autocomplete="username"
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl text-sm text-gray-800
                              focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                              transition-all duration-200 bg-gray-50 focus:bg-white
                              @error('email') border-red-300 @enderror"
                    placeholder="nama@email.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">
                Password
            </label>
            <div class="relative">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </span>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl text-sm text-gray-800
                              focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                              transition-all duration-200 bg-gray-50 focus:bg-white
                              @error('password') border-red-300 @enderror"
                    placeholder="••••••••">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1">
                Konfirmasi Password
            </label>
            <div class="relative">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </span>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                    autocomplete="new-password"
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl text-sm text-gray-800
                              focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                              transition-all duration-200 bg-gray-50 focus:bg-white
                              @error('password_confirmation') border-red-300 @enderror"
                    placeholder="Ulangi password">
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit"
                class="w-full py-3 px-4 text-sm font-bold text-white rounded-xl shadow-md
                           transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5
                           focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                style="background: linear-gradient(135deg, #4F46E5, #3B82F6);">
                Daftar Sekarang →
            </button>
        </div>

        <!-- Link Login (Kontras Jelas & Hover Gelap) -->
        <div class="text-center pt-2">
            <p class="text-sm text-gray-600 font-medium">
                Sudah punya akun?
                <a href="{{ route('login') }}"
                    class="font-bold text-indigo-600 hover:text-indigo-800 hover:underline transition-colors ml-1">
                    Masuk di sini
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
