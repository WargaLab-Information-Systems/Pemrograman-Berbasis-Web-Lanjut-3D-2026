@extends('layouts.master')

@section('title', 'Daftar Buku')

@section('content')
    <div>
        <h2 style="color: #1e3c72; margin-bottom: 5px;">Daftar Koleksi Buku</h2>
        <p style="color: #666; margin-top: 0;">Berikut adalah daftar lengkap buku perpustakaan saat ini.</p>
        
        <div class="book-grid">
            @foreach ($books as $book)
                <x-kartu-buku 
                    :id="$book['id']" 
                    :judul="$book['judul']" 
                    :penulis="$book['penulis']" 
                    :tahun="$book['tahun_terbit']"
                >
                    Kategori: {{ $book['kategori'] }}
                </x-kartu-buku>
            @endforeach
        </div>
    </div>
@endsection