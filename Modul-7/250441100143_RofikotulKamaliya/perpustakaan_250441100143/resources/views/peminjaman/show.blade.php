@extends('layouts.dashboard')

@section('title', 'Detail Peminjaman')

@section('content')
<div class="dashboard-heading">
    <h2>Detail Peminjaman</h2>
</div>

<div class="book-detail">
    <h2>{{ $peminjaman->buku->judul ?? '-' }}</h2>

    <div class="detail-item">
        <strong>Peminjam</strong>
        <span>: {{ $peminjaman->anggota->nama ?? '-' }}</span>
    </div>

    <div class="detail-item">
        <strong>Kategori</strong>
        <span>: {{ $peminjaman->buku->kategori->nama ?? '-' }}</span>
    </div>

    <div class="detail-item">
        <strong>Tanggal Pinjam</strong>
        <span>: {{ $peminjaman->tanggal_pinjam }}</span>
    </div>

    <div class="detail-item">
        <strong>Tanggal Kembali</strong>
        <span>: {{ $peminjaman->tanggal_kembali ?? '-' }}</span>
    </div>

    <div class="detail-item">
        <strong>Status</strong>
        <span>: <span class="badge badge-{{ $peminjaman->status }}">{{ ucfirst($peminjaman->status) }}</span></span>
    </div>

    <div style="margin-top:15px;">
        <a href="{{ route('peminjaman.riwayat') }}" class="btn">Kembali ke Riwayat</a>
    </div>
</div>
@endsection
