@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Laporan & Rekapitulasi Perpustakaan</h1>
            <p class="text-sm text-gray-600">Statistik buku per kategori, buku terpopuler, dan rekap aktivitas peminjaman.</p>
        </div>
        <div>
            <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg shadow-sm transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak / Ekspor Laporan
            </button>
        </div>
    </div>

    <!-- Ringkasan Statistik -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
            <p class="text-xs font-semibold text-gray-400 uppercase">Total Koleksi Buku</p>
            <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_buku'] }} Judul</h3>
        </div>
        <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
            <p class="text-xs font-semibold text-gray-400 uppercase">Total Transaksi</p>
            <h3 class="text-2xl font-bold text-indigo-600 mt-1">{{ $stats['total_peminjaman'] }} Kali</h3>
        </div>
        <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
            <p class="text-xs font-semibold text-gray-400 uppercase">Sedang Dipinjam</p>
            <h3 class="text-2xl font-bold text-blue-600 mt-1">{{ $stats['sedang_dipinjam'] }} Buku</h3>
        </div>
        <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
            <p class="text-xs font-semibold text-gray-400 uppercase">Sudah Dikembalikan</p>
            <h3 class="text-2xl font-bold text-green-600 mt-1">{{ $stats['sudah_dikembalikan'] }} Buku</h3>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Rekap Buku Per Kategori -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-base font-bold text-gray-900 mb-4">Rekap Koleksi Buku per Kategori</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 text-xs uppercase font-semibold text-gray-500">
                        <tr>
                            <th class="px-4 py-2.5">Kategori</th>
                            <th class="px-4 py-2.5 text-right">Jumlah Judul Buku</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($kategoris as $k)
                            <tr>
                                <td class="px-4 py-3 font-medium text-gray-800">{{ $k->nama }}</td>
                                <td class="px-4 py-3 text-right font-bold text-indigo-600">{{ $k->buku_count }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="px-4 py-4 text-center text-gray-400">Tidak ada kategori.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Rekap Peminjaman per Buku (Buku Paling Populer) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-base font-bold text-gray-900 mb-4">Top 10 Buku Sering Dipinjam</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 text-xs uppercase font-semibold text-gray-500">
                        <tr>
                            <th class="px-4 py-2.5">Judul Buku</th>
                            <th class="px-4 py-2.5 text-right">Total Dipinjam</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($bukuPopuler as $bp)
                            <tr>
                                <td class="px-4 py-3 font-medium text-gray-800">{{ $bp->judul }}</td>
                                <td class="px-4 py-3 text-right font-bold text-emerald-600">{{ $bp->peminjaman_count }} kali</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="px-4 py-4 text-center text-gray-400">Belum ada riwayat peminjaman.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

