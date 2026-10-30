@extends('layouts.app')

@section('title', $buku ? $buku['judul'] : 'Buku Tidak Ditemukan')

@section('content')
    @if ($buku)
        <article class="detail">
            <span class="badge">{{ $buku['kategori'] }}</span>
            <h2 class="detail__title">{{ $buku['judul'] }}</h2>

            <table class="detail__table">
                <tr><th>ID Buku</th><td>{{ $buku['id'] }}</td></tr>
                <tr><th>Judul</th><td>{{ $buku['judul'] }}</td></tr>
                <tr><th>Penulis</th><td>{{ $buku['penulis'] }}</td></tr>
                <tr><th>Tahun Terbit</th><td>{{ $buku['tahun_terbit'] }}</td></tr>
                <tr><th>Kategori</th><td>{{ $buku['kategori'] }}</td></tr>
            </table>

            <a href="{{ route('buku.index') }}" class="btn">&larr; Kembali ke Daftar Buku</a>
        </article>
    @else
        <div class="not-found">
            <h2> Buku Tidak Ditemukan</h2>
            <p>Maaf, buku yang kamu cari tidak ada dalam koleksi kami.</p>
            <a href="{{ route('buku.index') }}" class="btn">&larr; Kembali ke Daftar Buku</a>
        </div>
    @endif
@endsection