@extends('layouts.app')

@section('title', 'Daftar Buku — Perpustakaan Digital')

@section('content')

    <section style="padding: 2.5rem 0;" class="animate-fade-in-up">
        <div class="container-main">

            {{-- Page Heading --}}
            <div class="page-heading">
                <div class="page-heading__icon">
                    <i class="fa-solid fa-list-ul"></i>
                </div>
                <div>
                    <h2 class="page-heading__title">Daftar Buku</h2>
                    <p class="page-heading__subtitle">
                        Koleksi buku yang tersedia di perpustakaan kami.
                    </p>
                </div>
            </div>


            {{-- Daftar Buku menggunakan @foreach --}}
            <div class="buku-grid">

                @foreach ($books as $book)

                    <x-buku-card :book="$book">

                        {{-- Slot: Tombol Detail --}}
                        <a
                            id="btn-detail-{{ $book['id'] }}"
                            href="{{ route('buku.show', $book['id']) }}"
                            class="btn-primary"
                            style="width: 100%;"
                        >
                            <i class="fa-solid fa-eye"></i>
                            Lihat Detail
                        </a>

                    </x-buku-card>

                @endforeach

            </div>

        </div>
    </section>

@endsection