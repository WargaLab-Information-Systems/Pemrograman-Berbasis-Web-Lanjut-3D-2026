@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')
    <div style="max-width: 1100px; margin: 24px auto; padding: 0 16px;">
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-bottom: 20px;">
            <h2 style="margin: 0;">Koleksi Buku Kami</h2>
            <a href="{{ route('buku.create') }}" class="btn" style="background: #16a34a;">Tambah Buku</a>
        </div>

        @if (session('success'))
            <div style="background: #dcfce7; color: #166534; padding: 12px 16px; border-radius: 8px; margin-bottom: 16px;">
                {{ session('success') }}
            </div>
        @endif

        <div class="book-grid">
            @foreach($buku as $item)
                <x-kartu-buku
                    judul="{{ $item['judul'] }}"
                    penulis="{{ $item['penulis'] }}"
                    tahun="{{ $item['tahun_terbit'] }}"
                    detailUrl="{{ route('buku.show', $item['id']) }}"
                >
                    <span style="font-size: 12px; color: gray;">Kategori: {{ $item['kategori'] }}</span>
                </x-kartu-buku>
            @endforeach
        </div>
    </div>
@endsection