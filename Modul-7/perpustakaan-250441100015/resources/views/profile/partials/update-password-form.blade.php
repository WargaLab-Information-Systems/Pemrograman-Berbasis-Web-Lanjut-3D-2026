<section>
    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-sm font-semibold text-gray-700 mb-1.5">
                Kata Sandi Saat Ini
            </label>
            <input id="update_password_current_password" name="current_password" type="password"
                   autocomplete="current-password"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm text-gray-800
                          focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                          transition-all duration-200 bg-gray-50 focus:bg-white shadow-sm"
                   placeholder="••••••••">
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1.5" />
        </div>

        <div>
            <label for="update_password_password" class="block text-sm font-semibold text-gray-700 mb-1.5">
                Kata Sandi Baru
            </label>
            <input id="update_password_password" name="password" type="password"
                   autocomplete="new-password"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm text-gray-800
                          focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                          transition-all duration-200 bg-gray-50 focus:bg-white shadow-sm"
                   placeholder="Minimal 8 karakter">
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1.5" />
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1.5">
                Konfirmasi Kata Sandi Baru
            </label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                   autocomplete="new-password"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm text-gray-800
                          focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                          transition-all duration-200 bg-gray-50 focus:bg-white shadow-sm"
                   placeholder="Ulangi kata sandi baru">
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1.5" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit"
                    class="py-2.5 px-6 text-sm font-bold text-white rounded-xl shadow-md
                           transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5
                           focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    style="background: linear-gradient(135deg, #4F46E5, #3B82F6);">
                Perbarui Kata Sandi
            </button>

            @if (session('status') === 'password-updated')
                <span x-data="{ show: true }"
                      x-show="show"
                      x-transition
                      x-init="setTimeout(() => show = false, 2500)"
                      class="text-sm font-semibold text-green-600 flex items-center gap-1">
                    ✓ Kata sandi berhasil diperbarui
                </span>
            @endif
        </div>
    </form>
</section>