@extends('layouts.app')
@section('title', 'Beranda')
@section('content')
<div class="home">
    <div class="hero-content" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
        <span class="hero-label">PERPUSTAKAAN DIGITAL</span>
        <h1>Selamat Datang di Perpustakaan Digital</h1>
        <p>Temukan berbagai koleksi buku yang tersedia dan jelajahi informasi buku dengan mudah.</p>
        <a href="{{ route('buku.index') }}" class="btn">Lihat Daftar Buku</a>
    </div>
</div>
@endsection