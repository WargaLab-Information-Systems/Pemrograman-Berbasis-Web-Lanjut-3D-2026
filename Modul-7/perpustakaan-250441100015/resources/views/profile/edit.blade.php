<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-extrabold text-2xl text-gray-800 tracking-tight">
                    Pengaturan Profil 👤
                </h2>
                <p class="text-sm text-gray-500 mt-1">Kelola informasi data diri dan kata sandi akun Anda</p>
            </div>
            <span
                class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-indigo-100 text-indigo-700 shadow-sm border border-indigo-200">
                Role: {{ Auth::user()->roles->first()?->name ?? 'User' }}
            </span>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div
                class="p-6 sm:p-8 bg-white rounded-2xl shadow-sm border border-indigo-50 transition-all duration-300 hover:shadow-md">
                <div class="max-w-xl">
                    <div class="mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Informasi Pribadi</h3>
                        <p class="text-sm text-gray-500">Perbarui nama lengkap dan alamat email akun Anda.</p>
                    </div>
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div
                class="p-6 sm:p-8 bg-white rounded-2xl shadow-sm border border-indigo-50 transition-all duration-300 hover:shadow-md">
                <div class="max-w-xl">
                    <div class="mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Perbarui Kata Sandi</h3>
                        <p class="text-sm text-gray-500">Pastikan akun Anda menggunakan kata sandi yang panjang dan
                            aman.</p>
                    </div>
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div
                class="p-6 sm:p-8 bg-white rounded-2xl shadow-sm border border-red-100 transition-all duration-300 hover:shadow-md">
                <div class="max-w-xl">
                    <div class="mb-4">
                        <h3 class="text-lg font-bold text-red-600">Hapus Akun</h3>
                        <p class="text-sm text-gray-500">Setelah akun Anda dihapus, semua data akan terhapus secara
                            permanen.</p>
                    </div>
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
