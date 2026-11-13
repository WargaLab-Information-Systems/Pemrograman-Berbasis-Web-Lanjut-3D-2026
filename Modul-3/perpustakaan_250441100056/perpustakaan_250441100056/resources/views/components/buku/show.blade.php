@extends('layouts.app')

@section('title', 'Detail Buku')

@section('content')

<section class="detail-section">
    <div class="container">

    @if ($buku)

        <div class="detail-card">

            <div class="detail-info">

                <span class="book-category">
                    {{ $buku->kategori->nama }}
                </span>

                <h1>{{ $buku->judul }}</h1>

                <p class="detail-description">
                    Informasi lengkap mengenai buku yang
                    dipilih dari koleksi perpustakaan.
                </p>

                <div class="info-list">

                    <div class="info-item">
                        <span>Penulis</span>
                        <strong>{{ $buku->penulis }}</strong>
                    </div>

                    <div class="info-item">
                        <span>Tahun Terbit</span>
                        <strong>{{ $buku->tahun_terbit }}</strong>
                    </div>

                    <div class="info-item">
                        <span>Kategori</span>
                        <strong>{{ $buku->kategori->nama }}</strong>
                    </div>

                    <div class="info-item">
                        <span>ID Buku</span>
                        <strong>{{ $buku->id }}</strong>
                    </div>

                </div>

                <a href="{{ route('components.buku.index') }}" class="btn">
                    Kembali ke Daftar Buku
                </a>

            </div>

        </div>

    @else

        <div class="not-found">

            <div class="not-found-icon">
                🔍
            </div>

            <h1>Buku Tidak Ditemukan</h1>

            <p>
                Maaf, data buku yang kamu cari tidak tersedia.
            </p>

            <a href="{{ route('components.buku.index') }}" class="btn">
                Kembali ke Daftar Buku
            </a>

        </div>

    @endif

</div>

</section>

@endsection
