@extends('layouts.main')

@section('title', 'Dashboard Admin')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
    <div class="border-b pb-4 mb-6">
        <h3 class="text-xl font-bold text-gray-800">👑 Selamat Datang di Dashboard Admin!</h3>
        <p class="mt-1 text-gray-600">
            Halo <strong>{{ Auth::user()->name }}</strong>, Anda memiliki hak akses penuh untuk mengelola operasional, pengguna, dan laporan perpustakaan.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Card Kelola Pengguna & Role --}}
        @can('kelola data pengguna')
        <div class="p-5 border border-purple-100 bg-purple-50/50 rounded-xl shadow-sm hover:shadow-md transition">
            <div class="flex items-center space-x-3 mb-2">
                <span class="text-2xl">👥</span>
                <h4 class="text-lg font-bold text-purple-900">Kelola Pengguna</h4>
            </div>
            <p class="text-sm text-gray-600 mb-4">
                Atur akun anggota, petugas, serta penetapan role pengguna.
            </p>
            <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition">
                Kelola Pengguna &rarr;
            </a>
        </div>
        @endcan

        {{-- Card Kelola Data Buku --}}
        @can('kelola data buku')
        <div class="p-5 border border-indigo-100 bg-indigo-50/50 rounded-xl shadow-sm hover:shadow-md transition">
            <div class="flex items-center space-x-3 mb-2">
                <span class="text-2xl">📚</span>
                <h4 class="text-lg font-bold text-indigo-900">Katalog Buku</h4>
            </div>
            <p class="text-sm text-gray-600 mb-4">
                Kelola master data buku, tambah judul baru, dan perbarui koleksi.
            </p>
            <a href="{{ route('buku.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition">
                Buka Katalog &rarr;
            </a>
        </div>
        @endcan

        {{-- Card Laporan Perpustakaan --}}
        @can('lihat laporan perpustakaan')
        <div class="p-5 border border-amber-100 bg-amber-50/50 rounded-xl shadow-sm hover:shadow-md transition">
            <div class="flex items-center space-x-3 mb-2">
                <span class="text-2xl">📊</span>
                <h4 class="text-lg font-bold text-amber-900">Laporan Perpustakaan</h4>
            </div>
            <p class="text-sm text-gray-600 mb-4">
                Lihat ringkasan statistik sirkulasi, peminjaman, dan aktivitas perpustakaan.
            </p>
            <a href="{{ route('laporan.index') }}" class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium rounded-lg transition">
                Buka Laporan &rarr;
            </a>
        </div>
        @endcan
    </div>
</div>
@endsection
