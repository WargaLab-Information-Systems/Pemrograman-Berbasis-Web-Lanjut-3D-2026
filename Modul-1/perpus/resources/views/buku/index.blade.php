@extends('layouts.app')

@section('title', 'Daftar Buku - Perpustakaan Digital')

@section('content')

    <div class="page-header">
        <h2>Daftar Buku</h2>

        <p>
            Berikut adalah koleksi buku yang tersedia
            di perpustakaan.
        </p>
    </div>

    <div class="book-grid">

        @foreach ($buku as $item)

            <x-buku-card
                :judul="$item['judul']"
                :penulis="$item['penulis']"
                :tahun="$item['tahun']"
                :gambar="$item['gambar']"
            >

                <a
                    href="{{ route('buku.detail', $item['id']) }}"
                    class="button"
                >
                    Lihat Detail
                </a>

            </x-buku-card>

        @endforeach

    </div>

@endsection