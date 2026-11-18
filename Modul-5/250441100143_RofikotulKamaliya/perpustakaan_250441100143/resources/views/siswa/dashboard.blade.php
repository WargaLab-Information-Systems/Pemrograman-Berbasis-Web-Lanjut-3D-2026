@extends('layouts.dashboard')
@section('title', 'Dashboard Siswa')
@section('content')

    <div class="dashboard-heading">
        <h2>Dashboard Siswa</h2>
        <p>Ringkasan aktivitas peminjaman milik Anda.</p>
    </div>

    <section class="dashboard-welcome">
        <h3>Selamat Datang, {{ Auth::user()->name }}!</h3>
        <p>Anda dapat melihat koleksi buku, mengajukan peminjaman, dan melihat riwayat peminjaman Anda.</p>
    </section>

    <section class="dashboard-grid">
        <div class="dashboard-card">
            <h3>Koleksi Buku</h3>
            <p class="dashboard-number">{{ $totalBuku }}</p>
        </div>

        <div class="dashboard-card">
            <h3>Pengajuan Saya</h3>
            <p class="dashboard-number">{{ $pengajuan }}</p>
        </div>

        <div class="dashboard-card">
            <h3>Sedang Saya Pinjam</h3>
            <p class="dashboard-number">{{ $sedangDipinjam }}</p>
        </div>
    </section>

@endsection
