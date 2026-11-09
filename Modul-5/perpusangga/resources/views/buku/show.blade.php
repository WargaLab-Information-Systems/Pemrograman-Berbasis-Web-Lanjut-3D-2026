<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-xl text-slate-800 leading-tight">Detail Koleksi Buku</h2>
                <p class="text-xs text-slate-500 mt-0.5">Informasi lengkap spesifikasi dan ketersediaan buku</p>
            </div>
            <a href="{{ route('buku.index') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                Kembali ke Katalog
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-slate-200/80 rounded-xl p-6 shadow-sm space-y-6">
                
                <div class="border-b border-slate-100 pb-4">
                    <span class="px-2.5 py-1 rounded text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200 inline-block mb-2">
                        {{ is_object($buku->kategori) ? $buku->kategori->nama : (is_array($buku->kategori) ? ($buku->kategori['nama'] ?? '-') : $buku->kategori) }}
                    </span>
                    <h3 class="text-2xl font-bold text-slate-900 tracking-tight">{{ $buku->judul }}</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div class="bg-slate-50 p-4 rounded-lg border border-slate-100">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Penulis</span>
                        <span class="font-semibold text-slate-800 mt-1 block">{{ $buku->penulis }}</span>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-lg border border-slate-100">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Tahun Terbit</span>
                        <span class="font-semibold text-slate-800 mt-1 block">{{ $buku->tahun_terbit }}</span>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                    <a href="{{ route('buku.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-lg transition">
                        Kembali
                    </a>

                    @can('Ajukan peminjaman')
                    <form action="{{ route('peminjaman.ajukan', $buku->id) }}" method="POST">
                        @csrf
                        <button type="submit" onclick="return confirm('Yakin ingin meminjam buku ini?')" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-sm transition active:scale-[0.99]">
                            Pinjam Buku Ini
                        </button>
                    </form>
                    @endcan
                </div>

            </div>
        </div>
    </div>
</x-app-layout>