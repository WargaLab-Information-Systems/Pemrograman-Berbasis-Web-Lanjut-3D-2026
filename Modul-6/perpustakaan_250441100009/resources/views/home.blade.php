@extends('layouts.app')

@section('judul', 'Beranda - Perpustakaan Online')

@section('konten')
    <section class="beranda-hero" aria-labelledby="judul-beranda">
        <img
            class="beranda-foto"
            src="https://images.unsplash.com/photo-1507842217343-583bb7270b66?auto=format&fit=crop&w=1800&q=85"
            alt="Rak-rak buku di dalam perpustakaan"
        >
        <div class="beranda-lapisan"></div>
        <div class="beranda-teks">
            <p>Perpustakaan Onlineku</p>
            <h2 id="judul-beranda">Selamat datang.</h2>
            <span>Temukan buku yang ingin kamu baca berikutnya.</span>
        </div>
    </section>

    <section class="beranda-katalog" aria-labelledby="judul-katalog-beranda">
        <div>
            <h2 id="judul-katalog-beranda">Katalog buku</h2>
            <p>Lihat judul, penulis, kategori, dan tahun terbit buku yang tersedia.</p>
        </div>
        <a href="{{ route('buku.index') }}">Lihat daftar buku</a>
    </section>
@endsection