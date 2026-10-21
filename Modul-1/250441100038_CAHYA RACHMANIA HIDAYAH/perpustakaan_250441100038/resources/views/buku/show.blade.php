@extends('layouts.master')

@section('title', 'Detail Buku')

@section('content')
    <div>
        <h2 style="color: #1e3c72; margin-bottom: 5px;">Detail Informasi Buku</h2>
        <p style="color: #666; margin-top: 0; margin-bottom: 25px;">Informasi lengkap mengenai buku yang dipilih.</p>

        @if ($book)
            <div class="detail-card">
                <h3>{{ $book['judul'] }}</h3>
                <p><strong>ID Buku:</strong> {{ $book['id'] }}</p>
                <p><strong>Penulis:</strong> {{ $book['penulis'] }}</p>
                <p><strong>Tahun Terbit:</strong> {{ $book['tahun_terbit'] }}</p>
                <p><strong>Kategori:</strong> {{ $book['kategori'] }}</p>
                
                <a href="{{ route('buku.index') }}" class="btn-back">Kembali ke Daftar Buku</a>
            </div>
        @else
            <div class="alert-error">
                <p style="margin: 0;"><strong>Peringatan!</strong> Data buku dengan ID tersebut tidak ditemukan di dalam sistem.</p>
            </div>
            <a href="{{ route('buku.index') }}" class="btn-back">Kembali ke Daftar Buku</a>
        @endif
    </div>
@endsection