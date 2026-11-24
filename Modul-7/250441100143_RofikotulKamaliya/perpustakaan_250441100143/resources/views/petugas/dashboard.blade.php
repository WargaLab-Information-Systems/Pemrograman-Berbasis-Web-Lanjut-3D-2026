@extends('layouts.dashboard')
@section('title', 'Dashboard Petugas')
@section('content')

    <div class="dashboard-heading">
        <h2>Dashboard Petugas</h2>
        <p>Ringkasan operasional perpustakaan.</p>
    </div>

    <section class="dashboard-welcome">
        <h3>Selamat Datang, Petugas!</h3>
        <p>Anda dapat mengelola buku, peminjaman, dan pengembalian.</p>
    </section>

    <section class="dashboard-grid">
        <div class="dashboard-card">
            <h3>Total Buku</h3>
            <p class="dashboard-number">{{ $totalBuku }}</p>
        </div>

        <div class="dashboard-card">
            <h3>Menunggu Persetujuan</h3>
            <p class="dashboard-number">{{ $menungguPersetujuan }}</p>
        </div>

        <div class="dashboard-card">
            <h3>Sedang Dipinjam</h3>
            <p class="dashboard-number">{{ $sedangDipinjam }}</p>
        </div>
    </section>

@endsection
