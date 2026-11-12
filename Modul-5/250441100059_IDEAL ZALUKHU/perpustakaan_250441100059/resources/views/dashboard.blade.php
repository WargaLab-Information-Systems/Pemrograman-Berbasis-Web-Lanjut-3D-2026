
<x-app-layout>
    <div class="p-6">

        {{-- Judul Dashboard --}}
        <h1 class="text-2xl font-bold text-gray-800">
            Dashboard Siswa
        </h1>

        {{-- Informasi Pengguna --}}
        <p class="mt-2 text-gray-600">
            Selamat datang, {{ Auth::user()->name }}.
        </p>

        <p class="mt-1 text-gray-600">
            Selamat datang di Sistem Perpustakaan Digital.
        </p>

        {{-- Menu Utama --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">

            {{-- Katalog Buku --}}
            <div class="bg-white p-6 rounded-lg shadow-sm border">
                <h2 class="text-lg font-semibold text-gray-800">
                    Katalog Buku
                </h2>

                <p class="text-gray-600 mt-2">
                    Lihat koleksi buku yang tersedia di perpustakaan.
                </p>

                <a href="{{ route('siswa.buku.index') }}"
                   class="inline-block mt-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Lihat Katalog
                </a>
            </div>

            {{-- Peminjaman --}}
            <div class="bg-white p-6 rounded-lg shadow-sm border">
                <h2 class="text-lg font-semibold text-gray-800">
                    Peminjaman Buku
                </h2>

                <p class="text-gray-600 mt-2">
                    Lihat informasi dan status peminjaman buku.
                </p>

                <a href="{{ route('siswa.peminjaman.index') }}"
                   class="inline-block mt-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Lihat Peminjaman
                </a>
            </div>

        </div>

    </div>
</x-app-layout>
