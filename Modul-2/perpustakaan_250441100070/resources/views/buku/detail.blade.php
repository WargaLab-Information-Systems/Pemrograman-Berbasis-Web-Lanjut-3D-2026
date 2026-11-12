@extends('layouts.app')

@section('title', $buku ? $buku['judul'] . ' - Detail Buku' : 'Buku Tidak Ditemukan')

@section('content')

    @if ($buku)
        <div class="detail-buku">
            <h2>{{ $buku['judul'] }}</h2>
            <p><strong>Penulis:</strong> {{ $buku['penulis'] }}</p>
            <p><strong>Tahun Terbit:</strong> {{ $buku['tahun'] }}</p>
            <p><strong>Kategori:</strong> {{ $buku['kategori'] }}</p>

            <a href="{{ route('buku.index') }}" class="btn btn-secondary">&larr; Kembali ke Daftar Buku</a>
        </div>
    @else
        <div class="alert alert-danger">
            <p>Maaf, data buku yang Anda cari tidak ditemukan.</p>
            <a href="{{ route('buku.index') }}" class="btn btn-secondary">&larr; Kembali ke Daftar Buku</a>
        </div>
    @endif
@endsection
