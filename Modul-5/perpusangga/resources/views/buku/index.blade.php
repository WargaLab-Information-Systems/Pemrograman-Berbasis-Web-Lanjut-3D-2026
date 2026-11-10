<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-xl text-slate-800 leading-tight">Katalog & Data Buku</h2>
                <p class="text-xs text-slate-500 mt-0.5">Kelola koleksi buku perpustakaan secara real-time</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-sm font-medium">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl text-rose-800 text-sm font-medium">
                    {{ session('error') }}
                </div>
            @endif

            @can('Kelola data buku')
            {{-- Tampilan Admin / Petugas (Form Tambah Buku + Tabel CRUD) --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Form Tambah Buku -->
                <div class="bg-white border border-slate-200/80 rounded-xl p-6 shadow-sm h-fit">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">Tambah Buku Baru</h3>
                    <form action="{{ route('buku.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="judul" class="block text-xs font-semibold text-slate-600 mb-1.5">Judul Buku</label>
                            <input type="text" id="judul" name="judul" required class="w-full border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-600 focus:outline-none transition" />
                        </div>

                        <div>
                            <label for="kategori_id" class="block text-xs font-semibold text-slate-600 mb-1.5">Kategori Buku</label>
                            <select id="kategori_id" name="kategori_id" required class="w-full border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="penulis" class="block text-xs font-semibold text-slate-600 mb-1.5">Penulis</label>
                            <input type="text" id="penulis" name="penulis" required class="w-full border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-600 focus:outline-none transition" />
                        </div>
                        <div>
                            <label for="tahun_terbit" class="block text-xs font-semibold text-slate-600 mb-1.5">Tahun Terbit</label>
                            <input type="number" id="tahun_terbit" name="tahun_terbit" required class="w-full border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-600 focus:outline-none transition" />
                        </div>
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2.5 px-4 rounded-lg shadow-sm transition active:scale-[0.99]">
                            Simpan Buku
                        </button>
                    </form>
                </div>

                <!-- Tabel Koleksi Buku -->
                <div class="lg:col-span-2 bg-white border border-slate-200/80 rounded-xl p-6 shadow-sm">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">Daftar Koleksi Buku</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-200 bg-slate-50 text-xs font-bold text-slate-500 uppercase">
                                    <th class="py-3 px-4">#</th>
                                    <th class="py-3 px-4">Judul</th>
                                    <th class="py-3 px-4">Kategori</th>
                                    <th class="py-3 px-4">Penulis</th>
                                    <th class="py-3 px-4">Tahun</th>
                                    <th class="py-3 px-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                                @forelse($bukus as $index => $buku)
                                    <tr>
<!-- A1 -->
                                        <td class="py-3.5 px-4 font-semibold text-slate-400">{{ $index + 1 }}</td>
                                        <td class="py-3.5 px-4 font-medium text-slate-900">{{!! $buku->judul !!}}</td>
                                        <td class="py-3.5 px-4">
                                            <span class="px-2.5 py-1 rounded text-xs font-semibold bg-slate-100 text-slate-700">
                                                {{ $buku->kategori->nama ?? (is_array($buku->kategori) ? ($buku->kategori['nama'] ?? '-') : $buku->kategori) }}
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-4">{{ $buku->penulis }}</td>
                                        <td class="py-3.5 px-4 text-xs text-slate-600">{{ $buku->tahun_terbit }}</td>
                                        <td class="py-3.5 px-4 flex items-center gap-1.5">
                                            <a href="{{ route('buku.show', $buku->id) }}" class="px-2 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded transition">Detail</a>
                                            <a href="{{ route('buku.edit', $buku->id) }}" class="px-2 py-1 bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 text-xs font-semibold rounded transition">Edit</a>
                                            <form action="{{ route('buku.destroy', $buku->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus buku ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-2 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-xs font-semibold rounded transition">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-6 text-center text-slate-400 text-xs">Belum ada data buku.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @else
            {{-- Tampilan Siswa (Full Width) --}}
            <div class="bg-white border border-slate-200/80 rounded-xl p-6 shadow-sm w-full">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">Daftar Koleksi Buku</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50 text-xs font-bold text-slate-500 uppercase">
                                <th class="py-3 px-4">#</th>
                                <th class="py-3 px-4">Judul</th>
                                <th class="py-3 px-4">Kategori</th>
                                <th class="py-3 px-4">Penulis</th>
                                <th class="py-3 px-4">Tahun</th>
                                <th class="py-3 px-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                            @forelse($bukus as $index => $buku)
                                <tr>
                                    <td class="py-3.5 px-4 font-semibold text-slate-400">{{ $index + 1 }}</td>
                                    <td class="py-3.5 px-4 font-medium text-slate-900">{{ $buku->judul }}</td>
                                    <td class="py-3.5 px-4">
                                        <span class="px-2.5 py-1 rounded text-xs font-semibold bg-slate-100 text-slate-700">
                                            {{ $buku->kategori->nama ?? (is_array($buku->kategori) ? ($buku->kategori['nama'] ?? '-') : $buku->kategori) }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4">{{ $buku->penulis }}</td>
                                    <td class="py-3.5 px-4 text-xs text-slate-600">{{ $buku->tahun_terbit }}</td>
                                    <td class="py-3.5 px-4 flex items-center gap-2">
                                        <a href="{{ route('buku.show', $buku->id) }}" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition">Detail</a>
                                        
                                        @can('Ajukan peminjaman')
                                        <form action="{{ route('peminjaman.ajukan', $buku->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" onclick="return confirm('Yakin ingin meminjam buku ini?')" class="px-2.5 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition shadow-sm">
                                                Pinjam Buku
                                            </button>
                                        </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-6 text-center text-slate-400 text-xs">Belum ada data buku.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endcan

        </div>
    </div>
</x-app-layout>