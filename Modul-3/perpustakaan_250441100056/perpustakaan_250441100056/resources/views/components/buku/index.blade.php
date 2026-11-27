@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')

<section class="page-header">
    <div class="container">

        <h1>DAFTAR BUKU</h1>

        <p>
            Temukan koleksi buku yang tersedia di perpustakaan ini.
    Pilih berbagai buku menarik untuk menemani belajar dan menambah wawasanmu.
        </p>

    </div>
</section>

<section class="book-section">
    <div class="container">

        <div class="book-grid">

            @foreach ($buku as $item)

                <x-buku-card :buku="$item">
                    <small></small>
                </x-buku-card>

            @endforeach

        </div>

    </div>
</section>

@endsection