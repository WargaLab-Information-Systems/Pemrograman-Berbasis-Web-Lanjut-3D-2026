@extends('layouts.app')

@section('judul', 'Detail Buku')

@section('konten')
    @if ($buku)
        <article class="detail-buku">
            <a class="detail-kembali" href="{{ route('buku.index') }}">Kembali ke daftar buku</a>
            <p class="detail-kategori">{{ $buku['kategori'] }}</p>
            <h2>{{ $buku['judul'] }}</h2>
            <p class="detail-penulis">Penulis: {{ $buku['penulis'] }}</p>

            <dl class="detail-meta">
                <div>
                    <dt>Tahun terbit</dt>
                    <dd>{{ $buku['tahun_terbit'] }}</dd>
                </div>
            </dl>
        </article>
    @else
        <section class="detail-tidak-ditemukan">
            <h2>Buku Tidak Ditemukan</h2>
            <p>Data buku dengan ID tersebut tidak tersedia.</p>
            <a class="detail-kembali" href="{{ route('buku.index') }}">Kembali ke daftar buku</a>
        </section>
    @endif
@endsection