<section class="space-y-4">
    <p class="text-sm text-gray-500">
        Tindakan ini permanen. Setelah akun Anda dihapus, semua data dan riwayat tidak dapat dipulihkan kembali.
    </p>

    <button type="button"
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="py-2.5 px-6 text-sm font-bold text-white rounded-xl shadow-sm
                   bg-red-600 hover:bg-red-700 transition-all duration-200 hover:shadow-md">
        Hapus Akun Saya
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-bold text-gray-900">
                Apakah Anda yakin ingin menghapus akun?
            </h2>

            <p class="mt-2 text-sm text-gray-600">
                Masukkan kata sandi akun Anda untuk mengonfirmasi penghapusan permanen.
            </p>

            <div class="mt-4">
                <input id="password"
                       name="password"
                       type="password"
                       class="w-full sm:w-3/4 px-4 py-2.5 border border-gray-300 rounded-xl text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-red-500"
                       placeholder="Masukkan kata sandi Anda">
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button"
                        x-on:click="$dispatch('close')"
                        class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                    Batal
                </button>

                <button type="submit"
                        class="px-5 py-2.5 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-xl transition-all">
                    Ya, Hapus Akun
                </button>
            </div>
        </form>
    </x-modal>
</section>