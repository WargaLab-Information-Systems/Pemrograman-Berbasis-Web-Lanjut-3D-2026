@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')
    <h2>Koleksi Buku Kami</h2>
    
    <div class="book-grid">
        @foreach($buku as $item)
            
            <x-kartu-buku 
                judul="{{ $item['judul'] }}" 
                penulis="{{ $item['penulis'] }}" 
                tahun="{{ $item['tahun_terbit'] }}"
                detailUrl="{{ route('buku.show', $item['id']) }}"
            >
                <span style="font-size: 12px; color: gray;">Kategori: {{ $item['kategori'] }}</span>
            </x-kartu-buku>

        @endforeach
    </div>
@endsection