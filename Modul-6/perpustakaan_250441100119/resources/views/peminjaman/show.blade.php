@extends('layouts.app')

@section('title', 'Detail Peminjaman')

@section('content')

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8"
     style="padding-top:36px;padding-bottom:32px;">

    <h1 class="text-2xl font-bold text-gray-800 mb-3">
        Detail Peminjaman Buku
    </h1>

    <p class="text-gray-600 mb-8">
        Informasi peminjaman buku perpustakaan.
    </p>

    <div class="bg-white shadow rounded-lg p-6">

        <div class="space-y-5">

            <div>
                <p class="text-sm text-gray-500 mb-1">
                    ID Peminjaman
                </p>
                <p class="font-semibold text-gray-800">
                    {{ $peminjaman->id }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500 mb-1">
                    Nama Anggota
                </p>
                <p class="font-semibold text-gray-800">
                    {{ $peminjaman->anggota?->nama ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500 mb-1">
                    Judul Buku
                </p>
                <p class="font-semibold text-gray-800">
                    {{ $peminjaman->buku?->judul ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500 mb-1">
                    Tanggal Pinjam
                </p>
                <p class="font-semibold text-gray-800">
                    {{ $peminjaman->tanggal_pinjam }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500 mb-1">
                    Tanggal Kembali
                </p>
                <p class="font-semibold text-gray-800">
                    {{ $peminjaman->tanggal_kembali ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500 mb-1">
                    Status Peminjaman
                </p>
                <p class="font-semibold text-gray-800">
                    {{ ucfirst($peminjaman->status) }}
                </p>
            </div>

        </div>

        <div class="mt-8">
            <a
                href="{{ route('peminjaman.index') }}"
                style="display:inline-block;background-color:#2563eb;color:white;padding:10px 20px;border-radius:8px;font-weight:600;"
            >
                Kembali
            </a>
        </div>

    </div>

</div>

@endsection