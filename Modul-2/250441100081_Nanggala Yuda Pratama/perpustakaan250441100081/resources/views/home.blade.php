@extends('layouts.app')

@section('title', 'Beranda - Perpustakaan Digital')

@section('content')
<div class="flex justify-center items-center my-10">
    <div class="w-full max-w-xl bg-white border border-slate-200 rounded-2xl shadow-sm p-8 md:p-10 text-center">
        <h2 class="text-2xl md:text-3xl font-bold text-slate-900 mb-3">
            Selamat Datang di Perpustakaan
        </h2>
        <p class="text-slate-600 text-sm leading-relaxed mb-6">
            Kelola dan temukan referensi buku akademik, teknologi, dan umum dengan cepat dan terstruktur.
        </p>
        <div>
            <a href="{{ route('buku.index') }}" class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-3 rounded-xl transition shadow-sm text-sm">
                Lihat Daftar Buku
            </a>
        </div>
    </div>
</div>
@endsection