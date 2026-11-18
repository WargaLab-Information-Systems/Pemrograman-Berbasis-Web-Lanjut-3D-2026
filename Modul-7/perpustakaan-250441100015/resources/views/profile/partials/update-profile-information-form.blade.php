<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-1.5">
                Nama Lengkap
            </label>
            <input id="name" name="name" type="text"
                   value="{{ old('name', $user->name) }}"
                   required autofocus autocomplete="name"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm text-gray-800
                          focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                          transition-all duration-200 bg-gray-50 focus:bg-white shadow-sm
                          @error('name') border-red-300 @enderror">
            <x-input-error class="mt-1.5" :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">
                Alamat Email
            </label>
            <input id="email" name="email" type="email"
                   value="{{ old('email', $user->email) }}"
                   required autocomplete="username"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm text-gray-800
                          focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                          transition-all duration-200 bg-gray-50 focus:bg-white shadow-sm
                          @error('email') border-red-300 @enderror">
            <x-input-error class="mt-1.5" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 text-sm text-amber-700 bg-amber-50 p-3 rounded-lg border border-amber-200">
                    <p>
                        Email Anda belum diverifikasi.
                        <button form="send-verification" class="font-bold underline text-indigo-600 hover:text-indigo-800 ml-1">
                            Klik di sini untuk mengirim ulang email verifikasi.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-semibold text-sm text-green-600">
                            Tautan verifikasi baru telah dikirim ke alamat email Anda.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit"
                    class="py-2.5 px-6 text-sm font-bold text-white rounded-xl shadow-md
                           transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5
                           focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    style="background: linear-gradient(135deg, #4F46E5, #3B82F6);">
                Simpan Perubahan
            </button>

            @if (session('status') === 'profile-updated')
                <span x-data="{ show: true }"
                      x-show="show"
                      x-transition
                      x-init="setTimeout(() => show = false, 2500)"
                      class="text-sm font-semibold text-green-600 flex items-center gap-1">
                    ✓ Berhasil disimpan
                </span>
            @endif
        </div>
    </form>
</section>