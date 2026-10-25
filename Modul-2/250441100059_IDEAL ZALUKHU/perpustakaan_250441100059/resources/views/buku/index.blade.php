@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')

    <h2>Daftar Buku</h2>

    <div class="buku-grid">
        @foreach ($buku as $item)
            <x-buku-card
                :id="$item['id']"
                :judul="$item['judul']"
                :penulis="$item['penulis']"
                :tahun="$item['tahun_terbit']"
            >
                <p>
                    <strong>Kategori:</strong>
                    {{ $item['kategori'] }}
                </p>
            </x-buku-card>
        @endforeach
    </div>

@endsection