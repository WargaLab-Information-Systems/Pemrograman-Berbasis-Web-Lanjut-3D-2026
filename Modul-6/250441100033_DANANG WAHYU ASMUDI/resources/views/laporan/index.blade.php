<x-app-layout>
    <div class="bg-white p-6 rounded-lg shadow-md max-w-6xl mx-auto space-y-6">
        <div class="flex flex-wrap items-center justify-between border-b pb-4 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Laporan Operasional Perpustakaan</h1>
                <p class="text-gray-600 text-sm mt-1">Laporan rekapitulasi data buku, kategori, dan transaksi peminjaman.</p>
            </div>
            @role('admin')
                <a href="{{ route('admin.dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded text-sm transition">
                    &larr; Dashboard Admin
                </a>
            @else
                <a href="{{ route('petugas.dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded text-sm transition">
                    &larr; Dashboard Petugas
                </a>
            @endrole
        </div>

        <!-- Kotak Ringkasan Statistik -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
            <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg text-center">
                <div class="text-xs font-semibold text-blue-700 uppercase">Total Judul Buku</div>
                <div class="text-3xl font-bold text-blue-900 mt-1">{{ $totalBuku }}</div>
            </div>
            <div class="p-4 bg-purple-50 border border-purple-200 rounded-lg text-center">
                <div class="text-xs font-semibold text-purple-700 uppercase">Total Peminjaman</div>
                <div class="text-3xl font-bold text-purple-900 mt-1">{{ $totalPeminjaman }}</div>
            </div>
            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg text-center">
                <div class="text-xs font-semibold text-yellow-700 uppercase">Sedang Dipinjam</div>
                <div class="text-3xl font-bold text-yellow-900 mt-1">{{ $totalDipinjam }}</div>
            </div>
            <div class="p-4 bg-green-50 border border-green-200 rounded-lg text-center">
                <div class="text-xs font-semibold text-green-700 uppercase">Sudah Dikembalikan</div>
                <div class="text-3xl font-bold text-green-900 mt-1">{{ $totalDikembalikan }}</div>
            </div>
        </div>

        <!-- Tabel 1: Rekap Buku per Kategori -->
        <div>
            <h2 class="text-lg font-bold text-gray-800 mb-3">1. Rekapitulasi Buku per Kategori</h2>
            <div class="overflow-x-auto border rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2.5 text-left font-medium text-gray-600">No</th>
                            <th class="px-4 py-2.5 text-left font-medium text-gray-600">Nama Kategori</th>
                            <th class="px-4 py-2.5 text-center font-medium text-gray-600">Jumlah Koleksi Buku</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($rekapKategori as $index => $kat)
                            <tr>
                                <td class="px-4 py-2.5 text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-4 py-2.5 font-medium text-gray-900">{{ $kat->nama }}</td>
                                <td class="px-4 py-2.5 text-center font-bold text-blue-600">{{ $kat->bukus_count }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-4 text-center text-gray-500">Tidak ada kategori.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tabel 2: Rekap Peminjaman per Buku -->
        <div>
            <h2 class="text-lg font-bold text-gray-800 mb-3">2. Rekapitulasi Peminjaman per Buku</h2>
            <div class="overflow-x-auto border rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2.5 text-left font-medium text-gray-600">No</th>
                            <th class="px-4 py-2.5 text-left font-medium text-gray-600">Judul Buku</th>
                            <th class="px-4 py-2.5 text-left font-medium text-gray-600">Penulis</th>
                            <th class="px-4 py-2.5 text-center font-medium text-gray-600">Total Dipinjam (Kali)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($rekapBuku as $index => $b)
                            <tr>
                                <td class="px-4 py-2.5 text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-4 py-2.5 font-medium text-gray-900">{{ $b->judul }}</td>
                                <td class="px-4 py-2.5 text-gray-600">{{ $b->penulis }}</td>
                                <td class="px-4 py-2.5 text-center font-bold text-purple-600">{{ $b->peminjamans_count }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-4 text-center text-gray-500">Tidak ada data buku.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

