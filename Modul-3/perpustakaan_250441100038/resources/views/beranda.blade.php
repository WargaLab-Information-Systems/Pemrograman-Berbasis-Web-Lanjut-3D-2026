@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    <section class="hero">
        <h2>Selamat Datang di Perpustakaan Digital</h2>
        <p>Jelajahi koleksi buku kami dan lihat detail setiap buku.</p>
        <a href="{{ route('buku.index') }}" class="btn">Lihat Daftar Buku</a>
    </section>
@endsection