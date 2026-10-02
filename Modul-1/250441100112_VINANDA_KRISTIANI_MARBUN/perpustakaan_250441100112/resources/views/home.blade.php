@extends('layouts.app')

@section('title', 'Beranda')

@section('content')

    <section class="hero">

        <h2>Selamat Datang di Perpustakaan Digital 📚</h2>

        <p>
            Selamat datang di Perpustakaan Digital.
            Temukan berbagai koleksi buku untuk menambah
            pengetahuan dan wawasan Anda.
        </p>

        <a href="{{ route('buku.index') }}" class="btn">
            Lihat Daftar Buku
        </a>

    </section>

    <section class="info-section">

        <h2>Kenapa Membaca?</h2>

        <div class="info-grid">

            <div class="info-card">
                <h3> Menambah Pengetahuan</h3>

                <p>
                    Membaca membantu kita memperoleh informasi
                    dan wawasan baru.
                </p>
            </div>


            <div class="info-card">
                <h3> Melatih Pikiran</h3>

                <p>
                    Membaca dapat membantu meningkatkan kemampuan
                    berpikir dan memahami informasi.
                </p>
            </div>


            <div class="info-card">
                <h3> Mengenal Dunia</h3>

                <p>
                    Buku memberikan kesempatan untuk mengenal
                    berbagai tempat dan pengalaman.
                </p>
            </div>

        </div>

    </section>

@endsection