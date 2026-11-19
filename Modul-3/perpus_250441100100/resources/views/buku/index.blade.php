<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Buku') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div>
                <span class="text-xs font-bold text-indigo-600 tracking-wider uppercase">Sistem Informasi Perpustakaan</span>
                <h1 class="text-3xl font-extrabold text-gray-900 mt-1">
                    @can('kelola data buku')
                        Manajemen Buku
                    @else
                        Daftar Buku
                    @endcan
                </h1>
            </div>

            @if (session('success'))
                <div class="p-4 bg-green-100 text-green-700 rounded-xl text-sm">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="p-4 bg-red-100 text-red-700 rounded-xl text-sm">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="p-4 bg-red-100 text-red-700 rounded-xl text-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- FORMULIR TAMBAH BUKU (HANYA UNTUK ADMIN & PETUGAS) -->
            @can('kelola data buku')
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Tambah Buku</h2>
                <form action="{{ route('buku.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <select name="kategori_id" class="w-full rounded-xl border-gray-300 shadow-sm text-sm" required>
                            <option value="">Pilih kategori</option>
                            @foreach($kategoris as $kat)
                                <option value="{{ $kat->id }}">{{ $kat->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul Buku</label>
                        <input type="text" name="judul" class="w-full rounded-xl border-gray-300 shadow-sm text-sm" placeholder="Masukkan judul buku" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Penulis</label>
                        <input type="text" name="penulis" class="w-full rounded-xl border-gray-300 shadow-sm text-sm" placeholder="Masukkan nama penulis" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Terbit</label>
                        <input type="number" name="tahun_terbit" class="w-full rounded-xl border-gray-300 shadow-sm text-sm" placeholder="Contoh: 2025" required>
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm py-2.5 rounded-xl transition duration-150">
                        Simpan Buku
                    </button>
                </form>
            </div>
            @endcan

            <!-- TABEL DAFTAR BUKU -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-bold text-gray-900">Daftar Buku</h2>
                    <a href="{{ route('buku.index') }}" class="bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold px-3 py-2 rounded-lg">Muat Ulang Data</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm border-separate border-spacing-y-2">
                        <thead>
                            <tr class="bg-gray-50 text-gray-700 font-bold text-xs uppercase">
                                <th class="py-3 px-4">NO.</th>
                                <th class="py-3 px-4">KATEGORI</th>
                                <th class="py-3 px-4">JUDUL</th>
                                <th class="py-3 px-4">PENULIS</th>
                                <th class="py-3 px-4">TAHUN</th>
                                <th class="py-3 px-4">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($buku as $index => $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-3.5 px-4 font-medium text-gray-900">{{ $index + 1 }}</td>
                                    <td class="py-3.5 px-4 text-gray-600">{{ $item->kategori->nama ?? '-' }}</td>
                                    <td class="py-3.5 px-4 font-semibold text-gray-800">
                                        <a href="{{ route('buku.show', $item->id) }}" class="text-indigo-600 hover:underline">
                                            {{ $item->judul }}
                                        </a>
                                    </td>
                                    <td class="py-3.5 px-4 text-gray-600">{{ $item->penulis }}</td>
                                    <td class="py-3.5 px-4 text-gray-600">{{ $item->tahun_terbit }}</td>
                                    
                                    <td class="py-3.5 px-4">
                                        <div class="flex flex-wrap gap-2">
                                            @can('mengajukan peminjaman buku')
                                                <form action="{{ route('peminjaman.ajukan', $item->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs px-3 py-1 rounded-lg">Ajukan Pinjam</button>
                                                </form>
                                            @endcan

                                            @can('kelola data buku')
                                                <a href="{{ route('buku.edit', $item->id) }}" class="bg-amber-500 hover:bg-amber-600 text-white text-xs px-3 py-1 rounded-lg">Ubah</a>
                                                <form action="{{ route('buku.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus buku ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-xs px-3 py-1 rounded-lg">Hapus</button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-gray-400">Belum ada data buku.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>