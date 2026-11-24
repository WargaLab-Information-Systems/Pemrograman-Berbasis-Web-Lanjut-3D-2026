@extends('layouts.dashboard')
@section('title', 'Dashboard Admin')
@section('content')

    <div class="dashboard-heading">
        <h2>Dashboard Admin</h2>
        <p>Ringkasan aktivitas Perpustakaan Digital.</p>
    </div>

    <section class="dashboard-welcome">
        <h3>Selamat Datang, Admin!</h3>
        <p>
            Anda memiliki akses penuh untuk mengelola sistem
            Perpustakaan Digital.
        </p>

        @can('kelola data pengguna')
            <div class="inline-form" style="margin-top:12px;">
                <a href="{{ route('admin.users.index') }}" class="btn-primary">Kelola Pengguna &amp; Role</a>
            </div>
        @endcan
    </section>

    <section class="dashboard-grid">

        <div class="dashboard-card">
            <h3>Total Buku</h3>
            <p class="dashboard-number">{{ $totalBuku }}</p>
        </div>

        <div class="dashboard-card">
            <h3>Total Pengguna</h3>
            <p class="dashboard-number">{{ $totalPengguna }}</p>
        </div>

        <div class="dashboard-card">
            <h3>Total Admin</h3>
            <p class="dashboard-number">{{ $totalAdmin }}</p>
        </div>

        <div class="dashboard-card">
            <h3>Total Petugas</h3>
            <p class="dashboard-number">{{ $totalPetugas }}</p>
        </div>

        <div class="dashboard-card">
            <h3>Total Siswa</h3>
            <p class="dashboard-number">{{ $totalSiswa }}</p>
        </div>

        <div class="dashboard-card">
            <h3>Total Peminjaman</h3>
            <p class="dashboard-number">{{ $totalPeminjaman }}</p>
        </div>

        <div class="dashboard-card">
            <h3>Menunggu Persetujuan</h3>
            <p class="dashboard-number">{{ $menungguPersetujuan }}</p>
        </div>

    </section>

@endsection
