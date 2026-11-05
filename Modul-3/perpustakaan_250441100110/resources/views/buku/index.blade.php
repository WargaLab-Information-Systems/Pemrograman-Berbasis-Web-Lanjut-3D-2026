@extends('layouts.app')

@section('judul', 'Daftar Buku')

@section('konten')

    <h2>Daftar Buku</h2>

    <div class="daftar-buku">

        @foreach ($buku as $item)

            <x-kartu-buku
                :judul="$item['judul']"
                :penulis="$item['penulis']"
                :tahun="$item['tahun']"
            >

                <a href="{{ route('buku.detail', $item['id']) }}">
                    Lihat Detail
                </a>

            </x-kartu-buku>

        @endforeach

    </div>

@endsection