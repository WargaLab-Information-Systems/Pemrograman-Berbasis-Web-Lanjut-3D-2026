@extends('layouts.dashboard')
@section('title', 'Detail Buku')
@section('content')

    <div class="dashboard-heading">
        <h2>Detail Buku</h2>
    </div>

    <div class="book-detail">

        <h2>{{ $buku->judul }}</h2>

        <div class="detail-item">
            <strong>ID Buku</strong>
            <span>: {{ $buku->id }}</span>
        </div>

        <div class="detail-item">
            <strong>Penulis</strong>
            <span>: {{ $buku->penulis }}</span>
        </div>

        <div class="detail-item">
            <strong>Tahun Terbit</strong>
            <span>: {{ $buku->tahun_terbit }}</span>
        </div>

        <div class="detail-item">
            <strong>Kategori</strong>
            <span>: {{ $buku->kategori->nama ?? '-' }}</span>
        </div>

        <div class="action-row" style="margin-top:18px;">
            @can('mengajukan peminjaman buku')
                <form action="{{ route('peminjaman.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="buku_id" value="{{ $buku->id }}">
                    <button type="submit" class="btn-uniform">Ajukan Peminjaman</button>
                </form>
            @endcan

            <a href="{{ route('buku.index') }}" class="btn-uniform btn-secondary">Kembali ke Daftar Buku</a>
        </div>

    </div>
@endsection
