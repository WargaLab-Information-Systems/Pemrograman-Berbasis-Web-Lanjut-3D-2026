@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    <section class="hero">
        <h2>Selamat Datang di PERDIG Radit</h2>
        <p>Jelajahi koleksi buku kami, mulai dari novel, sastra, teknologi, sampai sejarah.</p>
        <a href="{{ route('buku.index') }}" class="btn">Lihat Daftar Buku</a>
    </section>
@endsection
