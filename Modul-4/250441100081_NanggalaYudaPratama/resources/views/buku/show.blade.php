@extends('layouts.app')

@section('title', 'Detail Buku - Perpustakaan Digital')

@section('content')
<div class="flex justify-center my-6">
    <div class="w-full max-w-md bg-white border border-slate-200 rounded-2xl shadow-sm p-8">
        
        @if ($book)
            <div class="mb-6">
                <div class="flex justify-between items-center mb-3">
                    <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-3 py-1 rounded-full uppercase tracking-wider">
                        {{ $book['kategori'] }}
                    </span>
                    <span class="text-xs font-medium text-slate-400 bg-slate-50 px-2.5 py-1 rounded-lg">
                        ID: #{{ $book['id'] }}
                    </span>
                </div>
                <h2 class="text-xl font-bold text-slate-900 leading-snug">
                    {{ $book['judul'] }}
                </h2>
            </div>

            <div class="space-y-3.5 border-t border-b border-slate-100 py-5 mb-6 text-sm">
                <div class="flex justify-between items-center">
                    <span class="text-slate-400 font-medium">Penulis</span>
                    <span class="font-semibold text-slate-800">{{ $book['penulis'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-400 font-medium">Tahun Terbit</span>
                    <span class="font-semibold text-slate-800">{{ $book['tahun'] }}</span>
                </div>
            </div>

            <div>
                <a href="{{ route('buku.index') }}" class="inline-flex items-center justify-center w-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium px-4 py-2.5 rounded-xl transition text-xs"> Kembali ke Daftar Buku
                </a>
            </div>
        @else
            <div class="text-center py-6">
                <h2 class="text-base font-bold text-red-600 mb-1">Buku Tidak Ditemukan</h2>
                <p class="text-xs text-slate-500 mb-6">Data buku dengan ID tersebut tidak terdaftar.</p>
                <a href="{{ route('buku.index') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-xl transition text-xs">
                    Kembali ke Daftar Buku
                </a>
            </div>
        @endif

    </div>
</div>
@endsection