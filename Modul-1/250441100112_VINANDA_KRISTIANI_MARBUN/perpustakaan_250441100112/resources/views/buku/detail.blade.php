@extends('layouts.app')

@section('title', 'Detail Buku')

@section('content')

    @if ($dataBuku)

        <div class="detail-card">

            <div class="detail-icon">
                📖
            </div>

            <div class="detail-content">

                <span class="category">
                    {{ $dataBuku['kategori'] }}
                </span>

                <h2>{{ $dataBuku['judul'] }}</h2>

                <div class="detail-info">

                    <p>
                        <strong>ID Buku</strong>
                        {{ $dataBuku['id'] }}
                    </p>

                    <p>
                        <strong>Penulis</strong>
                        {{ $dataBuku['penulis'] }}
                    </p>

                    <p>
                        <strong>Tahun Terbit</strong>
                        {{ $dataBuku['tahun'] }}
                    </p>

                    <p>
                        <strong>Kategori</strong>
                        {{ $dataBuku['kategori'] }}
                    </p>

                </div>

                <a href="{{ route('buku.index') }}" class="btn">
                    ← Kembali ke Daftar Buku
                </a>

            </div>

        </div>

    @else

        <div class="not-found">

            <h2>📕 Buku Tidak Ditemukan</h2>

            <p>
                Maaf, data buku dengan ID tersebut tidak tersedia.
            </p>

            <a href="{{ route('buku.index') }}" class="btn">
                Kembali ke Daftar Buku
            </a>

        </div>

    @endif

@endsection