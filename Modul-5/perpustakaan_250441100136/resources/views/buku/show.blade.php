@extends('layouts.main')

@section('title', isset($buku) ? $buku['judul'] : 'Buku Tidak Ditemukan')

@section('content')
<div class="detail-container">
    @if($buku)
        <a href="{{ route('buku.index') }}" class="btn-back">&larr; Kembali ke Daftar Buku</a>
        
        <div class="detail-card">
            <div class="detail-header">
                <span class="badge">{{ $buku->kategori->nama ?? $buku['kategori'] ?? 'Umum' }}</span>
                <h2>{{ $buku['judul'] }}</h2>
            </div>
            
            <div class="detail-body">
                <table class="table-detail">
                    <tr>
                        <th>ID Buku</th>
                        <td>: BK-{{ str_pad($buku['id'], 3, '0', STR_PAD_LEFT) }}</td>
                    </tr>
                    <tr>
                        <th>Penulis</th>
                        <td>: {{ $buku['penulis'] }}</td>
                    </tr>
                    <tr>
                        <th>Tahun Terbit</th>
                        <td>: {{ $buku['tahun_terbit'] }}</td>
                    </tr>
                    <tr>
                        <th>Kategori</th>
                        <td>: {{ $buku->kategori->nama ?? $buku['kategori'] ?? 'Umum' }}</td>
                    </tr>
                    <tr>
                        <th>Deskripsi</th>
                        <td>: {{ $buku['deskripsi'] ?? 'Tidak ada deskripsi.' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    @else
        <div class="alert alert-danger">
            <h2>⚠️ Data Buku Tidak Ditemukan!</h2>
            <p>Maaf, buku dengan ID yang Anda cari tidak terdaftar dalam sistem perpustakaan.</p>
            <a href="{{ route('buku.index') }}" class="btn btn-primary" style="margin-top: 15px; display: inline-block;">Kembali ke Daftar Buku</a>
        </div>
    @endif
</div>
@endsection










