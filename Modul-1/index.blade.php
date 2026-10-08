@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')

    <div class="page-header">
        <h2>Daftar Buku</h2>

        <p>
            Berikut adalah koleksi buku yang tersedia.
        </p>
    </div>

    <div class="book-grid">

        @foreach ($databuku as $buku)

            <x-kartu-buku :buku="$buku">

                <x-slot:footer>

                    <a href="{{ route('buku.show', $buku['id_buku']) }}"
                       class="btn">
                        Lihat Detail
                    </a>

                </x-slot:footer>

            </x-kartu-buku>

        @endforeach

    </div>

@endsection