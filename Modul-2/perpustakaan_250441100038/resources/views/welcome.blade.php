@extends('layouts.master') {{-- Sesuaikan jika nama file layout Anda berbeda --}}

@section('title', 'Beranda')

@section('content')
<div class="container mx-auto px-6 py-10">
    <!-- Judul -->
    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">
        Selamat Datang di Perpustakaan Digital
    </h1>
    
    <!-- Deskripsi -->
    <p class="text-gray-600 text-sm md:text-base mb-6">
        Aplikasi ini menampilkan koleksi buku yang tersedia. Silakan lihat daftar buku untuk mulai menjelajah.
    </p>
    
    <!-- Tombol Lihat Daftar Buku -->
    <a href="{{ route('buku.index') }}" 
       class="inline-block bg-[#1e293b] hover:bg-black text-white px-6 py-2.5 rounded-lg text-sm font-medium transition-colors shadow-sm">
        Lihat Daftar Buku
    </a>
</div>
@endsection