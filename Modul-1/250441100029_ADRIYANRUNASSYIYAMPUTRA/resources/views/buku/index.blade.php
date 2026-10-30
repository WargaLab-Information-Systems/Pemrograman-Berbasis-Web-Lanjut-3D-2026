@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')
    <h2 class="page-title">Daftar Buku</h2>

    <div class="card-grid">
        @foreach ($daftarBuku as $buku)
            <x-kartu-buku
                :judul="$buku['judul']"
                :penulis="$buku['penulis']"
                :tahun="$buku['tahun_terbit']"
                :url="route('buku.show', $buku['id'])">

                <x-slot:kategori>{{ $buku['kategori'] }}</x-slot:kategori>

            </x-kartu-buku>
        @endforeach
    </div>
@endsection