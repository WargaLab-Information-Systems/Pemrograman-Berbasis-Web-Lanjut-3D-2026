@extends('layouts.app')

@section('title', 'Detail Buku')

@section('content')

<style>
    .book-detail {
        width: 100%;
        max-width: 500px;
        margin: 20px auto 60px;
        padding: 35px;
        background-color: white;
        border-radius: 12px;
        border: 1px solid #eadede;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        box-sizing: border-box;
        text-align: left;
    }

    .book-detail h2 {
        color: #800000;
        text-align: center;
        font-size: 24px;
        line-height: 1.4;
        margin: 0 0 25px;
    }

    .book-detail p {
        padding: 13px 0;
        margin: 0;
        border-bottom: 1px solid #eeeeee;
        color: #555;
        font-size: 14px;
        line-height: 1.7;
        text-align: left;
    }

    .book-detail p strong {
        color: #333;
    }

    .book-detail .btn {
        display: block;
        width: fit-content;
        max-width: 100%;
        margin: 25px auto 0;
        padding: 11px 22px;
        background-color: #800000;
        color: white;
        border-radius: 6px;
        text-decoration: none;
        font-size: 14px;
        font-weight: bold;
        text-align: center;
    }

    .book-detail .btn:hover {
        background-color: #5c0000;
    }

    @media (max-width: 600px) {
        .book-detail {
            padding: 25px 20px;
            margin: 15px auto 35px;
        }

        .book-detail h2 {
            font-size: 22px;
        }

        .book-detail .btn {
            font-size: 13px;
            padding: 10px 15px;
        }
    }
</style>

<div class="book-detail">

    @if ($buku)

        <h2>{{ $buku->judul }}</h2>

        <p>
            <strong>ID Buku:</strong>
            {{ $buku->id }}
        </p>

        <p>
            <strong>Penulis:</strong>
            {{ $buku->penulis }}
        </p>

        <p>
            <strong>Tahun Terbit:</strong>
            {{ $buku->tahun_terbit }}
        </p>

        <p>
            <strong>Kategori:</strong>
            {{ $buku->kategori?->nama ?? '-' }}
        </p>

    @else

        <h2>Buku Tidak Ditemukan</h2>

        <p>
            Maaf, buku yang Anda cari tidak tersedia.
        </p>

    @endif

    <a href="{{ url('/buku') }}" class="btn">
        Kembali ke Daftar Buku
    </a>

</div>

@endsection