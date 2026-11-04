@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')
    <h1>Daftar Buku</h1>
    <p class="subjudul">{{ count($daftarBuku) }} buku tersedia di katalog.</p>

    <div class="grid-buku">
        @foreach ($daftarBuku as $buku)
            <x-kartu-buku :buku="$buku">
                <x-slot:kategori>{{ $buku['kategori'] }}</x-slot:kategori>
            </x-kartu-buku>
        @endforeach
    </div>
@endsection
