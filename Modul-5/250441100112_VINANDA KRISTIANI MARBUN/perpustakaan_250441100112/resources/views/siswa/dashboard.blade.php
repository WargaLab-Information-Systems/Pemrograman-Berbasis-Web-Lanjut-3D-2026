@extends('layouts.main')

@section('title', 'Dashboard Siswa - Perpustakaan Digital')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    <!-- Header Sambutan -->
    <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-emerald-800 rounded-2xl p-6 sm:p-8 text-white shadow-lg">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <span class="inline-block px-3 py-1 bg-white/20 text-xs font-semibold rounded-full uppercase tracking-wider mb-2">
                    Akun Siswa
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Halo, {{ Auth::user()->name }}!</h1>
                <p class="text-emerald-100 text-sm mt-1">Selamat datang di Perpustakaan Digital. Temukan buku favoritmu, ajukan peminjaman, dan pantau riwayat bacaanmu dengan mudah.</p>
            </div>
            <div class="text-right">
                <span class="text-xs text-emerald-200">Waktu Server</span>
                <div class="text-sm font-semibold">{{ date('d F Y') }}</div>
            </div>
        </div>
    </div>

    <!-- Statistik Siswa -->
    @php
        $anggota = \App\Models\Anggota::where('email', Auth::user()->email)->first();
        $sedangDipinjam = $anggota ? \App\Models\Peminjaman::where('anggota_id', $anggota->id)->whereIn('status', ['dipinjam', 'menunggu_pengembalian'])->count() : 0;
        $totalPinjam = $anggota ? \App\Models\Peminjaman::where('anggota_id', $anggota->id)->count() : 0;
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-150 dark:border-gray-700">
            <div class="text-xs font-medium text-gray-500 uppercase">Buku Sedang Dipinjam</div>
            <div class="text-3xl font-bold text-emerald-600 mt-2">{{ $sedangDipinjam }}</div>
            <a href="{{ route('peminjaman.saya') }}" class="text-xs text-emerald-600 hover:underline mt-2 inline-block">Lihat Buku Dipinjam &rarr;</a>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-150 dark:border-gray-700">
            <div class="text-xs font-medium text-gray-500 uppercase">Total Riwayat Peminjaman</div>
            <div class="text-3xl font-bold text-gray-800 dark:text-gray-100 mt-2">{{ $totalPinjam }}</div>
            <a href="{{ route('peminjaman.saya') }}" class="text-xs text-emerald-600 hover:underline mt-2 inline-block">Lihat Semua Riwayat &rarr;</a>
        </div>
    </div>

    <!-- Menu Akses Cepat Siswa -->
    <div>
        <h2 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4">Layanan Siswa</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="{{ route('buku.index') }}" class="p-6 bg-white dark:bg-gray-800 hover:shadow-md transition rounded-xl border border-gray-100 dark:border-gray-700 block group">
                <div class="text-3xl mb-3">📖</div>
                <h3 class="font-bold text-gray-800 dark:text-gray-100 group-hover:text-emerald-600 transition">Katalog & Daftar Buku</h3>
                <p class="text-xs text-gray-500 mt-1">Cari buku menarik, baca detail informasi buku, dan ajukan peminjaman buku.</p>
            </a>

            <a href="{{ route('peminjaman.saya') }}" class="p-6 bg-white dark:bg-gray-800 hover:shadow-md transition rounded-xl border border-gray-100 dark:border-gray-700 block group">
                <div class="text-3xl mb-3">📋</div>
                <h3 class="font-bold text-gray-800 dark:text-gray-100 group-hover:text-emerald-600 transition">Riwayat Peminjaman Saya</h3>
                <p class="text-xs text-gray-500 mt-1">Pantau status persetujuan peminjaman dan ajukan pengembalian buku.</p>
            </a>

            <a href="{{ route('profile.edit') }}" class="p-6 bg-white dark:bg-gray-800 hover:shadow-md transition rounded-xl border border-gray-100 dark:border-gray-700 block group">
                <div class="text-3xl mb-3">👤</div>
                <h3 class="font-bold text-gray-800 dark:text-gray-100 group-hover:text-emerald-600 transition">Profil Saya</h3>
                <p class="text-xs text-gray-500 mt-1">Lihat dan perbarui informasi akun pribadi serta kata sandi Anda.</p>
            </a>
        </div>
    </div>
</div>
@endsection
