@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-4">
        <a href="{{ url()->previous() }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium inline-flex items-center">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-2">Formulir Pengajuan Peminjaman Buku</h2>
        <p class="text-sm text-gray-500 mb-6">Pilih buku yang ingin Anda pinjam. Permohonan akan segera diproses oleh petugas perpustakaan.</p>

        <form method="POST" action="{{ route('peminjaman.store') }}" class="space-y-4">
            @csrf

            <div>
                <label for="buku_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Buku</label>
                <select name="buku_id" id="buku_id" required class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">-- Pilih Judul Buku --</option>
                    @foreach($bukus as $b)
                        <option value="{{ $b->id }}" {{ (old('buku_id', $bukuId) == $b->id) ? 'selected' : '' }}>
                            {{ $b->judul }} (Penulis: {{ $b->penulis }})
                        </option>
                    @endforeach
                </select>
                @error('buku_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="tanggal_pinjam" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai Peminjaman</label>
                <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('tanggal_pinjam') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="bg-gray-50 p-4 rounded-lg text-xs text-gray-600 space-y-1">
                <p class="font-semibold text-gray-700">Informasi Peminjam:</p>
                <p>Nama: <span class="font-medium text-gray-900">{{ Auth::user()->name }}</span></p>
                <p>Email: <span class="font-medium text-gray-900">{{ Auth::user()->email }}</span></p>
                <p>Batas waktu peminjaman standar adalah 7 hari terhitung sejak disetujui.</p>
            </div>

            <div class="pt-4 flex items-center gap-3">
                <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm rounded-lg shadow-sm transition">
                    Kirim Pengajuan Peminjaman
                </button>
                <a href="{{ route('dashboard') }}" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium text-sm rounded-lg transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

