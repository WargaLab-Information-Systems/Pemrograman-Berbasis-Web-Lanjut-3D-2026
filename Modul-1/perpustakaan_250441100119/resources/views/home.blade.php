@extends('layouts.app')

@section('title', 'Beranda')

@section('content')

<div class="home-container">

    <div class="home-card">

        <div class="home-icon">
            📚
        </div>

        <h1>Selamat Datang di Perpustakaan</h1>

        <p>
            Selamat datang di sistem perpustakaan sederhana.
            Di sini Anda dapat melihat berbagai koleksi buku
            beserta informasi mengenai buku tersebut.
        </p>

        <a href="{{ route('buku.index') }}" class="btn">
            Lihat Daftar Buku
        </a>

    </div>

</div>

@endsection