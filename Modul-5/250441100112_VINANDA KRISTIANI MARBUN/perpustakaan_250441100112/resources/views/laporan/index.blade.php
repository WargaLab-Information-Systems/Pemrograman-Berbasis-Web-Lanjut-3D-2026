@extends('layouts.main')

@section('title', 'Laporan Perpustakaan')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Laporan & Statistik Perpustakaan</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400">Rekapitulasi statistik koleksi buku, sirkulasi peminjaman, dan pengembalian.</p>
    </div>

    <!-- Ringkasan Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border-l-4 border-indigo-500">
            <span class="text-xs font-semibold text-gray-500 uppercase">Total Koleksi Buku</span>
            <div class="text-3xl font-bold text-gray-800 dark:text-gray-100 mt-2">{{ $totalBuku }}</div>
            <p class="text-xs text-gray-500 mt-1">Dalam {{ $totalKategori }} kategori</p>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border-l-4 border-amber-500">
            <span class="text-xs font-semibold text-gray-500 uppercase">Menunggu Persetujuan</span>
            <div class="text-3xl font-bold text-amber-600 mt-2">{{ $totalDiajukan }}</div>
            <p class="text-xs text-gray-500 mt-1">Pengajuan peminjaman</p>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border-l-4 border-blue-500">
            <span class="text-xs font-semibold text-gray-500 uppercase">Sedang Dipinjam</span>
            <div class="text-3xl font-bold text-blue-600 mt-2">{{ $totalDipinjam }}</div>
            <p class="text-xs text-gray-500 mt-1">Buku beredar di siswa</p>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border-l-4 border-green-500">
            <span class="text-xs font-semibold text-gray-500 uppercase">Total Pengembalian</span>
            <div class="text-3xl font-bold text-green-600 mt-2">{{ $totalDikembalikan }}</div>
            <p class="text-xs text-gray-500 mt-1">Buku telah dikembalikan</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Rekap Buku per Kategori -->
        <div class="lg:col-span-1 bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-base font-bold text-gray-800 dark:text-gray-100">Buku Berdasarkan Kategori</h3>
            </div>
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($bukuPerKategori as $kat)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-800 dark:text-gray-200">{{ $kat->nama }}</td>
                            <td class="px-4 py-3 text-sm text-right font-semibold text-indigo-600 dark:text-indigo-400">{{ $kat->bukus_count }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="px-4 py-4 text-center text-sm text-gray-500">Belum ada kategori</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Rekap Peminjaman per Buku Terpopuler -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-base font-bold text-gray-800 dark:text-gray-100">Statistik Frekuensi Peminjaman per Buku</h3>
            </div>
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul Buku</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total Dipinjam</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($peminjamanPerBuku as $buku)
                        <tr>
                            <td class="px-6 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $buku->judul }}</td>
                            <td class="px-6 py-3 text-sm text-gray-500">{{ $buku->kategori?->nama ?? '-' }}</td>
                            <td class="px-6 py-3 text-sm text-right font-bold text-indigo-600 dark:text-indigo-400">
                                {{ $buku->peminjamans_count }} kali
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada data buku</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                {{ $peminjamanPerBuku->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

