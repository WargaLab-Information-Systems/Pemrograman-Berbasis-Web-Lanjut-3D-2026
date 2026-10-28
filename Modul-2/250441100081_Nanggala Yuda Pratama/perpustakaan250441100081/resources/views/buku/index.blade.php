@extends('layouts.app')

@section('title', 'Daftar Buku - Perpustakaan Digital')

@section('content')
<div class="mb-8 pb-4 border-b border-slate-200">
    <h2 class="text-2xl font-bold text-slate-900">Koleksi Buku</h2>
    <p class="text-sm text-slate-500 mt-1">Daftar lengkap seluruh buku yang tersedia di perpustakaan.</p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach ($books as $book)
        <x-buku-card :judul="$book['judul']" :penulis="$book['penulis']" :tahun="$book['tahun']">
            {{ $book['kategori'] }}

            <x-slot:footerSlot>
                <a href="{{ route('buku.show', $book['id']) }}" class="flex items-center justify-center w-full bg-slate-100 hover:bg-blue-600 hover:text-white text-slate-700 text-xs font-semibold py-2.5 rounded-xl transition duration-150"> Lihat Detail
                </a>
            </x-slot:footerSlot>
        </x-buku-card>
    @endforeach
</div>
@endsection