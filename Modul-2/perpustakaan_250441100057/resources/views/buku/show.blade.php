@extends('layouts.app')

@section('judul', 'Detail Buku')

@section('konten')

    @if ($dataBuku)

        <div class="detail-buku">

            <p class="label">INFORMASI BUKU</p>

            <h2>{{ $dataBuku['judul'] }}</h2>

            <div class="detail-isi">

                <p>
                    <strong>Penulis</strong>
                    <br>
                    {{ $dataBuku['penulis'] }}
                </p>

                <p>
                    <strong>Tahun Terbit</strong>
                    <br>
                    {{ $dataBuku['tahun'] }}
                </p>

                <p>
                    <strong>Kategori</strong>
                    <br>
                    {{ $dataBuku['kategori'] }}
                </p>

            </div>

            <a class="tombol" href="{{ route('buku.index') }}">
                Kembali ke Daftar Buku
            </a>

        </div>

    @else

        <div class="detail-buku">

            <p class="label">INFORMASI BUKU</p>

            <h2>Buku Tidak Ditemukan</h2>

            <p>
                Data buku yang dicari tidak tersedia.
            </p>

            <a class="tombol" href="{{ route('buku.index') }}">
                Kembali ke Daftar Buku
            </a>

        </div>

    @endif

@endsection