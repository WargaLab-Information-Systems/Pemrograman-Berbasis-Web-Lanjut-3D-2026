@extends('layouts.main')

@section('title', 'Beranda - Perpustakaan Digital')

@section('content')
<div class="hero-section">
    <h1>Selamat Datang di E-Pustaka</h1>
    <p>Pusat koleksi buku ilmiah, pemrograman, dan sistem informasi terlengkap.</p>
    <a href="{{ route('buku.index') }}" class="btn btn-hero">Jelajahi Koleksi Buku</a>
</div>

<div class="features-grid">
    <div class="feature-item">
        <h3>📖 Koleksi Terlengkap</h3>
        <p>Akses berbagai referensi literatur akademik dan teknis secara cepat.</p>
    </div>
    <div class="feature-item">
        <h3>⚡ Pencarian Mudah</h3>
        <p>Temukan detail buku berdasarkan ID dan kategori pilihan.</p>
    </div>
    <div class="feature-item">
        <h3>🎓 Akses Mahasiswa</h3>
        <p>Dirancang khusus untuk mendukung kegiatan belajar dan penelitian.</p>
    </div>
</div>
@endsection