@extends('layouts.main')

@section('title', 'Akses Ditolak')

@section('content')
    <section style="text-align:center; padding:60px 20px;">
        <h2 style="color:#d9534f; font-size:48px; margin:0;">403</h2>
        <h3>Akses Ditolak</h3>
        <p>Anda tidak memiliki hak akses untuk membuka halaman atau menjalankan operasi ini.</p>
        <a href="{{ auth()->check() ? route('dashboard') : route('home') }}" class="tombol">Kembali</a>
    </section>
@endsection
