@extends('layouts.app')

@section('title', 'Selamat Datang - Perpustakaan Digital')

@section('content')
<div class="hero-section text-center my-5">
    <h1 class="display-4 fw-bold">Selamat Datang di Perpustakaan Digital</h1>
    <p class="lead text-muted">Temukan dan baca berbagai koleksi buku terbaik kami secara online.</p>
    <div class="mt-4">
        <a href="{{ route('buku.index') }}" class="btn btn-primary btn-lg me-2">Lihat Daftar Buku</a>
    </div>
</div>
@endsection