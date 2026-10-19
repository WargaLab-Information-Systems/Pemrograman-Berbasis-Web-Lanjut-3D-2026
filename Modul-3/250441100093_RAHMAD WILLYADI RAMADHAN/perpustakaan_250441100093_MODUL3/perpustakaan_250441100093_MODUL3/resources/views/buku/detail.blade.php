@extends('layouts.app')

@section('konten')
    @if ($detailBuku)
        <h2>Detail Buku: {{ $detailBuku['judul'] }}</h2>
        <ul>
            <li>ID Buku: {{ $detailBuku['id'] }}</li>
            <li>Judul: {{ $detailBuku['judul'] }}</li>
            <li>Penulis: {{ $detailBuku['penulis'] }}</li>
            <li>Tahun Terbit: {{ $detailBuku['tahun'] }}</li>
            <li>Kategori: {{ $detailBuku['kategori'] }}</li>
        </ul>
        <a href="{{ route('buku.index') }}" class="btn">Kembali ke Daftar</a>
    @else
        <div style="background: #ffcccc; padding: 15px;">
            <h2 style="color: red;">Error: Buku tidak ditemukan!</h2>
            <a href="{{ route('buku.index') }}" class="btn">Kembali</a>
        </div>
    @endif
@endsection