@extends('layouts.main')

@section('content')
    <h2 class="page-title">Selamat Datang di Perpustakaan</h2>
    <div class="detail-box">
        <p>Sistem informasi katalog buku perpustakaan menggunakan arsitektur MVC Laravel.</p>
        <br>
        <a href="{{ route('buku.index') }}" class="btn-detail">Lihat Daftar Buku &rarr;</a>
    </div>
@endsection