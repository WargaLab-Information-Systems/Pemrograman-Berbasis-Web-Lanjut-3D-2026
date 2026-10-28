@extends('layouts.app')

@section('title', 'Detail Buku - Perpustakaan')

@section('content')

    @if ($buku)

        <div class="detail-card">

            <div class="detail-icon">
                📖
            </div>

            <div class="detail-info">

                <p class="hero-label">DETAIL BUKU</p>

                <h1>{{ $buku['judul'] }}</h1>

                <div class="detail-item">
                    <strong>Penulis</strong>
                    <span>{{ $buku['penulis'] }}</span>
                </div>

                <div class="detail-item">
                    <strong>Tahun Terbit</strong>
                    <span>{{ $buku['tahun'] }}</span>
                </div>

                <div class="detail-item">
                    <strong>Kategori</strong>
                    <span>{{ $buku['kategori'] }}</span>
                </div>

                <a href="{{ route('buku.index') }}" class="btn">
                    ← Kembali ke Daftar Buku
                </a>

            </div>

        </div>

    @else

        <div class="not-found">

            <div class="not-found-icon">
                📕
            </div>

            <h1>Buku Tidak Ditemukan</h1>

            <p>
                Maaf, buku dengan ID tersebut tidak tersedia
                di perpustakaan.
            </p>

            <a href="{{ route('buku.index') }}" class="btn">
                Kembali ke Daftar Buku
            </a>

        </div>

    @endif

@endsection