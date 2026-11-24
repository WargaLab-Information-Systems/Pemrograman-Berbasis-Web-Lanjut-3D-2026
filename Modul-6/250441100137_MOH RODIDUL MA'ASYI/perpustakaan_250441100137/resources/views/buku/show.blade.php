@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-4">
        <a href="{{ route('buku.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium inline-flex items-center">
            &larr; Kembali ke Katalog Buku
        </a>
    </div>

    @if ($buku)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
            <div class="flex items-center justify-between mb-4">
                <span class="px-3 py-1 bg-indigo-50 text-indigo-700 text-xs font-semibold rounded-full">
                    {{ is_object($buku->kategori) ? $buku->kategori->nama : ($buku['kategori'] ?? 'Umum') }}
                </span>
                <span class="text-xs text-gray-400">ID Buku: #{{ $buku->id ?? $buku['id'] }}</span>
            </div>

            <h1 class="text-2xl font-extrabold text-gray-900 mb-2 leading-tight">
                {{ $buku->judul ?? $buku['judul'] }}
            </h1>

            <div class="border-t border-b border-gray-100 py-4 my-4 space-y-3 text-sm text-gray-600">
                <div class="flex justify-between">
                    <span class="font-medium text-gray-500">Penulis / Pengarang:</span>
                    <span class="font-semibold text-gray-800">{{ $buku->penulis ?? $buku['penulis'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium text-gray-500">Tahun Terbit:</span>
                    <span class="font-semibold text-gray-800">{{ $buku->tahun_terbit ?? $buku['tahun'] ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium text-gray-500">Status Ketersediaan:</span>
                    <span class="font-semibold text-emerald-600">Tersedia di Perpustakaan</span>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4">
                @can('Mengajukan peminjaman buku')
                    <a href="{{ route('peminjaman.create', ['buku_id' => $buku->id ?? $buku['id']]) }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Ajukan Peminjaman Buku Ini
                    </a>
                @endcan

                @can('Kelola data buku')
                    <a href="{{ route('buku.kelola') }}" class="inline-flex items-center px-4 py-2 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 text-sm font-semibold rounded-lg transition">
                        Kelola di Inventaris &rarr;
                    </a>
                @endcan
            </div>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center">
            <h2 class="text-xl font-bold text-red-600 mb-2">Buku Tidak Ditemukan</h2>
            <p class="text-gray-500 mb-4 text-sm">Maaf, buku yang Anda cari tidak tersedia dalam database kami.</p>
            <a href="{{ route('buku.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg">
                Kembali ke Katalog
            </a>
        </div>
    @endif
</div>
@endsection
