@extends('layouts.app')

@section('title', 'Detail Peminjaman - Perpustakaan Digital')

@section('content')
    <h2 class="page-title">Detail Peminjaman #{{ $peminjaman->id }}</h2>

    {{-- File ini TIDAK berubah lagi di Soal 2-4. Yang berubah hanya
         pemeriksaan otorisasi di controller (lihat PeminjamanController). --}}
    <div class="detail-buku">
        <p><strong>Anggota:</strong> {{ $peminjaman->anggota->nama }}</p>
        <p><strong>Buku:</strong> {{ $peminjaman->buku->judul }}</p>
        <p><strong>Kategori:</strong> {{ $peminjaman->buku->kategori->nama ?? '-' }}</p>
        <p><strong>Tanggal Pinjam:</strong> {{ $peminjaman->tglPinjam() }}</p>
        <p><strong>Tanggal Kembali:</strong> {{ $peminjaman->tglKembali() }}</p>
        <p><strong>Status:</strong> <span class="badge badge-{{ $peminjaman->status }}">{{ $peminjaman->status }}</span></p>

        <a href="{{ route('riwayat.show', auth()->user()) }}" class="btn btn-secondary">&larr; Kembali</a>
    </div>
@endsection
