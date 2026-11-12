@extends('layouts.app')

@section('konten')
    <h2>Daftar Koleksi Buku</h2>
    
    @foreach ($buku as $item)
        
        <x-kartu-buku 
            :judul="$item['judul']" 
            :penulis="$item['penulis']" 
            :tahun="$item['tahun']"
            :linkDetail="route('buku.show', $item['id'])">
            
            Kategori: {{ $item['kategori'] }}
            
        </x-kartu-buku>

    @endforeach
@endsection