@extends('layouts.app')

@section('title', 'Akses Ditolak - Perpustakaan Digital')

@section('content')
    <div class="panel pesan-halaman">
        <h2>403 | Akses Ditolak</h2>
        <p>Anda tidak memiliki hak akses untuk membuka halaman atau menjalankan operasi ini.</p>
        <a href="{{ route('home') }}" class="btn btn-secondary">Kembali ke Beranda</a>
    </div>
@endsection
