@extends('layouts.app')
@section('title', 'Detail Buku')
@section('content')

    <div class="page-header">
        <h2>Detail Buku</h2>
    </div>

    @if ($dataBuku)

        <div class="book-detail">

            <h2>{{ $dataBuku['judul'] }}</h2>

            <div class="detail-item">
                <strong>ID Buku</strong>
                <span>: {{ $dataBuku['id'] }}</span>
            </div>

            <div class="detail-item">
                <strong>Penulis</strong>
                <span>: {{ $dataBuku['penulis'] }}</span>
            </div>

            <div class="detail-item">
                <strong>Tahun Terbit</strong>
                <span>: {{ $dataBuku['tahun'] }}</span>
            </div>

            <div class="detail-item">
                <strong>Kategori</strong>
                <span>: {{ $dataBuku['kategori'] }}</span>
            </div>

            <a href="{{ route('buku.index') }}" class="btn">
                Kembali ke Daftar Buku
            </a>

        </div>

    @else

        <div class="not-found">
            <h2>Buku Tidak Ditemukan</h2>

            <p>
                Maaf, data buku yang kamu cari tidak tersedia.
            </p>

            <a href="{{ route('buku.index') }}" class="btn">
                Kembali ke Daftar Buku
            </a>
        </div>

    @endif
@endsection