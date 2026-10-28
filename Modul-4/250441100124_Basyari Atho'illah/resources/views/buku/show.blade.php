@extends('layouts.app')

@section('title', $book ? $book['judul'] . ' — Perpustakaan Digital' : 'Buku Tidak Ditemukan')

@section('content')

    <section style="padding: 2.5rem 0;" class="animate-fade-in-up">
        <div class="container-main" style="max-width: 56rem;">

            @if ($book)

                {{-- Breadcrumb --}}
                <nav class="breadcrumb" aria-label="Breadcrumb">

                    <a href="{{ route('home') }}" class="breadcrumb__link">
                        Beranda
                    </a>

                    <i class="fa-solid fa-chevron-right breadcrumb__separator"></i>

                    <a href="{{ route('buku.index') }}" class="breadcrumb__link">
                        Daftar Buku
                    </a>

                    <i class="fa-solid fa-chevron-right breadcrumb__separator"></i>

                    <span class="breadcrumb__current">
                        Detail Buku
                    </span>

                </nav>


                {{-- Detail Card --}}
                <div class="detail-card">

                    {{-- Header --}}
                    <div class="detail-card__header">
                        <div class="detail-card__header-inner">

                            <div class="detail-card__book-icon">
                                <i class="fa-solid fa-book-open"></i>
                            </div>

                            <div>
                                <span class="detail-card__category-badge">
                                    {{ $book['kategori'] }}
                                </span>

                                <h1 class="detail-card__title">
                                    {{ $book['judul'] }}
                                </h1>

                                <p class="detail-card__subtitle">
                                    Detail informasi buku
                                </p>
                            </div>

                        </div>
                    </div>


                    {{-- Body --}}
                    <div class="detail-card__body">

                        <div class="detail-info-grid">

                            {{-- ID Buku --}}
                            <div class="detail-info-item">
                                <div class="detail-info-item__inner">
                                    <div class="detail-info-item__icon">
                                        <i class="fa-solid fa-hashtag"></i>
                                    </div>
                                    <div>
                                        <p class="detail-info-item__label">ID Buku</p>
                                        <p class="detail-info-item__value">{{ $book['id'] }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Judul --}}
                            <div class="detail-info-item">
                                <div class="detail-info-item__inner">
                                    <div class="detail-info-item__icon">
                                        <i class="fa-solid fa-book"></i>
                                    </div>
                                    <div>
                                        <p class="detail-info-item__label">Judul</p>
                                        <p class="detail-info-item__value">{{ $book['judul'] }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Penulis --}}
                            <div class="detail-info-item">
                                <div class="detail-info-item__inner">
                                    <div class="detail-info-item__icon">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                    <div>
                                        <p class="detail-info-item__label">Penulis</p>
                                        <p class="detail-info-item__value">{{ $book['penulis'] }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Tahun Terbit --}}
                            <div class="detail-info-item">
                                <div class="detail-info-item__inner">
                                    <div class="detail-info-item__icon">
                                        <i class="fa-solid fa-calendar"></i>
                                    </div>
                                    <div>
                                        <p class="detail-info-item__label">Tahun Terbit</p>
                                        <p class="detail-info-item__value">{{ $book['tahun'] }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Kategori (full width) --}}
                            <div class="detail-info-item detail-info-item--full">
                                <div class="detail-info-item__inner">
                                    <div class="detail-info-item__icon">
                                        <i class="fa-solid fa-tag"></i>
                                    </div>
                                    <div>
                                        <p class="detail-info-item__label">Kategori</p>
                                        <p class="detail-info-item__value">{{ $book['kategori'] }}</p>
                                    </div>
                                </div>
                            </div>

                        </div>


                        {{-- Actions --}}
                        <div class="detail-card__actions">
                            <a
                                id="btn-kembali-daftar"
                                href="{{ route('buku.index') }}"
                                class="btn-secondary"
                            >
                                <i class="fa-solid fa-arrow-left"></i>
                                Kembali ke Daftar Buku
                            </a>
                        </div>

                    </div>

                </div>

            @else

                {{-- ============ Buku Tidak Ditemukan (@if condition) ============ --}}
                <div class="not-found-card">

                    <div class="not-found-card__icon">
                        <i class="fa-solid fa-book-open"></i>
                    </div>

                    <h1 class="not-found-card__title">Buku Tidak Ditemukan</h1>

                    <p class="not-found-card__message">
                        Maaf, buku dengan ID
                        <span class="not-found-card__id">{{ request()->route('id') }}</span>
                        tidak tersedia di dalam koleksi perpustakaan kami.
                    </p>

                    <a
                        id="btn-kembali-not-found"
                        href="{{ route('buku.index') }}"
                        class="btn-primary"
                    >
                        <i class="fa-solid fa-arrow-left"></i>
                        Kembali ke Daftar Buku
                    </a>

                </div>

            @endif

        </div>
    </section>

@endsection