@extends('layouts.app')

@section('title', 'Laporan - Perpustakaan Digital')

@section('content')
    <h2 class="page-title">Laporan Perpustakaan</h2>

    <div class="stat-grid">
        <div class="stat"><span class="stat__angka">{{ $totalBuku }}</span><span class="stat__label">Total buku</span></div>
        <div class="stat"><span class="stat__angka">{{ $totalAnggota }}</span><span class="stat__label">Total anggota</span></div>
        @foreach (['diajukan', 'dipinjam', 'dikembalikan', 'ditolak'] as $status)
            <div class="stat">
                <span class="stat__angka">{{ $perStatus[$status] ?? 0 }}</span>
                <span class="stat__label">Peminjaman {{ $status }}</span>
            </div>
        @endforeach
    </div>

    <h3>Jumlah buku per kategori</h3>
    <div class="table-wrap">
        <table class="tabel-buku">
            <thead><tr><th>Kategori</th><th>Jumlah Buku</th></tr></thead>
            <tbody>
                @foreach ($bukuPerKategori as $k)
                    <tr><td>{{ $k->nama }}</td><td>{{ $k->bukus_count }}</td></tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <h3 class="judul-seksi">Buku paling sering dipinjam</h3>
    <div class="table-wrap">
        <table class="tabel-buku">
            <thead><tr><th>Judul</th><th>Penulis</th><th>Total Peminjaman</th></tr></thead>
            <tbody>
                @foreach ($terpopuler as $b)
                    <tr><td>{{ $b->judul }}</td><td>{{ $b->penulis }}</td><td>{{ $b->peminjamans_count }}</td></tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
