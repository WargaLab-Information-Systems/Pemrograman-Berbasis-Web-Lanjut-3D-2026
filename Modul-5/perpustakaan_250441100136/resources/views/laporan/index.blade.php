@extends('layouts.main')

@section('title', 'Laporan Perpustakaan')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
        <span>📊</span> Laporan Operasional Perpustakaan
    </h2>
    <p class="text-sm text-gray-600">
        Ringkasan statistik data koleksi, sirkulasi peminjaman, dan aktivitas perpustakaan.
    </p>
</div>

<!-- Kartu Ringkasan Statistik -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
        <div class="text-sm font-medium text-gray-500">Total Koleksi Buku</div>
        <div class="mt-2 text-3xl font-extrabold text-indigo-600">{{ $totalBuku }}</div>
        <div class="text-xs text-gray-400 mt-1">Judul buku terdaftar</div>
    </div>

    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
        <div class="text-sm font-medium text-gray-500">Total Transaksi Peminjaman</div>
        <div class="mt-2 text-3xl font-extrabold text-blue-600">{{ $totalPeminjaman }}</div>
        <div class="text-xs text-gray-400 mt-1">Keseluruhan sirkulasi</div>
    </div>

    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
        <div class="text-sm font-medium text-gray-500">Sedang Dipinjam</div>
        <div class="mt-2 text-3xl font-extrabold text-amber-500">{{ $bukuDipinjam }}</div>
        <div class="text-xs text-gray-400 mt-1">Belum dikembalikan</div>
    </div>

    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
        <div class="text-sm font-medium text-gray-500">Sudah Dikembalikan</div>
        <div class="mt-2 text-3xl font-extrabold text-emerald-600">{{ $bukuKembali }}</div>
        <div class="text-xs text-gray-400 mt-1">Transaksi selesai</div>
    </div>
</div>

<!-- Tabel Riwayat Sirkulasi Terbaru -->
<div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
        <h3 class="font-semibold text-gray-800 text-base">📑 Aktivitas Sirkulasi Terakhir</h3>
        <span class="text-xs text-gray-500">10 data terbaru</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
                    <th class="p-3 border-b">ID</th>
                    <th class="p-3 border-b">Nama Peminjam</th>
                    <th class="p-3 border-b">Judul Buku</th>
                    <th class="p-3 border-b">Tgl Pinjam</th>
                    <th class="p-3 border-b">Tgl Kembali</th>
                    <th class="p-3 border-b text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($transaksiTerbaru as $trx)
                <tr class="hover:bg-gray-50">
                    <td class="p-3 font-semibold text-gray-700">#{{ $trx->id }}</td>
                    <td class="p-3">{{ $trx->anggota->nama ?? 'Anggota #'.$trx->anggota_id }}</td>
                    <td class="p-3 font-medium text-gray-900">{{ $trx->buku->judul ?? 'Buku #'.$trx->buku_id }}</td>
                    <td class="p-3 text-gray-600">{{ $trx->tanggal_pinjam }}</td>
                    <td class="p-3 text-gray-600">{{ $trx->tanggal_kembali ?? '-' }}</td>
                    <td class="p-3 text-center">
                        @if($trx->tanggal_kembali)
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                Dikembalikan
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                                Sedang Dipinjam
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-6 text-center text-gray-500">
                        Belum ada catatan sirkulasi peminjaman.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
