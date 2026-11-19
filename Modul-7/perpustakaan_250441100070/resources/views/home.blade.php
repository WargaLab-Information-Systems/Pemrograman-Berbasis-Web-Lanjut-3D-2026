{{-- 8. @extends, @section pada halaman Beranda --}}
@extends('layouts.app')

@section('title', 'Beranda - Perpustakaan Digital')

@section('content')
    <section class="hero">
        <h2>Selamat Datang di Perpustakaan Digital</h2>
        <p>
            Temukan koleksi buku favorit Anda &mdash; mulai dari novel,
            sains populer, hingga buku pemrograman &mdash; semuanya dalam
            satu tempat.
        </p>
        <a href="{{ route('buku.index') }}" class="btn btn-primary">Lihat Daftar Buku</a>
    </section>
@endsection
