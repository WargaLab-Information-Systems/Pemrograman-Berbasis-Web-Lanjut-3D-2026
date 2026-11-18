@extends('layouts.app')
@section('title', 'Daftar Buku')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Page Header Banner --}}
    <div class="bg-gradient-to-r from-indigo-600 via-indigo-700 to-purple-700 rounded-3xl p-8 sm:p-10 text-white shadow-xl mb-8 relative overflow-hidden flex flex-col md:flex-row md:items-center md:justify-between gap-6" data-aos="fade-down" data-aos-duration="700">
        <div class="relative z-10">
            <span class="inline-block bg-white/20 backdrop-blur-md px-3.5 py-1 rounded-full text-xs font-semibold tracking-wider uppercase mb-3">
                Koleksi Perpustakaan
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight mb-2">Daftar Buku</h1>
            <p class="text-indigo-100 text-sm sm:text-base">Jelajahi dan ajukan peminjaman koleksi buku yang tersedia di perpustakaan.</p>
        </div>

        @can('kelola-buku')
            <div class="relative z-10 flex-shrink-0">
                <a href="{{ route('buku.create') }}" class="inline-flex items-center gap-2 bg-white text-indigo-700 hover:bg-indigo-50 font-bold px-6 py-3 rounded-2xl shadow-lg transition duration-200 transform hover:-translate-y-0.5">
                    <span class="text-lg leading-none">+</span> Tambah Buku Baru
                </a>
            </div>
        @endcan
    </div>

    {{-- Pesan Notifikasi Sukses / Error --}}
    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3.5 rounded-2xl mb-6 font-semibold flex items-center gap-3 shadow-sm" role="status">
            <span>✅</span> {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-800 px-5 py-3.5 rounded-2xl mb-6 font-semibold flex items-center gap-3 shadow-sm" role="status">
            <span>❌</span> {{ session('error') }}
        </div>
    @endif

    {{-- Grid Kartu Buku --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse ($books as $book)
            <div class="bg-white rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 border border-slate-100 hover:-translate-y-1 flex flex-col overflow-hidden" data-aos="fade-up" data-aos-duration="600" data-aos-delay="{{ min($loop->index * 100, 500) }}">
                
                {{-- Body Kartu --}}
                <div class="p-6 flex-1 flex flex-col">
                    {{-- Kategori & ID Buku --}}
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <span class="inline-block bg-indigo-50 text-indigo-700 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                            {{ $book->kategori->nama ?? 'Umum' }}
                        </span>
                        <span class="text-xs font-bold text-slate-400">#{{ $book->id }}</span>
                    </div>

                    {{-- Judul Buku (Tinggi Minimum Disamakan) --}}
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 leading-snug line-clamp-2 mb-3 min-h-[3rem]" title="{{ $book->judul }}">
                        {{ $book->judul }}
                    </h2>

                    {{-- Informasi Penulis & Tahun --}}
                    <div class="space-y-1.5 text-xs text-slate-500 mb-4">
                        <p class="flex items-center gap-1.5 truncate">
                            <span class="text-slate-400">Penulis:</span>
                            <strong class="text-slate-700 truncate">{{ $book->penulis }}</strong>
                        </p>
                        <p class="flex items-center gap-1.5">
                            <span class="text-slate-400">Tahun:</span>
                            <strong class="text-slate-700">{{ $book->tahun_terbit }}</strong>
                        </p>
                    </div>

                    {{-- Tombol Lihat Detail --}}
                    <div class="mt-auto pt-2">
                        <a href="{{ route('buku.show', $book->id) }}" class="inline-flex items-center gap-1 text-xs font-bold text-indigo-600 hover:text-indigo-800 transition">
                            Lihat Detail <span>→</span>
                        </a>
                    </div>
                </div>

                {{-- Footer Tombol Aksi --}}
                <div class="p-5 pt-3 bg-slate-50/70 border-t border-slate-100 flex flex-col gap-2">
                    {{-- Tombol Ajukan Peminjaman (Semua Role) --}}
                    <form action="{{ route('peminjaman.store', $book->id) }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit"
                            onclick="return confirm('Ajukan peminjaman buku: {{ $book->judul }}?');"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-xl text-xs transition duration-150 shadow-sm shadow-indigo-100 flex items-center justify-center gap-1.5 cursor-pointer">
                            <span>📖</span> Ajukan Peminjaman
                        </button>
                    </form>

                    {{-- Tombol Edit & Hapus (Khusus Admin / Petugas) --}}
                    @can('kelola-buku')
                        <div class="grid grid-cols-2 gap-2 mt-0.5">
                            <a href="{{ route('buku.edit', $book->id) }}"
                                class="bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 text-center font-semibold py-1.5 px-3 rounded-xl text-xs transition flex items-center justify-center gap-1">
                                <span>✏️</span> Edit
                            </a>

                            <form action="{{ route('buku.destroy', $book->id) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus buku ini?');" class="m-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-semibold py-1.5 px-3 rounded-xl text-xs transition flex items-center justify-center gap-1 cursor-pointer">
                                    <span>🗑️</span> Hapus
                                </button>
                            </form>
                        </div>
                    @endcan
                </div>

            </div>
        @empty
            <div class="col-span-full bg-white rounded-3xl p-12 text-center text-slate-400 border border-slate-100">
                <p class="text-base font-semibold">Belum ada koleksi buku di perpustakaan.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection