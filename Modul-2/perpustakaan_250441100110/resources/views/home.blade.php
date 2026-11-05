@extends('layouts.app')

@section('judul', 'Beranda')

@section('konten')

    <h2>Selamat Datang di Perpustakaan Digital</h2>

    <p>
        Silakan melihat koleksi buku yang tersedia.
    </p>

    <a href="{{ route('buku.index') }}">
        Lihat Daftar Buku
    </a>

@endsection