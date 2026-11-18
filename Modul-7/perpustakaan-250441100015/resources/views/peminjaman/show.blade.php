@extends('layouts.app')

@section('title', 'Detail Peminjaman')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">
    <div class="bg-white p-6 rounded-lg shadow">
        <h1 class="text-2xl font-bold mb-4">Detail Peminjaman #{{ $peminjaman->id }}</h1>
        <p><strong>Nama Peminjam:</strong> {{ $peminjaman->anggota->nama ?? '-' }}</p>
        <p><strong>Judul Buku:</strong> {{ $peminjaman->buku->judul ?? '-' }}</p>
        <p><strong>Tanggal Pinjam:</strong> {{ $peminjaman->tanggal_pinjam }}</p>
        <p><strong>Status:</strong> {{ ucfirst($peminjaman->status) }}</p>
        <div class="mt-4">
            <a href="{{ route('dashboard') }}" class="text-blue-500 hover:underline">&larr; Kembali ke Dashboard</a>
        </div>
    </div>
</div>
@endsection