@extends('layouts.app')

@section('title', $buku ? $buku['judul'] . ' - Detail Buku' : 'Buku Tidak Ditemukan')

@section('content')
    {{-- Tampilkan detail buku berdasarkan {id}, tangani kondisi data tidak ditemukan --}}
    @if ($buku)
        <div class="detail-buku">
            <h2>{{ $buku['judul'] }}</h2>
            <p><strong>Penulis:</strong> {{ $buku['penulis'] }}</p>
            <p><strong>Tahun Terbit:</strong> {{ $buku['tahun'] }}</p>
            <p><strong>Kategori:</strong> {{ $buku['kategori'] }}</p>

            <div class="form-actions">
                <a href="{{ route('buku.index') }}" class="btn btn-secondary">&larr; Kembali ke Daftar Buku</a>

                {{-- Modul 5: tombol hanya tampil untuk yang punya permission --}}
                @can('mengajukan peminjaman buku')
                    <form method="POST" action="{{ route('peminjaman.ajukan', $buku['id']) }}" class="inline-form">
                        @csrf
                        <button type="submit" class="btn btn-primary">Ajukan Peminjaman</button>
                    </form>
                @endcan
            </div>
        </div>
    @else
        <div class="alert alert-danger">
            <p>Maaf, data buku yang Anda cari tidak ditemukan.</p>
            <a href="{{ route('buku.index') }}" class="btn btn-secondary">&larr; Kembali ke Daftar Buku</a>
        </div>
    @endif
@endsection
