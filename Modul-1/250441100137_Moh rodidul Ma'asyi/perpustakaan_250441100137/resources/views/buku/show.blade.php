@extends('layouts.app')

@section('title', 'Detail Buku')

@section('content')
    <h2 class="page-title">Detail Buku</h2>

    @if ($buku)
        <div class="detail">
            <h3>{{ $buku['judul'] }}</h3>
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
            <p>Buku dengan ID <strong>{{ $id }}</strong> tidak ditemukan.</p>
            <a href="{{ route('buku.index') }}" class="btn">&larr; Kembali ke Daftar Buku</a>
        </div>
    @endif
@endsection
