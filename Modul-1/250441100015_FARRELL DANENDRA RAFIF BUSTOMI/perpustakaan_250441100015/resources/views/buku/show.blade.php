@extends('layouts.app')
@section('title', $book ? $book['judul'] : 'Buku Tidak Ditemukan')
@section('content')
<div class="detail-page">
    @if ($book)
        <div class="detail-card" data-aos="fade-up" data-aos-duration="900" data-aos-delay="150">
            <span class="book-category">
                {{ $book['kategori'] }}
            </span>
            <h1>{{ $book['judul'] }}</h1>
            <div class="detail-info">
                <p>
                    <strong>Penulis</strong>
                    <span>{{ $book['penulis'] }}</span>
                </p>
                <p>
                    <strong>Tahun Terbit</strong>
                    <span>{{ $book['tahun'] }}</span>
                </p>
                <p>
                    <strong>Kategori</strong>
                    <span>{{ $book['kategori'] }}</span>
                </p>
                <p>
                    <strong>ID Buku</strong>
                    <span>{{ $book['id'] }}</span>
                </p>
            </div>
            <a href="{{ route('buku.index') }}" class="btn-detail">Kembali ke Daftar Buku</a>
        </div>
    @else
        <div class="not-found"  data-aos="zoom-in" data-aos-duration="800" data-aos-delay="150">
            <h1>Buku Tidak Ditemukan</h1>
            <p>Maaf, data buku dengan ID tersebut tidak tersedia.</p>
            <a href="{{ route('buku.index') }}" class="btn-detail">Kembali ke Daftar Buku</a>
        </div>
    @endif
</div>
@endsection