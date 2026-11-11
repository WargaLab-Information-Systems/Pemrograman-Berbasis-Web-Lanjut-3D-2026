@extends('layouts.main')

@section('title', 'Beranda')

@section('content')
    <section class="hero" style="text-align: center; padding: 60px 20px;">
        <h2 style="color: #2f7a4c; margin-bottom: 15px;">Selamat Datang di Perpustakaan Digital</h2>
        <p style="font-size: 18px; color: #555; margin-bottom: 25px;">
            Jelajahi koleksi buku kami dan lihat detail setiap buku.
        </p>
        <a href="{{ route('buku.index') }}" style="background-color: #2f7a4c; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; transition: 0.3s;">
            Lihat Daftar Buku
        </a>
    </section>
@endsection