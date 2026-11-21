@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard Siswa</h1>
            <p class="text-sm text-gray-600">Halo, <span class="font-semibold text-emerald-600">{{ Auth::user()->name }}</span>! Selamat datang di Perpustakaan Digital.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('buku.index') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg shadow-sm transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                Jelajahi Buku
            </a>
            <a href="{{ route('peminjaman.riwayat') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg shadow-sm transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Riwayat Peminjaman
            </a>
        </div>
    </div>

    <!-- Banner Informasi Siswa -->
    <div class="bg-gradient-to-r from-emerald-600 to-teal-700 rounded-xl p-6 text-white shadow-md mb-8">
        <div class="max-w-2xl">
            <h2 class="text-xl font-bold mb-2">Temukan Buku Favoritmu Hari Ini</h2>
            <p class="text-emerald-100 text-sm mb-4">Kamu dapat melihat seluruh koleksi buku perpustakaan dan mengajukan peminjaman secara langsung.</p>
            <a href="{{ route('buku.index') }}" class="inline-flex items-center px-4 py-2 bg-white text-emerald-800 text-sm font-bold rounded-lg shadow hover:bg-emerald-50 transition">
                Cari & Pinjam Buku &rarr;
            </a>
        </div>
    </div>

    <!-- Riwayat Peminjaman Terkini Siswa -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-lg font-bold text-gray-900">Status Peminjaman Terkini Saya</h2>
            <a href="{{ route('peminjaman.riwayat') }}" class="text-sm text-emerald-600 hover:text-emerald-800 font-medium">Lihat Semua Riwayat &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs uppercase font-semibold text-gray-500">
                    <tr>
                        <th class="px-6 py-3">Judul Buku</th>
                        <th class="px-6 py-3">Tanggal Pinjam</th>
                        <th class="px-6 py-3">Tanggal Pengembalian</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($riwayatPeminjaman as $rp)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $rp->buku->judul ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $rp->tanggal_pinjam }}</td>
                            <td class="px-6 py-4">{{ $rp->tanggal_kembali ?? 'Belum Kembali' }}</td>
                            <td class="px-6 py-4">
                                @if($rp->status === 'menunggu')
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">Menunggu Konfirmasi</span>
                                @elseif($rp->status === 'disetujui' && !$rp->tanggal_kembali)
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">Sedang Dipinjam</span>
                                @elseif($rp->status === 'dikembalikan' || $rp->tanggal_kembali)
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">Sudah Dikembalikan</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">Pengajuan Ditolak</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('peminjaman.show', $rp->id) }}" class="text-emerald-600 hover:text-emerald-900 font-medium text-xs">Lihat Bukti</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-400">
                                Kamu belum memiliki riwayat peminjaman buku. Silakan jelajahi katalog untuk meminjam buku.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

