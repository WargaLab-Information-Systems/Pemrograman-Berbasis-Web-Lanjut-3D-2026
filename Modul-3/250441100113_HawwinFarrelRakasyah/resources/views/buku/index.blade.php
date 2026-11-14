@extends('layouts.main')

@section('content')
    <h2 class="page-title">Daftar Katalog Buku</h2>

    <div class="buku-grid">
        @foreach($buku as $item)
            <x-buku-card
                :id="$item['id']"
                :judul="$item['judul']"
                :penulis="$item['penulis']"
                :tahun="$item['tahun_terbit']"
            >
                Kategori: {{ $item['kategori'] }}
            </x-buku-card>
        @endforeach
    </div>
@endsection