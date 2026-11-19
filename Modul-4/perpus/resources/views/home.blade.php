@extends('layouts.app')

@section('title', 'Beranda - Perpustakaan Digital')

@section('content')

    <section class="hero">
        <h2>Selamat Datang di Perpustakaan Digital</h2>

        <p>
            Temukan berbagai koleksi buku yang dapat membantu
            menambah pengetahuan dan wawasan Anda.
        </p>

        <a href="{{ route('buku.index') }}" class="button">
            Lihat Daftar Buku
        </a>
    </section>

@endsection