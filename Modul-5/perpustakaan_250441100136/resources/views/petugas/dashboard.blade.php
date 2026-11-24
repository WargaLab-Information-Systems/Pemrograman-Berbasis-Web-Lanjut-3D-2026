@extends('layouts.main')

@section('title', 'Dashboard Petugas')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
    <div class="border-b pb-4 mb-6">
        <h3 class="text-xl font-bold text-gray-800">👋 Selamat Datang, Petugas Perpustakaan!</h3>
        <p class="mt-1 text-gray-600">
            Halo <strong>{{ Auth::user()->name }}</strong>, Anda login dengan hak akses <span class="px-2 py-1 text-xs font-semibold rounded bg-green-100 text-green-800">Petugas</span>.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Card Kelola Data Buku --}}
        @can('kelola data buku')
        <div class="p-5 border border-indigo-100 bg-indigo-50/50 rounded-xl shadow-sm hover:shadow-md transition">
            <div class="flex items-center space-x-3 mb-2">
                <span class="text-2xl">📚</span>
                <h4 class="text-lg font-bold text-indigo-900">Kelola Data Buku</h4>
            </div>
            <p class="text-sm text-gray-600 mb-4">
                Tambah, ubah, dan perbarui katalog koleksi buku perpustakaan.
            </p>
            <a href="{{ route('buku.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition">
                Buka Katalog Buku &rarr;
            </a>
        </div>
        @endcan

        {{-- Card Kelola Peminjaman & Pengembalian --}}
        @can('kelola peminjaman')
        <div class="p-5 border border-emerald-100 bg-emerald-50/50 rounded-xl shadow-sm hover:shadow-md transition">
            <div class="flex items-center space-x-3 mb-2">
                <span class="text-2xl">🔄</span>
                <h4 class="text-lg font-bold text-emerald-900">Persetujuan & Pengembalian</h4>
            </div>
            <p class="text-sm text-gray-600 mb-4">
                Proses pengajuan peminjaman buku dari siswa dan catat transaksi pengembalian.
            </p>
            <a href="{{ route('peminjaman.index') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition">
                Kelola Transaksi &rarr;
            </a>
        </div>
        @endcan

        {{-- Card Laporan Perpustakaan --}}
        @can('lihat laporan perpustakaan')
        <div class="p-5 border border-amber-100 bg-amber-50/50 rounded-xl shadow-sm hover:shadow-md transition md:col-span-2">
            <div class="flex items-center space-x-3 mb-2">
                <span class="text-2xl">📊</span>
                <h4 class="text-lg font-bold text-amber-900">Laporan Perpustakaan</h4>
            </div>
            <p class="text-sm text-gray-600 mb-4">
                Pantau statistik koleksi buku, jumlah transaksi, serta sirkulasi peminjaman buku.
            </p>
            <a href="{{ route('laporan.index') }}" class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium rounded-lg transition">
                Lihat Laporan &rarr;
            </a>
        </div>
        @endcan
    </div>
</div>
@endsection
