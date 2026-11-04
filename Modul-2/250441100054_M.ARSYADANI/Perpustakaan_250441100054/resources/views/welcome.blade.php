@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    <section class="hero">
        <h1>Selamat datang di Perpustakaan Kita</h1>
        <p>Telusuri koleksi buku kami, lihat penulis dan tahun terbitnya, lalu buka halaman detail untuk info lengkap.</p>
        <a href="{{ route('buku.index') }}" class="btn btn-besar">Jelajahi koleksi buku</a>
    </section>
@endsection
