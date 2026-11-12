@extends('layouts.app')

@section('title', 'Detail Buku')

@section('content')

    <h2>Detail Buku</h2>

    @if ($dataBuku)

        <div class="detail-buku">

            <h2>{{ $dataBuku['judul'] }}</h2>

            <p>
                <strong>ID Buku:</strong>
                {{ $dataBuku['id'] }}
            </p>

            <p>
                <strong>Penulis:</strong>
                {{ $dataBuku['penulis'] }}
            </p>

            <p>
                <strong>Tahun Terbit:</strong>
                {{ $dataBuku['tahun_terbit'] }}
            </p>

            <p>
                <strong>Kategori:</strong>
                {{ $dataBuku['kategori'] }}
            </p>

            <a class="detail-button" href="{{ route('buku.index') }}">
                Kembali ke Daftar Buku
            </a>

        </div>

    @else

        <div class="detail-buku">

            <h2>Buku Tidak Ditemukan</h2>

            <p>
                Data buku dengan ID tersebut tidak tersedia.
            </p>

            <a class="detail-button" href="{{ route('buku.index') }}">
                Kembali ke Daftar Buku
            </a>

        </div>

    @endif

@endsection