@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-4">
        <a href="{{ url()->previous() }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium inline-flex items-center">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
        <div class="flex items-center justify-between mb-4">
            <span class="text-xs text-gray-400">ID Transaksi Peminjaman: #{{ $peminjaman->id }}</span>
            
            @if($peminjaman->status === 'menunggu')
                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">Menunggu Konfirmasi</span>
            @elseif($peminjaman->status === 'disetujui' && !$peminjaman->tanggal_kembali)
                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">Sedang Dipinjam</span>
            @elseif($peminjaman->status === 'dikembalikan' || $peminjaman->tanggal_kembali)
                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Telah Dikembalikan</span>
            @else
                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">Ditolak</span>
            @endif
        </div>

        <h1 class="text-xl font-bold text-gray-900 mb-6">Detail Transaksi Peminjaman Buku</h1>

        <div class="border-t border-b border-gray-100 py-4 space-y-3 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-500">Judul Buku:</span>
                <span class="font-bold text-gray-800">{{ $peminjaman->buku->judul ?? '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Kategori Buku:</span>
                <span class="font-medium text-gray-700">{{ $peminjaman->buku->kategori->nama ?? '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Penulis:</span>
                <span class="font-medium text-gray-700">{{ $peminjaman->buku->penulis ?? '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Nama Peminjam:</span>
                <span class="font-semibold text-gray-900">{{ $peminjaman->user->name ?? $peminjaman->anggota->nama ?? '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Email Peminjam:</span>
                <span class="font-medium text-gray-700">{{ $peminjaman->user->email ?? $peminjaman->anggota->email ?? '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Tanggal Pinjam:</span>
                <span class="font-medium text-gray-800">{{ $peminjaman->tanggal_pinjam }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Tanggal Pengembalian:</span>
                <span class="font-medium {{ $peminjaman->tanggal_kembali ? 'text-green-600 font-semibold' : 'text-gray-400' }}">
                    {{ $peminjaman->tanggal_kembali ?? 'Belum Dikembalikan' }}
                </span>
            </div>
        </div>

        <!-- Tombol Aksi Petugas / Admin jika ada -->
        <div class="mt-6 flex items-center justify-between">
            <div class="flex gap-2">
                @if($peminjaman->status === 'menunggu')
                    @can('Memproses persetujuan peminjaman')
                        <form method="POST" action="{{ route('peminjaman.setujui', $peminjaman->id) }}" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-xs font-semibold transition">
                                Setujui Peminjaman
                            </button>
                        </form>
                        <form method="POST" action="{{ route('peminjaman.tolak', $peminjaman->id) }}" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-xs font-semibold transition">
                                Tolak
                            </button>
                        </form>
                    @endcan
                @endif

                @if($peminjaman->status === 'disetujui' && !$peminjaman->tanggal_kembali)
                    @can('Mencatat pengembalian buku')
                        <form method="POST" action="{{ route('peminjaman.kembalikan', $peminjaman->id) }}" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold transition">
                                Catat Pengembalian Buku
                            </button>
                        </form>
                    @endcan
                @endif
            </div>

            <a href="{{ url()->previous() }}" class="text-xs text-gray-500 hover:text-gray-800 font-medium">
                Kembali
            </a>
        </div>
    </div>
</div>
@endsection

