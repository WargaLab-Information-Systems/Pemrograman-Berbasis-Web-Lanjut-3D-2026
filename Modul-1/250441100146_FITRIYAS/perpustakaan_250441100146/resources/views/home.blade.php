@extends('layouts.app')

@section('title', 'Beranda - Perpustakaan')

@section('content')

    <section class="hero">
        <div>
            <p class="hero-label">SELAMAT DATANG</p>

            <h1>
                Temukan Buku Favoritmu
            </h1>

            <p>
                Selamat datang di Perpustakaan. Temukan berbagai
                koleksi buku menarik untuk menambah wawasan dan
                pengetahuan.
            </p>

            <a href="{{ route('buku.index') }}" class="btn">
                Lihat Daftar Buku
            </a>
        </div>
    </section>

    <section class="welcome">
        <h2>Perpustakaan Digital</h2>

        <p>
            Nikmati kemudahan melihat koleksi buku yang tersedia
            dan temukan buku yang sesuai dengan minatmu.
        </p>
    </section>

@endsection