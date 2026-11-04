@extends('layouts.app')

@section('title', $buku ? $buku['judul'] : 'Buku tidak ditemukan')

@section('content')
    @if ($buku)
        <article class="detail-buku">
            <span class="badge">{{ $buku['kategori'] }}</span>
            <h1>{{ $buku['judul'] }}</h1>

            <dl class="detail-list">
                <dt>ID Buku</dt>      <dd>{{ $buku['id'] }}</dd>
                <dt>Penulis</dt>      <dd>{{ $buku['penulis'] }}</dd>
                <dt>Tahun terbit</dt> <dd>{{ $buku['tahun_terbit'] }}</dd>
                <dt>Kategori</dt>     <dd>{{ $buku['kategori'] }}</dd>
            </dl>

            <a href="{{ route('buku.index') }}" class="btn">Kembali ke daftar buku</a>
        </article>
    @else
        <div class="tidak-ditemukan">
            <h1>Buku tidak ditemukan</h1>
            <p>Buku dengan ID tersebut tidak ada di katalog. Periksa kembali alamatnya atau pilih buku dari daftar.</p>
            <a href="{{ route('buku.index') }}" class="btn">Lihat daftar buku</a>
        </div>
    @endif
@endsection
m