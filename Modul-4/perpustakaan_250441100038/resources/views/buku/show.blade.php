@extends('layouts.main')

@section('title', 'Detail Buku')

@section('content')
    @if ($buku)
        <div class="detail">
            <h2>{{ $buku['judul'] }}</h2>

            <table>
                <tr><th>ID Buku</th><td>{{ $buku['id'] }}</td></tr>
                <tr><th>Judul</th><td>{{ $buku['judul'] }}</td></tr>
                <tr><th>Penulis</th><td>{{ $buku['penulis'] }}</td></tr>
                <tr><th>Tahun Terbit</th><td>{{ $buku['tahun_terbit'] }}</td></tr>
                <tr><th>Kategori</th><td>{{ $buku['kategori'] }}</td></tr>
            </table>

            <a href="{{ route('buku.index') }}" class="btn">&larr; Kembali ke Daftar Buku</a>
        </div>
    @else
        <div class="alert">
            <h2>Buku tidak ditemukan </h2>
            <p>Buku dengan ID "{{ $id }}" tidak ada di koleksi kami.</p>
            <a href="{{ route('buku.index') }}" class="btn">Kembali ke Daftar Buku</a>
        </div>
    @endif
@endsection