@extends('layouts.main')

@section('title', 'Detail Peminjaman')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <a href="{{ url()->previous() ?: route('peminjaman.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
            &larr; Kembali
        </a>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-2">Detail Peminjaman #{{ $peminjaman->id }}</h1>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden p-6 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 border-b pb-2">Informasi Buku</h3>
                <div>
                    <span class="text-xs text-gray-500 uppercase">Judul Buku</span>
                    <p class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $peminjaman->buku?->judul ?? '-' }}</p>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase">Kategori</span>
                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $peminjaman->buku?->kategori?->nama ?? '-' }}</p>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase">Penulis</span>
                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $peminjaman->buku?->penulis ?? '-' }}</p>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase">Tahun Terbit</span>
                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $peminjaman->buku?->tahun_terbit ?? '-' }}</p>
                </div>
            </div>

            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 border-b pb-2">Informasi Peminjam & Status</h3>
                <div>
                    <span class="text-xs text-gray-500 uppercase">Nama Peminjam</span>
                    <p class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $peminjaman->anggota?->nama ?? '-' }}</p>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase">Email Peminjam</span>
                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $peminjaman->anggota?->email ?? '-' }}</p>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase">Status Peminjaman</span>
                    <div class="mt-1">
                        @if($peminjaman->status === 'diajukan')
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200">Diajukan</span>
                        @elseif($peminjaman->status === 'dipinjam')
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">Sedang Dipinjam</span>
                        @elseif($peminjaman->status === 'menunggu_pengembalian')
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">Menunggu Verifikasi Pengembalian</span>
                        @elseif($peminjaman->status === 'dikembalikan')
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Sudah Dikembalikan</span>
                        @else
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Ditolak</span>
                        @endif
                    </div>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase">Tanggal Pinjam</span>
                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $peminjaman->tanggal_pinjam ? \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d F Y') : '-' }}</p>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase">Tanggal Kembali</span>
                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $peminjaman->tanggal_kembali ? \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d F Y') : '-' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

