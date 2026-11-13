<x-guest-layout>
    <!-- Title -->
    <div class="mb-8 text-center">
        <h2 class="text-2xl font-bold text-gray-800">Selamat Datang 👋</h2>
        <p class="text-sm text-gray-500 mt-1">Masuk ke akun Anda untuk melanjutkan</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">
                Alamat Email
            </label>
            <div class="relative">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                    </svg>
                </span>
                <input id="email" type="email" name="email"
                       value="{{ old('email') }}"
                       required autofocus autocomplete="username"
                       class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm
                              focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                              transition-all duration-200 bg-gray-50 focus:bg-white
                              @error('email') border-red-300 @enderror"
                       placeholder="nama@email.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-sm font-semibold text-gray-700">
                    Password
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                       class="text-xs text-indigo-600 hover:text-indigo-800 font-medium transition-colors">
                        Lupa password?
                    </a>
                @endif
            </div>
            <div class="relative">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </span>
                <input id="password" type="password" name="password"
                       required autocomplete="current-password"
                       class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm
                              focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                              transition-all duration-200 bg-gray-50 focus:bg-white
                              @error('password') border-red-300 @enderror"
                       placeholder="••••••••">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input id="remember_me" type="checkbox" name="remember"
                   class="w-4 h-4 rounded border-gray-300 text-indigo-600
                          focus:ring-indigo-500 focus:ring-offset-0 cursor-pointer">
            <label for="remember_me" class="ml-2 text-sm text-gray-600 cursor-pointer select-none">
                Ingat saya
            </label>
        </div>

        <!-- Submit Button -->
        <button type="submit"
                class="w-full py-3 px-4 text-sm font-semibold text-white rounded-xl
                       transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5
                       focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                style="background: linear-gradient(135deg, #4F46E5, #3B82F6);">
            Masuk ke Sistem →
        </button>
    </form>
</x-guest-layout>
