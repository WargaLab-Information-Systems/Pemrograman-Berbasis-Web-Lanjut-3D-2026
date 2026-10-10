@extends('layouts.app')

@section('title', 'Beranda — Perpustakaan Digital')

@section('content')

    <section class="hero-section animate-fade-in-up">
        <div class="container-main">
            <div style="max-width: 48rem; margin-inline: auto; text-align: center;">

                <div class="hero-badge">
                    <i class="fa-solid fa-book-open"></i>
                    Selamat Datang di Perpustakaan Digital
                </div>

                <h2 class="hero-title">
                    Temukan Buku <br>
                    <span style="color: #4f46e5;">Favorit Kamu</span> di Sini
                </h2>

                <p class="hero-description">
                    Jelajahi koleksi buku kami yang beragam — mulai dari novel, sejarah,
                    hingga pengembangan diri. Temukan inspirasi dari setiap halaman.
                </p>

                <div style="display: flex; flex-direction: column; align-items: center; gap: 0.75rem; justify-content: center;">
                    <a
                        id="btn-lihat-buku"
                        href="{{ route('buku.index') }}"
                        class="btn-primary"
                    >
                        <i class="fa-solid fa-book"></i>
                        Lihat Daftar Buku
                    </a>
                </div>

            </div>
        </div>
    </section>

@endsection