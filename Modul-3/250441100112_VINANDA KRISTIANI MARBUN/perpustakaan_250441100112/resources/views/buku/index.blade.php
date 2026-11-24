@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')

    <div class="page-title">

        <h2> Daftar Buku</h2>

        <p>
            Berikut adalah koleksi buku yang tersedia
            di perpustakaan.
        </p>

    </div>


    <div class="book-grid">

        @foreach ($buku as $item)

            <x-kartu-buku :buku="$item">

                <div class="slot-content">
                    <small>
                        ID Buku: {{ $item['id'] }}
                    </small>
                </div>

            </x-kartu-buku>

        @endforeach

    </div>

@endsection