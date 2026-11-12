@extends('layouts.app')
@section('title', 'Beranda')
@section('content')

    <section class="hero">
        <h2>SELAMAT DATANG</h2>
        <h3>
            Temukan berbagai koleksi buku yang tersedia di perpustakaan kami.
        </h3>
        <p>
            Perpustakaan Digital merupakan tempat untuk menjelajahi berbagai
            koleksi buku secara mudah dan praktis. Kamu dapat melihat daftar
            buku yang tersedia, mengetahui informasi setiap buku, serta
            menemukan bacaan yang sesuai dengan minatmu.
        </p>

        <a href="{{ route('buku.index') }}" class="btn">
            Lihat Daftar Buku
        </a>
    </section>
@endsection