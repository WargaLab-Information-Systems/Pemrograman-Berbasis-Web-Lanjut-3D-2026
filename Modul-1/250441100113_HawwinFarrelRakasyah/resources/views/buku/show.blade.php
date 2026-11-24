@extends('layouts.main')

@section('content')
    <h2 class="page-title">Detail Informasi Buku</h2>

    @if($buku)
        <div class="detail-box">
            <table>
                <tr>
                    <td width="30%"><strong>ID Buku</strong></td>
                    <td>: {{ $buku['id'] }}</td>
                </tr>
                <tr>
                    <td><strong>Judul Buku</strong></td>
                    <td>: {{ $buku['judul'] }}</td>
                </tr>
                <tr>
                    <td><strong>Penulis</strong></td>
                    <td>: {{ $buku['penulis'] }}</td>
                </tr>
                <tr>
                    <td><strong>Tahun Terbit</strong></td>
                    <td>: {{ $buku['tahun_terbit'] }}</td>
                </tr>
                <tr>
                    <td><strong>Kategori</strong></td>
                    <td>: {{ $buku['kategori'] }}</td>
                </tr>
            </table>

            <a href="{{ route('buku.index') }}" class="btn-kembali">&larr; Kembali ke Daftar Buku</a>
        </div>
    @else
        <div class="alert-error">
            <p><strong>Perhatian:</strong> Data buku dengan ID tersebut tidak ditemukan!</p>
            <br>
            <a href="{{ route('buku.index') }}" class="btn-kembali">&larr; Kembali ke Daftar Buku</a>
        </div>
    @endif
@endsection