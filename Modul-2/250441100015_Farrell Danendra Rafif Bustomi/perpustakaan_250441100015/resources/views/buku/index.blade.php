@extends('layouts.app')
@section('title', 'Daftar Buku')
@section('content')
<div class="book-page">
    <div class="page-header" data-aos="fade-down" data-aos-duration="800" data-aos-delay="100">
        <span class="page-label">KOLEKSI PERPUSTAKAAN</span>
        <h1>Daftar Buku</h1>
        <p>Jelajahi koleksi buku yang tersedia di perpustakaan.</p>
    </div>
    <div class="book-grid">
        @foreach ($books as $book)
            <x-buku-card :book="$book" data-aos="fade-up" data-aos-duration="800" data-aos-delay="{{ min($loop->index * 150, 600) }}">
                <x-slot:footer>
                    <small>ID Buku: {{ $book->id }}</small>
                </x-slot:footer>
            </x-buku-card>
        @endforeach
    </div>
</div>
@endsection