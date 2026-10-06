{{-- 8. @extends, @section pada halaman Daftar Buku --}}
@extends('layouts.app')

@section('title', 'Daftar Buku - Perpustakaan Digital')

@section('content')
    <h2 class="page-title">Daftar Buku</h2>

    <div class="buku-grid">
        @foreach ($daftarBuku as $b)
            <x-buku-card :judul="$b['judul']" :penulis="$b['penulis']" :tahun="$b['tahun']" :id="$b['id']">
                <x-slot:kategori>
                    {{ $b['kategori'] }}
                </x-slot:kategori>
            </x-buku-card>
        @endforeach
    </div>
@endsection