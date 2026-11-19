<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Admin
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-2">Selamat Datang, Admin Perpustakaan!</h3>
                <p class="text-gray-600 mb-6">Anda memiliki hak akses penuh untuk mengelola buku, pengguna, peminjaman, dan melihat laporan sistem.</p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-gray-50 border border-gray-200 rounded p-5">
                        <div class="text-sm font-medium text-gray-600">Total Koleksi Buku</div>
                        <div class="text-2xl font-bold text-gray-800 mt-2">{{ $totalBuku }}</div>
                        <a href="{{ route('buku.index') }}" class="inline-block mt-3 text-xs text-gray-700 hover:underline">Kelola Data Buku &rarr;</a>
                    </div>

                    <div class="bg-gray-50 border border-gray-200 rounded p-5">
                        <div class="text-sm font-medium text-gray-600">Total Pengguna Terdaftar</div>
                        <div class="text-2xl font-bold text-gray-800 mt-2">{{ $totalPengguna }}</div>
                        <a href="{{ route('admin.users') }}" class="inline-block mt-3 text-xs text-gray-700 hover:underline">Kelola Pengguna & Role &rarr;</a>
                    </div>

                    <div class="bg-gray-50 border border-gray-200 rounded p-5">
                        <div class="text-sm font-medium text-gray-600">Total Riwayat Peminjaman</div>
                        <div class="text-2xl font-bold text-gray-800 mt-2">{{ $totalPeminjaman }}</div>
                        <a href="{{ route('admin.peminjaman') }}" class="inline-block mt-3 text-xs text-gray-700 hover:underline">Lihat Peminjaman &rarr;</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

