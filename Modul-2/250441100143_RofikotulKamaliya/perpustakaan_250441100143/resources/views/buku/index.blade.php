@extends('layouts.app')
@section('title', 'Daftar Buku')
@section('content')

    <div class="page-header">
        <h2>Daftar Buku</h2>

        <p>
            Berikut adalah seluruh koleksi buku yang tersedia.
        </p>
    </div>

    <div class="book-grid">

        @foreach ($buku as $item)

            <x-buku-card
                :id="$item['id']"
                :judul="$item['judul']"
                :penulis="$item['penulis']"
                :tahun="$item['tahun']"
            >
                <p>
                    <strong>Kategori:</strong>
                    {{ $item['kategori'] }}
                </p>
            </x-buku-card>

        @endforeach

    </div>
@endsection