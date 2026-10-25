@extends('layouts.app')

@section('title', 'Beranda')

@section('content')

    <h2>Selamat Datang</h2>

    <div class="detail-buku">

        <h2>Perpustakaan Digital</h2>

        <p>
            Selamat datang di Sistem Informasi Perpustakaan Digital.
        </p>

        <p>
            Temukan berbagai informasi buku yang tersedia pada halaman
            daftar buku.
        </p>

        <a class="detail-button" href="{{ route('buku.index') }}">
            Lihat Daftar Buku
        </a>

    </div>

@endsection