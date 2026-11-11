@extends('layouts.main')

@section('title', 'Detail Buku')

@section('content')
    @if ($buku)
        <div class="detail">
            <h2>{{ $buku['judul'] }}</h2>

            <table>
                <tr><th>ID Buku</th><td>{{ $buku['id'] }}</td></tr>
                <tr><th>Judul</th><td>{{ $buku['judul'] }}</td></tr>
                <tr><th>Penulis</th><td>{{ $buku['penulis'] }}</td></tr>
                <tr><th>Tahun Terbit</th><td>{{ $buku['tahun_terbit'] }}</td></tr>
                <tr><th>Kategori</th><td>{{ $buku['kategori'] }}</td></tr>
            </table>

            @can('mengajukan peminjaman buku')
                @if ($sudahAktif)
                    <p><em>Anda sudah mengajukan/meminjam buku ini.</em></p>
                @else
                    <form method="POST" action="{{ route('peminjaman.ajukan', $buku['id']) }}" style="margin:15px 0;">
                        @csrf
                        <button type="submit" class="tombol">Ajukan Peminjaman</button>
                    </form>
                @endif
            @endcan

            <a href="{{ route('buku.index') }}" class="tombol tombol-abu">&larr; Kembali ke Daftar Buku</a>
        </div>
    @else
        <div class="alert">
            <h2>Buku tidak ditemukan </h2>
            <p>Buku dengan ID "{{ $id }}" tidak ada di koleksi kami.</p>
            <a href="{{ route('buku.index') }}" class="btn">Kembali ke Daftar Buku</a>
        </div>
    @endif
@endsection