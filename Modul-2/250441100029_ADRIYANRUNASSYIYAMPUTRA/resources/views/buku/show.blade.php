@extends('layouts.app')

@section('title', $buku->judul)

@section('content')
    <div class="detail">
        <span class="badge">{{ $buku->kategori->nama }}</span>
        <h2>{{ $buku->judul }}</h2>

        <table>
            <tr><th>ID Buku</th><td>{{ $buku->id }}</td></tr>
            <tr><th>Judul</th><td>{{ $buku->judul }}</td></tr>
            <tr><th>Penulis</th><td>{{ $buku->penulis }}</td></tr>
            <tr><th>Tahun Terbit</th><td>{{ $buku->tahun_terbit }}</td></tr>
            <tr><th>Kategori</th><td>{{ $buku->kategori->nama }}</td></tr>
        </table>

        <a href="{{ route('buku.index') }}" class="btn btn-sm">← Kembali ke Daftar Buku</a>
    </div>
@endsection