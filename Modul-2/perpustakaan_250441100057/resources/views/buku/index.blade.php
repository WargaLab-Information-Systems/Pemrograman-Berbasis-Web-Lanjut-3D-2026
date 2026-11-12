@extends('layouts.app')

@section('judul', 'Daftar Buku')

@section('konten')

    <div class="judul-halaman">

        <p class="label">KOLEKSI PERPUSTAKAAN</p>

        <h2>Daftar Buku</h2>

        <p>
            Berikut adalah buku yang tersedia di perpustakaan.
        </p>

    </div>

    <div class="daftar-buku">

        @foreach ($buku as $item)

            <x-buku-card :buku="$item">

                <x-slot:footer>
                    <a class="tombol" href="{{ route('buku.show', $item['id']) }}">
                        Lihat Detail
                    </a>
                </x-slot:footer>

            </x-buku-card>

        @endforeach

    </div>

@endsection