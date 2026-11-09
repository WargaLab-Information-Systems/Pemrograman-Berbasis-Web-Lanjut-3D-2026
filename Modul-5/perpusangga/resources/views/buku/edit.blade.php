<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-xl text-slate-800 leading-tight">Edit Data Buku</h2>
                <p class="text-xs text-slate-500 mt-0.5">Perbarui rincian informasi koleksi buku</p>
            </div>
            <a href="{{ route('buku.index') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                Batal & Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-slate-200/80 rounded-xl p-6 shadow-sm">
                <form action="{{ route('buku.update', $buku->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="judul" class="block text-xs font-semibold text-slate-600 mb-1.5">Judul Buku</label>
                        <input type="text" id="judul" name="judul" value="{{ old('judul', $buku->judul) }}" required class="w-full border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-600 focus:outline-none transition" />
                    </div>

                    <div>
                        <label for="kategori_id" class="block text-xs font-semibold text-slate-600 mb-1.5">Kategori</label>
                        <select id="kategori_id" name="kategori_id" required class="w-full border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id }}" {{ (old('kategori_id', $buku->kategori_id) == $kategori->id) ? 'selected' : '' }}>
                                    {{ $kategori->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="penulis" class="block text-xs font-semibold text-slate-600 mb-1.5">Penulis</label>
                        <input type="text" id="penulis" name="penulis" value="{{ old('penulis', $buku->penulis) }}" required class="w-full border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-600 focus:outline-none transition" />
                    </div>

                    <div>
                        <label for="tahun_terbit" class="block text-xs font-semibold text-slate-600 mb-1.5">Tahun Terbit</label>
                        <input type="number" id="tahun_terbit" name="tahun_terbit" value="{{ old('tahun_terbit', $buku->tahun_terbit) }}" required class="w-full border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-600 focus:outline-none transition" />
                    </div>

                    <div class="pt-2 flex items-center gap-3">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2.5 px-5 rounded-lg shadow-sm transition active:scale-[0.99]">
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('buku.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold py-2.5 px-4 rounded-lg transition">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>