<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Siswa
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold">
                        Selamat Datang, {{ auth()->user()->name }}
                    </h3>

                    <p class="mt-2 text-gray-600">
                        Silakan melihat koleksi buku dan memeriksa
                        riwayat peminjaman Anda.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm text-gray-500">
                            Koleksi Buku
                        </p>

                        <h3 class="text-3xl font-bold mt-2">
                            {{ $totalBuku }}
                        </h3>

                        <a href="{{ route('buku.index') }}"
                           class="inline-block mt-4 text-blue-600 hover:text-blue-800">
                            Lihat Katalog Buku
                        </a>
                    </div>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm text-gray-500">Peminjaman Aktif / Diajukan</p>
                        <h3 class="text-3xl font-bold mt-2">{{ $pinjamanAktif }}</h3>
                        <a href="{{ route('peminjaman.status.sendiri') }}"
                           class="inline-block mt-4 text-blue-600 hover:text-blue-800">
                            Lihat Status Peminjaman
                        </a>
                        <span class="text-gray-300 mx-1">|</span>
                        <a href="{{ route('peminjaman.riwayat.sendiri') }}"
                           class="inline-block mt-4 text-blue-600 hover:text-blue-800">
                            Riwayat ({{ $totalRiwayat }})
                        </a>
                    </div>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm text-gray-500">
                            Profil Saya
                        </p>

                        <a href="{{ route('profil.saya') }}"
                           class="inline-block mt-4 text-blue-600 hover:text-blue-800">
                            Kelola Profil
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>