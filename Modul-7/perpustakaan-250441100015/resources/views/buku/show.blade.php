@extends('layouts.app')

@section('title', $buku ? $buku->judul : 'Buku Tidak Ditemukan')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-4">
    @if ($buku)
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-indigo-50">
            {{-- Header Card --}}
            <div class="bg-gradient-to-r from-indigo-600 via-indigo-700 to-purple-700 p-8 sm:p-10 text-white">
                <div class="inline-block bg-white/20 backdrop-blur-md px-3.5 py-1 rounded-full text-xs font-semibold tracking-wider uppercase mb-3">
                    {{ $buku->kategori->nama ?? 'Umum' }}
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight mb-2">{{ $buku->judul }}</h1>
                <p class="text-indigo-100 text-base sm:text-lg">Koleksi Buku Perpustakaan Digital</p>
            </div>

            {{-- Detail Info Grid --}}
            <div class="p-8 sm:p-10">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
                    <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Penulis</span>
                        <p class="text-lg font-bold text-slate-800">{{ $buku->penulis }}</p>
                    </div>

                    <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Tahun Terbit</span>
                        <p class="text-lg font-bold text-slate-800">{{ $buku->tahun_terbit }}</p>
                    </div>

                    <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Kategori Buku</span>
                        <p class="text-lg font-bold text-slate-800">{{ $buku->kategori->nama ?? 'Tanpa Kategori' }}</p>
                    </div>

                    <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">ID Buku (Database)</span>
                        <p class="text-lg font-bold text-indigo-600">#{{ $buku->id }}</p>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-wrap items-center justify-between gap-4 pt-6 border-t border-slate-100">
                    <a href="{{ route('buku.index') }}" class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-900 font-semibold px-5 py-2.5 rounded-xl hover:bg-slate-100 transition">
                        <span>←</span> Kembali ke Daftar Buku
                    </a>

                    <div class="flex items-center gap-3">
                        @can('kelola-buku')
                            <a href="{{ route('buku.edit', $buku->id) }}" class="bg-amber-500 hover:bg-amber-600 text-white font-semibold px-5 py-2.5 rounded-xl transition shadow-md shadow-amber-100">
                                Edit Buku
                            </a>
                        @endcan

                        <form action="{{ route('peminjaman.store', $buku->id) }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit"
                                onclick="return confirm('Ajukan peminjaman buku: {{ $buku->judul }}?');"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2.5 rounded-xl transition shadow-md shadow-indigo-100">
                                Ajukan Peminjaman
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white rounded-3xl p-10 text-center shadow-lg border border-slate-100 max-w-lg mx-auto">
            <h1 class="text-2xl font-bold text-slate-800 mb-2">Buku Tidak Ditemukan</h1>
            <p class="text-slate-500 mb-6">Maaf, data buku yang Anda cari tidak tersedia di database.</p>
            <a href="{{ route('buku.index') }}" class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl font-semibold inline-block hover:bg-indigo-700 transition">
                Kembali ke Daftar Buku
            </a>
        </div>
    @endif
</div>
@endsection