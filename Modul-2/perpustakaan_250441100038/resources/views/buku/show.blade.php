@extends('layouts.master') {{-- Sesuaikan jika nama layout Anda berbeda --}}

@section('title', 'Detail Buku')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-3xl">
    
    <!-- Card Detail Buku -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
        
        <!-- Header Card -->
        <div class="bg-slate-800 px-6 py-4">
            <h2 class="text-xl font-semibold text-white">Informasi Detail Buku</h2>
        </div>

        <!-- Isi Konten -->
        <div class="p-6 md:p-8">
            <div class="mb-6">
                <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-semibold mb-3 capitalize">
                    {{ $buku->kategori->nama ?? $buku->kategori ?? 'Tanpa Kategori' }}
                </span>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $buku->judul }}</h1>
                <p class="text-gray-500 text-sm">ID Buku: #{{ $buku->id }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-gray-100 pt-6">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Penulis</p>
                    <p class="text-lg font-semibold text-gray-800 mt-1">{{ $buku->penulis }}</p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Tahun Terbit</p>
                    <p class="text-lg font-semibold text-gray-800 mt-1">{{ $buku->tahun_terbit ?? $buku->tahun }}</p>
                </div>
            </div>

            <!-- Tombol Kembali -->
            <div class="mt-10 border-t border-gray-100 pt-6">
                <a href="{{ route('buku.index') }}" 
                   class="inline-flex items-center px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Daftar Buku
                </a>
            </div>
        </div>
    </div>
    
</div>
@endsection