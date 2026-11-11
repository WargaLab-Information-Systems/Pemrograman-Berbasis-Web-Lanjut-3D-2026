@extends('layouts.main')

@section('title', 'Dashboard Admin')

@section('content')
    <h2 style="color:#2f7a4c;">Dashboard Admin</h2>
    <p>Selamat datang, {{ auth()->user()->name }}.</p>

    <div class="card-grid">
        <div class="card-box"><div class="angka">{{ $totalBuku }}</div>Total Buku</div>
        <div class="card-box"><div class="angka">{{ $totalPengguna }}</div>Total Pengguna</div>
        <div class="card-box"><div class="angka">{{ $totalPeminjaman }}</div>Total Peminjaman</div>
        <div class="card-box"><div class="angka">{{ $menunggu }}</div>Menunggu Persetujuan</div>
        <div class="card-box"><div class="angka">{{ $dipinjam }}</div>Sedang Dipinjam</div>
    </div>

    <a class="tombol" href="{{ route('buku.index') }}">Kelola Buku</a>
    <a class="tombol" href="{{ route('pengguna.index') }}">Kelola Pengguna &amp; Role</a>
    <a class="tombol" href="{{ route('peminjaman.index') }}">Data Peminjaman</a>
    <a class="tombol" href="{{ route('pengembalian.index') }}">Data Pengembalian</a>
    <a class="tombol" href="{{ route('laporan.index') }}">Laporan</a>
@endsection
