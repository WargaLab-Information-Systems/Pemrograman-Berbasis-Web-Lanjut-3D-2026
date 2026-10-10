@extends('layouts.app')

@section('judul', 'Daftar Buku')

@section('konten')
    <div class="halaman-judul">
        <h2>Daftar Buku</h2>
        <p>{{ count($daftarBuku) }} buku</p>
    </div>

    <div class="daftar-buku">
        @foreach ($daftarBuku as $buku)
            <x-kartu-buku
                :judul="$buku['judul']"
                :penulis="$buku['penulis']"
                :tahun="$buku['tahun_terbit']"
                :kategori="$buku['kategori']"
                :url="route('buku.detail', $buku['id'])"
            />
        @endforeach
    </div>
@endsection