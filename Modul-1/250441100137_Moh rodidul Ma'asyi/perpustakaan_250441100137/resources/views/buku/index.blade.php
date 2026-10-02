@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')
    <h2 class="page-title">Daftar Buku</h2>

    <div class="grid">
        @foreach ($daftarBuku as $item)
            <x-kartu-buku :buku="$item">
                <x-slot:badge>{{ $item['kategori'] }}</x-slot:badge>
                ID Buku: {{ $item['id'] }}
            </x-kartu-buku>
        @endforeach
    </div>
@endsection
