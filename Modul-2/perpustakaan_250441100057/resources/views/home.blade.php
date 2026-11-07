@extends('layouts.app')

@section('judul', 'Beranda')

@section('konten')

    <div class="beranda">

        <div class="beranda-isi">

            <p class="label">SELAMAT DATANG</p>

            <h2>Temukan Buku yang Kamu Cari</h2>

            <p class="deskripsi">
                Selamat datang di Sistem Informasi Perpustakaan.
                Silakan melihat berbagai buku yang tersedia.
            </p>

            <a class="tombol" href="{{ route('buku.index') }}">
                Lihat Daftar Buku
            </a>

        </div>

    </div>

@endsection