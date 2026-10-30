<!-- Memanggil layout utama -->
@extends('layouts.app')

<!-- Mengisi bagian @yield('title') di layout utama -->
@section('title', 'Beranda')

<!-- Mengisi bagian @yield('content') di layout utama -->
@section('content')
    <h1>Selamat Datang di Perpus Kampus</h1>
    <p>Aplikasi perpustakaan sederhana tanpa database untuk tugas kuliah.</p>
    <a href="{{ route('buku.index') }}" class="btn">Lihat Koleksi Buku Kami</a>
@endsection




