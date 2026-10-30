@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')
    <h2 class="judul-halaman">Daftar Buku</h2>

    <div class="grid-buku">
        @foreach ($bukus as $buku)
            <div class="card">
                <span class="badge">{{ $buku->kategori->nama }}</span>
                <h3>{{ $buku->judul }}</h3>
                <div class="meta">
                    {{ $buku->penulis }}<br>
                    {{ $buku->tahun_terbit }}
                </div>
                <a href="{{ route('buku.show', $buku->id) }}" class="btn btn-sm">Lihat Detail</a>
            </div>
        @endforeach
    </div>
@endsection