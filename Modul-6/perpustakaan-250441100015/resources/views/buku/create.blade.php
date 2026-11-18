@extends('layouts.app')

@section('title', 'Tambah Buku Baru')

@section('content')
<div class="max-w-2xl mx-auto py-8 px-4">
    <div class="bg-white p-6 rounded-xl shadow-md">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">Form Tambah Buku</h1>

        <form action="{{ route('buku.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Kategori</label>
                <select name="kategori_id" class="w-full border rounded-lg p-2" required>
                    @foreach ($kategoris as $kategori)
                        <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Judul Buku</label>
                <input type="text" name="judul" class="w-full border rounded-lg p-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Penulis</label>
                <input type="text" name="penulis" class="w-full border rounded-lg p-2" required>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Tahun Terbit</label>
                <input type="number" name="tahun_terbit" class="w-full border rounded-lg p-2" required>
            </div>
            <div class="flex gap-4">
                <button type="submit" class="bg-indigo-600 text-white px-5 py-2 rounded-lg hover:bg-indigo-700">
                    Simpan Buku
                </button>
                <a href="{{ route('buku.index') }}" class="bg-gray-200 text-gray-700 px-5 py-2 rounded-lg">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection