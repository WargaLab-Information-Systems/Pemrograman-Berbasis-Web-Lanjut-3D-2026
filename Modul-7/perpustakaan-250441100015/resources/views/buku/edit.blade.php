@extends('layouts.app')

@section('title', 'Edit Buku')

@section('content')
<div class="max-w-2xl mx-auto py-10 px-4">
    <div class="bg-white p-8 rounded-2xl shadow-lg border border-indigo-50">
        <div class="mb-6">
            <span class="text-xs font-bold text-indigo-600 tracking-wider uppercase">Manajemen Buku</span>
            <h1 class="text-2xl font-extrabold text-gray-900 mt-1">Form Edit Buku</h1>
            <p class="text-sm text-gray-500">Perbarui informasi buku yang tersimpan di sistem.</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-xl mb-6 text-sm">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('buku.update', $buku->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-5">
                <label class="block text-gray-700 font-semibold mb-2 text-sm">Kategori</label>
                <select name="kategori_id" class="w-full border-gray-300 rounded-xl p-3 focus:ring-indigo-500 focus:border-indigo-500 border" required>
                    @foreach ($kategoris as $kategori)
                        <option value="{{ $kategori->id }}" {{ $buku->kategori_id == $kategori->id ? 'selected' : '' }}>
                            {{ $kategori->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-5">
                <label class="block text-gray-700 font-semibold mb-2 text-sm">Judul Buku</label>
                <input type="text" name="judul" value="{{ old('judul', $buku->judul) }}" class="w-full border-gray-300 rounded-xl p-3 focus:ring-indigo-500 focus:border-indigo-500 border" required>
            </div>

            <div class="mb-5">
                <label class="block text-gray-700 font-semibold mb-2 text-sm">Penulis</label>
                <input type="text" name="penulis" value="{{ old('penulis', $buku->penulis) }}" class="w-full border-gray-300 rounded-xl p-3 focus:ring-indigo-500 focus:border-indigo-500 border" required>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2 text-sm">Tahun Terbit</label>
                <input type="number" name="tahun_terbit" value="{{ old('tahun_terbit', $buku->tahun_terbit) }}" class="w-full border-gray-300 rounded-xl p-3 focus:ring-indigo-500 focus:border-indigo-500 border" required>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="bg-indigo-600 text-white font-semibold px-6 py-2.5 rounded-xl hover:bg-indigo-700 transition shadow-md shadow-indigo-100">
                    Simpan Perubahan
                </button>
                <a href="{{ route('buku.index') }}" class="bg-gray-100 text-gray-700 font-semibold px-6 py-2.5 rounded-xl hover:bg-gray-200 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection