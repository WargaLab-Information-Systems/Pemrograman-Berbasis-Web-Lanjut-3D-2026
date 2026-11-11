@extends('layouts.main')

@section('title', 'Daftar Buku')

@section('content')
    <h2 class="page-title">Daftar Buku</h2>

    <div class="grid">
        @foreach ($buku as $item)
            <x-buku-card :buku="$item">
                <x-slot:badge>{{ $item->kategori->nama }}</x-slot:badge>
            </x-buku-card>
        @endforeach
    </div>
@endsection