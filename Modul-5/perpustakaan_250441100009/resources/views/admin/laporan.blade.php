<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Laporan Perpustakaan (Admin)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-2">Ringkasan Statistik Perpustakaan</h3>
                <p class="text-gray-600 mb-6">Laporan rekapitulasi data operasional perpustakaan saat ini.</p>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="border border-gray-200 rounded p-4 bg-gray-50">
                        <div class="text-xs font-semibold text-gray-500 uppercase">Total Judul Buku</div>
                        <div class="text-2xl font-bold text-gray-800 mt-2">{{ $totalBuku }}</div>
                    </div>

                    <div class="border border-gray-200 rounded p-4 bg-gray-50">
                        <div class="text-xs font-semibold text-gray-500 uppercase">Total Anggota/Pengguna</div>
                        <div class="text-2xl font-bold text-gray-800 mt-2">{{ $totalPengguna }}</div>
                    </div>

                    <div class="border border-gray-200 rounded p-4 bg-gray-50">
                        <div class="text-xs font-semibold text-gray-500 uppercase">Buku Sedang Dipinjam</div>
                        <div class="text-2xl font-bold text-gray-800 mt-2">{{ $bukuDipinjam }}</div>
                    </div>

                    <div class="border border-gray-200 rounded p-4 bg-gray-50">
                        <div class="text-xs font-semibold text-gray-500 uppercase">Buku Sudah Kembali</div>
                        <div class="text-2xl font-bold text-gray-800 mt-2">{{ $bukuKembali }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

