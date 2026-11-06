@extends('layouts.main')

@section('title', 'Dashboard Petugas - Perpustakaan Digital')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    <!-- Header Sambutan -->
    <div class="bg-gradient-to-r from-blue-700 via-teal-700 to-blue-900 rounded-2xl p-6 sm:p-8 text-white shadow-lg">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <span class="inline-block px-3 py-1 bg-white/20 text-xs font-semibold rounded-full uppercase tracking-wider mb-2">
                    Hak Akses: Petugas Perpustakaan
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Selamat Datang, {{ Auth::user()->name }}!</h1>
                <p class="text-blue-100 text-sm mt-1">Anda memiliki wewenang mengelola data buku, memproses peminjaman dan pengembalian, serta mengakses laporan operasional perpustakaan.</p>
            </div>
            <div class="text-right">
                <span class="text-xs text-blue-200">Waktu Server</span>
                <div class="text-sm font-semibold">{{ date('d F Y') }}</div>
            </div>
        </div>
    </div>

    <!-- Ringkasan Statistik -->
    @php
        $jmlBuku = \App\Models\Buku::count();
        $jmlDiajukan = \App\Models\Peminjaman::where('status', 'diajukan')->count();
        $jmlMenungguKembali = \App\Models\Peminjaman::where('status', 'menunggu_pengembalian')->count();
        $jmlDipinjam = \App\Models\Peminjaman::where('status', 'dipinjam')->count();
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow-sm border border-gray-150 dark:border-gray-700">
            <div class="text-xs font-medium text-gray-500 uppercase">Koleksi Buku</div>
            <div class="text-3xl font-bold text-gray-800 dark:text-gray-100 mt-2">{{ $jmlBuku }}</div>
            <a href="{{ route('buku.index') }}" class="text-xs text-blue-600 dark:text-blue-400 hover:underline mt-2 inline-block">Kelola Buku &rarr;</a>
        </div>

        <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow-sm border border-gray-150 dark:border-gray-700">
            <div class="text-xs font-medium text-gray-500 uppercase">Pengajuan Baru</div>
            <div class="text-3xl font-bold text-amber-600 mt-2">{{ $jmlDiajukan }}</div>
            <a href="{{ route('peminjaman.index', ['status' => 'diajukan']) }}" class="text-xs text-amber-600 hover:underline mt-2 inline-block">Proses Sekarang &rarr;</a>
        </div>

        <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow-sm border border-gray-150 dark:border-gray-700">
            <div class="text-xs font-medium text-gray-500 uppercase">Menunggu Kembali</div>
            <div class="text-3xl font-bold text-purple-600 mt-2">{{ $jmlMenungguKembali }}</div>
            <a href="{{ route('peminjaman.index', ['status' => 'menunggu_pengembalian']) }}" class="text-xs text-purple-600 hover:underline mt-2 inline-block">Verifikasi &rarr;</a>
        </div>

        <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow-sm border border-gray-150 dark:border-gray-700">
            <div class="text-xs font-medium text-gray-500 uppercase">Buku Dipinjam</div>
            <div class="text-3xl font-bold text-blue-600 mt-2">{{ $jmlDipinjam }}</div>
            <a href="{{ route('peminjaman.index', ['status' => 'dipinjam']) }}" class="text-xs text-blue-600 hover:underline mt-2 inline-block">Lihat Daftar &rarr;</a>
        </div>
    </div>

    <!-- Menu Aksi Utama -->
    <div>
        <h2 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4">Fitur Operasional Petugas</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="{{ route('buku.index') }}" class="p-6 bg-white dark:bg-gray-800 hover:shadow-md transition rounded-xl border border-gray-100 dark:border-gray-700 block group">
                <div class="text-3xl mb-3">📚</div>
                <h3 class="font-bold text-gray-800 dark:text-gray-100 group-hover:text-blue-600 transition">Kelola Data Buku</h3>
                <p class="text-xs text-gray-500 mt-1">Tambah, perbarui katalog, dan kelola buku perpustakaan.</p>
            </a>

            <a href="{{ route('peminjaman.index') }}" class="p-6 bg-white dark:bg-gray-800 hover:shadow-md transition rounded-xl border border-gray-100 dark:border-gray-700 block group">
                <div class="text-3xl mb-3">🔄</div>
                <h3 class="font-bold text-gray-800 dark:text-gray-100 group-hover:text-blue-600 transition">Proses Peminjaman & Pengembalian</h3>
                <p class="text-xs text-gray-500 mt-1">Setujui pengajuan peminjaman dan catat pengembalian buku siswa.</p>
            </a>

            <a href="{{ route('laporan.index') }}" class="p-6 bg-white dark:bg-gray-800 hover:shadow-md transition rounded-xl border border-gray-100 dark:border-gray-700 block group">
                <div class="text-3xl mb-3">📊</div>
                <h3 class="font-bold text-gray-800 dark:text-gray-100 group-hover:text-blue-600 transition">Laporan Perpustakaan</h3>
                <p class="text-xs text-gray-500 mt-1">Lihat statistik buku dan sirkulasi peminjaman buku.</p>
            </a>
        </div>
    </div>
</div>
@endsection
