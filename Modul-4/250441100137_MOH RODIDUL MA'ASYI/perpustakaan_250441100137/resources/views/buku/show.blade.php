@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto bg-white p-8 rounded-lg shadow-md border border-gray-200">
    @if ($buku)
        <h2 class="text-2xl font-bold text-blue-600 mb-4">{{ $buku['judul'] }}</h2>
        <div class="space-y-2 mb-6">
            <p><strong class="text-gray-700">ID Buku:</strong> {{ $buku['id'] }}</p>
            <p><strong class="text-gray-700">Penulis:</strong> {{ $buku['penulis'] }}</p>
            <p><strong class="text-gray-700">Tahun Terbit:</strong> {{ $buku['tahun'] }}</p>
            <p><strong class="text-gray-700">Kategori:</strong> {{ $buku['kategori'] }}</p>
        </div>
        <a href="{{ route('buku.index') }}" class="inline-block bg-gray-500 text-white text-sm px-4 py-2 rounded hover:bg-gray-600 transition">
            &larr; Kembali ke Daftar Buku
        </a>
    @else
        <div class="text-center py-6">
            <h2 class="text-xl font-bold text-red-600 mb-2">Buku Tidak Ditemukan</h2>
            <p class="text-gray-600 mb-4">Maaf, data buku dengan ID tersebut tidak tersedia di sistem.</p>
            <a href="{{ route('buku.index') }}" class="inline-block bg-blue-500 text-white text-sm px-4 py-2 rounded hover:bg-blue-600 transition">
                Kembali ke Daftar Buku
            </a>
        </div>
    @endif
</div>
@endsection
