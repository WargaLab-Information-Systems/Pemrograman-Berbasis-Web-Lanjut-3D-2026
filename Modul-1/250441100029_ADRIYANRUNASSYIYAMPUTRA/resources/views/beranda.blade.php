@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    <section class="hero">
        <h2>Selamat Datang di Perpustakaan Digital</h2>
        <p>Temukan berbagai koleksi buku menarik mulai dari novel, sejarah, filsafat, hingga teknologi.</p>
        <a href="{{ route('buku.index') }}" class="btn btn--large">Jelajahi Koleksi Buku</a>
    </section>

    <section class="info-grid">
        <div class="info-box">
            <h3>Koleksi Lengkap</h3>
            <p>Beragam kategori buku tersedia untuk semua kalangan.</p>
        </div>
        <div class="info-box">
            <h3>Mudah Dicari</h3>
            <p>Lihat detail setiap buku hanya dengan satu klik.</p>
        </div>
        <div class="info-box">
            <h3>Akses Kapan Saja</h3>
            <p>Perpustakaan digital yang siap diakses kapan pun.</p>
        </div>
    </section>
@endsection