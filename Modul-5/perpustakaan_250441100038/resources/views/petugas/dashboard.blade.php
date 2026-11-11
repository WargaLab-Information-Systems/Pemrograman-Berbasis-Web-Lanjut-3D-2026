@extends('layouts.main')

@section('title', 'Dashboard Petugas')

@section('content')
    <h2 style="color:#2f7a4c;">Dashboard Petugas</h2>
    <p>Selamat datang, {{ auth()->user()->name }}.</p>

    <div class="card-grid">
        <div class="card-box"><div class="angka">{{ $totalBuku }}</div>Total Buku</div>
        <div class="card-box"><div class="angka">{{ $menunggu }}</div>Menunggu Persetujuan</div>
        <div class="card-box"><div class="angka">{{ $dipinjam }}</div>Sedang Dipinjam</div>
    </div>

    <a class="tombol" href="{{ route('buku.index') }}">Kelola Buku</a>
    <a class="tombol" href="{{ route('peminjaman.index') }}">Proses Peminjaman</a>
    <a class="tombol" href="{{ route('pengembalian.index') }}">Catat Pengembalian</a>
    <a class="tombol" href="{{ route('laporan.index') }}">Laporan</a>
@endsection
