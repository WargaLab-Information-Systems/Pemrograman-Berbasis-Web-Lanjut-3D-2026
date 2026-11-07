<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Informasi Pribadi
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Perbarui nama dan alamat email akun Anda.
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" value="Nama" />
            <x-text-input id="name" name="name" type="text" class="student-profile-input mt-1 block w-full" x-bind:readonly="!editing" x-bind:class="!editing ? 'bg-gray-50' : ''" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" name="email" type="email" class="student-profile-input mt-1 block w-full" x-bind:readonly="!editing" x-bind:class="!editing ? 'bg-gray-50' : ''" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

        </div>

        <div class="flex items-center gap-4">
            <button type="button" x-show="!editing" x-on:click="editing = true" class="admin-back-button student-profile-edit-button">
                <svg class="student-profile-edit-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M12 20h9" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L8 18l-4 1 1-4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <span>Edit Profil</span>
            </button>
            <button type="submit" x-show="editing" x-cloak class="admin-back-button student-profile-edit-button">Simpan Perubahan</button>
            <button type="button" x-show="editing" x-on:click="editing = false" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700">Batal</button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >Informasi pribadi berhasil diubah.</p>
            @endif
        </div>
    </form>
</section>
