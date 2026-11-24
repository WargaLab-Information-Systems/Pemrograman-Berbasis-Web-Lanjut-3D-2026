@extends('layouts.app')

@section('title', 'Daftar Buku - Perpustakaan')

@section('content')

    <div class="page-header">
        <p class="hero-label">KOLEKSI PERPUSTAKAAN</p>

        <h1>Daftar Buku</h1>

        <p>
            Berikut adalah koleksi buku yang tersedia di perpustakaan.
        </p>
    </div>

    <div class="book-grid">

        @foreach ($buku as $item)

            <x-book-card
                :judul="$item['judul']"
                :penulis="$item['penulis']"
                :tahun="$item['tahun']"
                :link="route('buku.show', $item['id'])"
            >
                <span>Kategori: {{ $item['kategori'] }}</span>
            </x-book-card>

        @endforeach

    </div>

@endsection