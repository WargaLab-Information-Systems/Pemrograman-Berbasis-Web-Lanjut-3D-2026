@extends('layouts.app')
<!-- 14: Kondisi if jika data buku tidak ditemukan -->
@section('content')
    @if(!$buku)
        <div class="alert-error">
            <h3>Data Buku Tidak Ditemukan!</h3>
            <p>Maaf, buku dengan ID tersebut tidak ada dalam sistem kami.</p>
            <a href="{{ route('buku.index') }}" class="btn">Kembali ke Daftar Buku</a>
        </div>
    @else
        <!-- Poin 13: Menampilkan detail data buku berdasarkan {id} -->
        <div style="background: white; padding: 20px; border-radius: 8px; max-width: 600px;">
            <h2>{{ $buku['judul'] }}</h2>
            <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 15px 0;">
            <p><strong>ID Buku:</strong> {{ $buku['id'] }}</p>
            <p><strong>Penulis:</strong> {{ $buku['penulis'] }}</p>
            <p><strong>Tahun Terbit:</strong> {{ $buku['tahun_terbit'] }}</p>
            <p><strong>Kategori:</strong> <span class="badge">{{ $buku['kategori'] }}</span></p>
            
            <div style="margin-top: 20px;">
                <a href="{{ route('buku.index') }}" class="btn">&larr; Kembali ke Daftar Buku</a>
            </div>
        </div>
    @endif
@endsection