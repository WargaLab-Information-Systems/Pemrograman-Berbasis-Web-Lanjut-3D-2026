@extends('layouts.app')

@section('title', 'Dashboard Admin - Perpustakaan Digital')

@section('content')
    <h2 class="page-title">Dashboard Admin</h2>
    <p>Selamat datang, {{ auth()->user()->name }}.</p>

    <div class="stat-grid">
        <div class="stat"><span class="stat__angka">{{ $jumlahBuku }}</span><span class="stat__label">Buku</span></div>
        <div class="stat"><span class="stat__angka">{{ $jumlahKategori }}</span><span class="stat__label">Kategori</span></div>
        <div class="stat"><span class="stat__angka">{{ $jumlahPengguna }}</span><span class="stat__label">Pengguna</span></div>
        <div class="stat"><span class="stat__angka">{{ $diajukan }}</span><span class="stat__label">Pengajuan menunggu</span></div>
        <div class="stat"><span class="stat__angka">{{ $dipinjam }}</span><span class="stat__label">Sedang dipinjam</span></div>
        <div class="stat"><span class="stat__angka">{{ $dikembalikan }}</span><span class="stat__label">Sudah dikembalikan</span></div>
    </div>

    <div class="form-actions">
        @can('kelola data buku')
            <a href="{{ route('buku.index') }}" class="btn btn-primary">Kelola Buku</a>
        @endcan
        @can('kelola data pengguna')
            <a href="{{ route('admin.users.index') }}" class="btn btn-primary">Kelola Pengguna & Role</a>
        @endcan
        @can('kelola peminjaman')
            <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary">Peminjaman</a>
        @endcan
        @can('kelola pengembalian')
            <a href="{{ route('pengembalian.index') }}" class="btn btn-secondary">Pengembalian</a>
        @endcan
        @can('lihat laporan perpustakaan')
            <a href="{{ route('laporan.index') }}" class="btn btn-secondary">Laporan</a>
        @endcan
    </div>
@endsection
