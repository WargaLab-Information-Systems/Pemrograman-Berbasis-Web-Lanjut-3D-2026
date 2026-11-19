<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Buku') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-100 min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <span class="text-xs font-bold text-indigo-600 tracking-wider uppercase">Sistem Informasi Perpustakaan</span>
                    <h1 class="text-3xl font-extrabold text-gray-900 mt-1">Detail Informasi Buku</h1>
                </div>
                <a href="{{ route('buku.index') }}" class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold px-4 py-2.5 rounded-xl transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Daftar Buku
                </a>
            </div>

            @if (session('success'))
                <div class="p-4 bg-green-100 text-green-700 rounded-xl text-sm">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="p-4 bg-red-100 text-red-700 rounded-xl text-sm">{{ session('error') }}</div>
            @endif

            @if ($buku)
                @php
                    // Pembacaan data variabel fleksibel (Mendukung Object & Array)
                    $judul = is_array($buku) ? ($buku['judul'] ?? '-') : ($buku->judul ?? '-');
                    $idBuku = is_array($buku) ? ($buku['id'] ?? '-') : ($buku->id ?? '-');
                    $penulis = is_array($buku) ? ($buku['penulis'] ?? '-') : ($buku->penulis ?? '-');
                    $tahun = is_array($buku) ? ($buku['tahun'] ?? $buku['tahun_terbit'] ?? '-') : ($buku->tahun_terbit ?? $buku->tahun ?? '-');
                    
                    // Ekstraksi khusus nama kategori dari objek/array
                    $kategoriNama = '-';
                    if (is_object($buku) && isset($buku->kategori)) {
                        $kategoriNama = is_object($buku->kategori) 
                            ? ($buku->kategori->nama ?? $buku->kategori->nama_kategori ?? '-') 
                            : $buku->kategori;
                    } elseif (is_array($buku) && isset($buku['kategori'])) {
                        $kategoriNama = is_array($buku['kategori']) 
                            ? ($buku['kategori']['nama'] ?? $buku['kategori']['nama_kategori'] ?? '-') 
                            : $buku['kategori'];
                    }
                    
                    $gambar = is_array($buku) ? ($buku['gambar'] ?? null) : ($buku->gambar ?? null);
                @endphp

                <!-- Card Utama Detail Buku -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 p-6 sm:p-8">
                        
                        <!-- Kolom Sampul Buku -->
                        <div class="flex justify-center items-start">
                            @if($gambar)
                                <img src="{{ asset($gambar) }}" alt="{{ $judul }}" class="w-full max-w-xs h-auto object-cover rounded-xl shadow-md border border-gray-100">
                            @else
                                <div class="w-full max-w-xs h-72 bg-indigo-50 border border-indigo-100 rounded-xl flex flex-col items-center justify-center text-indigo-400 p-6">
                                    <svg class="w-20 h-20 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                    <span class="text-xs font-semibold uppercase tracking-wider text-indigo-500">Sampul Tidak Tersedia</span>
                                </div>
                            @endif
                        </div>

                        <!-- Kolom Informasi Detail -->
                        <div class="md:col-span-2 space-y-6">
                            <div class="border-b border-gray-100 pb-4">
                                <span class="inline-block bg-indigo-50 text-indigo-700 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-2">
                                    {{ $kategoriNama }}
                                </span>
                                <h2 class="text-3xl font-extrabold text-gray-900 leading-snug">{{ $judul }}</h2>
                            </div>

                            <!-- Grid Kartu Parameter Informasi -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">ID Buku</span>
                                    <span class="text-base font-bold text-gray-800 mt-0.5 inline-block">#{{ $idBuku }}</span>
                                </div>

                                <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Penulis / Pengarang</span>
                                    <span class="text-base font-bold text-gray-800 mt-0.5 inline-block">{{ $penulis }}</span>
                                </div>

                                <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Tahun Terbit</span>
                                    <span class="text-base font-bold text-gray-800 mt-0.5 inline-block">{{ $tahun }}</span>
                                </div>

                                <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Kategori Koleksi</span>
                                    <span class="text-base font-bold text-gray-800 mt-0.5 inline-block">{{ $kategoriNama }}</span>
                                </div>
                            </div>

                            <!-- Tombol Aksi Navigasi -->
                            <div class="pt-4 border-t border-gray-100 flex flex-wrap gap-3">
                                <a href="{{ route('buku.index') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm px-6 py-2.5 rounded-xl transition shadow-sm">
                                    &larr; Kembali ke Daftar Buku
                                </a>

                                @can('mengajukan peminjaman buku')
                                    <form action="{{ route('peminjaman.ajukan', $idBuku) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm px-5 py-2.5 rounded-xl transition shadow-sm">
                                            Ajukan Peminjaman
                                        </button>
                                    </form>
                                @endcan

                                @can('kelola data buku')
                                    @if(!is_array($buku) && isset($buku->id))
                                        <a href="{{ route('buku.edit', $buku->id) }}" class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white font-semibold text-sm px-5 py-2.5 rounded-xl transition shadow-sm">
                                            Edit Buku
                                        </a>
                                    @endif
                                @endcan
                            </div>
                        </div>

                    </div>
                </div>
            @else
                <div class="bg-white p-12 rounded-2xl shadow-sm border border-gray-100 text-center space-y-4">
                    <h2 class="text-2xl font-bold text-gray-900">Buku Tidak Ditemukan</h2>
                    <a href="{{ route('buku.index') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm px-6 py-2.5 rounded-xl transition">
                        Kembali ke Daftar Buku
                    </a>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>