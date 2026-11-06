@extends('layouts.main')

@section('title', 'Dashboard Admin - Perpustakaan Digital')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    <!-- Header Sambutan -->
    <div class="bg-gradient-to-r from-indigo-700 via-purple-700 to-indigo-900 rounded-2xl p-6 sm:p-8 text-white shadow-lg">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <span class="inline-block px-3 py-1 bg-white/20 text-xs font-semibold rounded-full uppercase tracking-wider mb-2">
                    Hak Akses: Administrator
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Selamat Datang, {{ Auth::user()->name }}!</h1>
                <p class="text-indigo-100 text-sm mt-1">Anda memiliki wewenang penuh dalam mengelola data buku, operasional perpustakaan, laporan, serta manajemen akun dan role pengguna.</p>
            </div>
            <div class="text-right">
                <span class="text-xs text-indigo-200">Waktu Server</span>
                <div class="text-sm font-semibold">{{ date('d F Y') }}</div>
            </div>
        </div>
    </div>

    <!-- Ringkasan Statistik -->
    @php
        $jmlBuku = \App\Models\Buku::count();
        $jmlUser = \App\Models\User::count();
        $jmlPinjam = \App\Models\Peminjaman::where('status', 'dipinjam')->count();
        $jmlKembali = \App\Models\Peminjaman::where('status', 'dikembalikan')->count();
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow-sm border border-gray-150 dark:border-gray-700">
            <div class="text-xs font-medium text-gray-500 uppercase">Koleksi Buku</div>
            <div class="text-3xl font-bold text-gray-800 dark:text-gray-100 mt-2">{{ $jmlBuku }}</div>
            <a href="{{ route('buku.index') }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline mt-2 inline-block">Lihat Buku &rarr;</a>
        </div>

        <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow-sm border border-gray-150 dark:border-gray-700">
            <div class="text-xs font-medium text-gray-500 uppercase">Total Pengguna</div>
            <div class="text-3xl font-bold text-gray-800 dark:text-gray-100 mt-2">{{ $jmlUser }}</div>
            <a href="{{ route('admin.users.index') }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline mt-2 inline-block">Kelola Pengguna &rarr;</a>
        </div>

        <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow-sm border border-gray-150 dark:border-gray-700">
            <div class="text-xs font-medium text-gray-500 uppercase">Buku Dipinjam</div>
            <div class="text-3xl font-bold text-blue-600 mt-2">{{ $jmlPinjam }}</div>
            <a href="{{ route('peminjaman.index') }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline mt-2 inline-block">Sirkulasi &rarr;</a>
        </div>

        <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow-sm border border-gray-150 dark:border-gray-700">
            <div class="text-xs font-medium text-gray-500 uppercase">Total Selesai</div>
            <div class="text-3xl font-bold text-green-600 mt-2">{{ $jmlKembali }}</div>
            <a href="{{ route('laporan.index') }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline mt-2 inline-block">Lihat Laporan &rarr;</a>
        </div>
    </div>

    <!-- Menu Aksi Utama -->
    <div>
        <h2 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4">Fitur Utama Administrator</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <a href="{{ route('buku.index') }}" class="p-6 bg-white dark:bg-gray-800 hover:shadow-md transition rounded-xl border border-gray-100 dark:border-gray-700 block group">
                <div class="text-3xl mb-3">📚</div>
                <h3 class="font-bold text-gray-800 dark:text-gray-100 group-hover:text-indigo-600 transition">Kelola Data Buku</h3>
                <p class="text-xs text-gray-500 mt-1">Tambah koleksi baru, perbarui data buku, atau hapus buku.</p>
            </a>

            <a href="{{ route('admin.users.index') }}" class="p-6 bg-white dark:bg-gray-800 hover:shadow-md transition rounded-xl border border-gray-100 dark:border-gray-700 block group">
                <div class="text-3xl mb-3">👥</div>
                <h3 class="font-bold text-gray-800 dark:text-gray-100 group-hover:text-indigo-600 transition">Kelola Pengguna & Role</h3>
                <p class="text-xs text-gray-500 mt-1">Kelola data pengguna serta penetapan role (Admin, Petugas, Siswa).</p>
            </a>

            <a href="{{ route('peminjaman.index') }}" class="p-6 bg-white dark:bg-gray-800 hover:shadow-md transition rounded-xl border border-gray-100 dark:border-gray-700 block group">
                <div class="text-3xl mb-3">🔄</div>
                <h3 class="font-bold text-gray-800 dark:text-gray-100 group-hover:text-indigo-600 transition">Kelola Peminjaman</h3>
                <p class="text-xs text-gray-500 mt-1">Persetujuan peminjaman dan pencatatan pengembalian buku.</p>
            </a>

            <a href="{{ route('laporan.index') }}" class="p-6 bg-white dark:bg-gray-800 hover:shadow-md transition rounded-xl border border-gray-100 dark:border-gray-700 block group">
                <div class="text-3xl mb-3">📊</div>
                <h3 class="font-bold text-gray-800 dark:text-gray-100 group-hover:text-indigo-600 transition">Laporan Perpustakaan</h3>
                <p class="text-xs text-gray-500 mt-1">Statistik sirkulasi buku per kategori dan buku terpopuler.</p>
            </a>
        </div>
    </div>
</div>
@endsection
