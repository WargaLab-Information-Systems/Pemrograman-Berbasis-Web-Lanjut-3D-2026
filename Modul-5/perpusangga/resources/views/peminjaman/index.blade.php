<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-xl text-slate-800 leading-tight">Kelola Peminjaman Buku</h2>
                <p class="text-xs text-slate-500 mt-0.5">Pencatatan transaksi peminjaman dan pengembalian koleksi perpustakaan</p>
            </div>
            <span class="px-3 py-1 rounded-lg text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">Sirkulasi</span>
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Form Peminjaman Baru -->
                <div class="bg-white border border-slate-200/80 rounded-xl p-6 shadow-sm h-fit">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">Tambah Peminjaman</h3>
                    <form action="{{ route('peminjaman.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="user_id" class="block text-xs font-semibold text-slate-600 mb-1.5">Siswa Peminjam</label>
                            <select id="user_id" name="user_id" required class="w-full border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                                <option value="">-- Pilih Siswa --</option>
                                @foreach($siswas as $siswa)
                                    <option value="{{ $siswa->id }}">{{ $siswa->name }} ({{ $siswa->email }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="buku_id" class="block text-xs font-semibold text-slate-600 mb-1.5">Buku</label>
                            <select id="buku_id" name="buku_id" required class="w-full border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                                <option value="">-- Pilih Buku --</option>
                                @foreach($bukus as $buku)
                                    <option value="{{ $buku->id }}">{{ $buku->judul }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="tanggal_pinjam" class="block text-xs font-semibold text-slate-600 mb-1.5">Tanggal Pinjam</label>
                            <input type="date" id="tanggal_pinjam" name="tanggal_pinjam" value="{{ date('Y-m-d') }}" required class="w-full border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-600 focus:outline-none transition" />
                        </div>

                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2.5 px-4 rounded-lg shadow-sm transition active:scale-[0.99]">
                            Simpan Transaksi
                        </button>
                    </form>
                </div>

                <!-- Tabel Transaksi Peminjaman -->
                <div class="lg:col-span-2 bg-white border border-slate-200/80 rounded-xl p-6 shadow-sm">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">Daftar Transaksi Peminjaman</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-200 bg-slate-50 text-xs font-bold text-slate-500 uppercase">
                                    <th class="py-3 px-4">Nama Siswa</th>
                                    <th class="py-3 px-4">Judul Buku</th>
                                    <th class="py-3 px-4">Tgl Pinjam</th>
                                    <th class="py-3 px-4">Status</th>
                                    <th class="py-3 px-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                                @forelse($peminjamans as $item)
                                    <tr>
                                        <td class="py-3.5 px-4 font-medium text-slate-900">{{ $item->user->name ?? '-' }}</td>
                                        <td class="py-3.5 px-4">{{ $item->buku->judul ?? '-' }}</td>
                                        <td class="py-3.5 px-4 text-xs text-slate-600">{{ $item->tanggal_pinjam }}</td>
                                        <td class="py-3.5 px-4">
                                            @if(strtolower(trim($item->status)) === 'dikembalikan')
                                                <span class="px-2.5 py-1 rounded text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    Dikembalikan
                                                </span>
                                            @else
                                                <span class="px-2.5 py-1 rounded text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                    Dipinjam
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-4">
                                            @if(strtolower(trim($item->status)) === 'dikembalikan')
                                                <span class="text-xs text-slate-400 font-medium">Selesai</span>
                                            @else
                                                <form action="{{ route('peminjaman.update', $item->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" onclick="return confirm('ACC Pengembalian buku ini?')" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-sm transition active:scale-[0.98]">
                                                        ACC Kembalikan
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-6 text-center text-slate-400 text-xs">Belum ada data peminjaman.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>