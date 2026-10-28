@extends('layouts.app')

@section('title', 'Kelola Data Buku')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
        <div>
            <h2 class="text-xl font-bold text-slate-800 tracking-tight">Katalog & Data Buku</h2>
            <p class="text-slate-500 text-xs sm:text-sm mt-0.5">Kelola koleksi buku perpustakaan</p>
        </div>
    </div>

    <div id="alert-box" class="hidden p-4 rounded-xl text-sm font-medium transition-all duration-200" role="alert"></div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <div class="lg:col-span-4 bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm sticky top-24">
            <div class="flex items-center justify-between pb-3.5 mb-4 border-b border-slate-100">
                <h3 id="form-title" class="text-base font-bold text-slate-800">Tambah Buku Baru</h3>
                <span class="text-xs text-slate-400 font-medium">Form Input</span>
            </div>
            
            <form id="form-buku" onsubmit="handleFormSubmit(event)" class="space-y-4">
                <input type="hidden" id="buku_id" name="buku_id" value="">

                <div>
                    <label for="kategori_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Kategori</label>
                    <select id="kategori_id" name="kategori_id" class="w-full bg-slate-50/80 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori ?? $kategori->nama ?? $kategori->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="judul" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Judul Buku</label>
                    <input type="text" id="judul" name="judul" class="w-full bg-slate-50/80 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition" placeholder="Contoh: Laskar Pelangi" required>
                </div>

                <div>
                    <label for="penulis" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Penulis</label>
                    <input type="text" id="penulis" name="penulis" class="w-full bg-slate-50/80 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition" placeholder="Contoh: Andrea Hirata" required>
                </div>

                <div>
                    <label for="tahun_terbit" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Tahun Terbit</label>
                    <input type="number" id="tahun_terbit" name="tahun_terbit" class="w-full bg-slate-50/80 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition" placeholder="Contoh: 2024" required>
                </div>

                <div class="pt-2 flex gap-2">
                    <button type="submit" id="btn-submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm py-2.5 px-4 rounded-xl shadow-sm hover:shadow transition active:scale-[0.98]">
                        Simpan Buku
                    </button>
                    <button type="button" id="btn-cancel" onclick="resetForm()" class="hidden w-full bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-sm py-2.5 px-4 rounded-xl transition active:scale-[0.98]">
                        Batal
                    </button>
                </div>
            </form>
        </div>

        <div class="lg:col-span-8 bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider">Daftar Koleksi Buku</h3>
                <button onclick="loadBukuData()" class="text-xs font-semibold text-blue-600 hover:text-blue-700 flex items-center gap-1">
                    Refresh
                </button>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-[11px] font-bold tracking-wider border-b border-slate-200/80">
                        <tr>
                            <th scope="col" class="px-5 py-3.5 text-center w-12">#</th>
                            <th scope="col" class="px-5 py-3.5">Kategori</th>
                            <th scope="col" class="px-5 py-3.5">Judul</th>
                            <th scope="col" class="px-5 py-3.5">Penulis</th>
                            <th scope="col" class="px-5 py-3.5 text-center">Tahun</th>
                            <th scope="col" class="px-5 py-3.5 text-center w-36">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tabel-buku-body" class="divide-y divide-slate-100">
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-slate-400">
                                Memuat data dari server...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<script src="{{ asset('js/buku.js') }}"></script>
@endsection