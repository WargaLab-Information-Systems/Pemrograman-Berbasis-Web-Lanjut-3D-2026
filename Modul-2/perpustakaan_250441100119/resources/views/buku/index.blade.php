@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')

    <div class="page-title">
        <h2>Daftar Buku</h2>
        <p>Berikut adalah koleksi buku yang tersedia di perpustakaan.</p>
    </div>

    <div class="book-grid">

        @foreach ($buku as $item)

            <x-book-card
                :judul="$item['judul']"
                :penulis="$item['penulis']"
                :tahun="$item['tahun']"
                :kategori="$item['kategori']"
                >
                
                <a href="{{ route('buku.detail', $item['id']) }}" class="btn">
                    Lihat Detail
                </a>
            </x-book-card>

        @endforeach

    </div>

@endsection