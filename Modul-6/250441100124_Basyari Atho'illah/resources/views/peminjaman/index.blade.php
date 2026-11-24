<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-lg bg-teal-500/10 text-teal-600 flex items-center justify-center text-base">
                        <i class="fa-solid fa-clipboard-list"></i>
                    </span>
                    Kelola Peminjaman & Pengembalian Buku
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Kelola alur sirkulasi buku perpustakaan (Persetujuan, Peminjaman Berlangsung, dan Pengembalian).
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- FILTER DAN PENCARIAN --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
                <form action="{{ route('peminjaman.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <!-- Cari Judul / Nama -->
                    <div class="sm:col-span-2">
                        <label for="search" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">
                            Pencarian Data
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                                <i class="fa-solid fa-magnifying-glass text-xs"></i>
                            </span>
                            <input type="text" id="search" name="search" value="{{ request('search') }}"
                                class="w-full pl-9 rounded-xl border-slate-300 shadow-2xs focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2"
                                placeholder="Cari berdasarkan judul buku atau nama peminjam...">
                        </div>
                    </div>

                    <!-- Filter Status -->
                    <div>
                        <label for="status" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">
                            Filter Status
                        </label>
                        <div class="flex gap-2">
                            <select id="status" name="status" class="w-full rounded-xl border-slate-300 shadow-2xs focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2">
                                <option value="">Semua Status</option>
                                <option value="menunggu" {{ request('status') === 'menunggu' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                                <option value="dipinjam" {{ request('status') === 'dipinjam' ? 'selected' : '' }}>Sedang Dipinjam</option>
                                <option value="dikembalikan" {{ request('status') === 'dikembalikan' ? 'selected' : '' }}>Telah Dikembalikan</option>
                                <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                            <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-xl transition shrink-0">
                                Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- TABEL SELURUH PEMINJAMAN --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50/75 text-xs uppercase tracking-wider text-slate-400 font-semibold border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3.5">ID & Peminjam</th>
                                <th class="px-6 py-3.5">Buku Dipinjam</th>
                                <th class="px-6 py-3.5">Tgl Pinjam</th>
                                <th class="px-6 py-3.5">Tgl Kembali</th>
                                <th class="px-6 py-3.5">Status</th>
                                <th class="px-6 py-3.5 text-right">Aksi Operasional</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($peminjamans as $p)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-6 py-4">
                                        <div class="text-xs font-mono text-slate-400">#{{ $p->id }}</div>
                                        <div class="font-semibold text-slate-800">{{ $p->user->name ?? $p->anggota->nama ?? '-' }}</div>
                                        <div class="text-xs text-slate-400">{{ $p->user->email ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-slate-800">{{ $p->buku->judul ?? '-' }}</div>
                                        <div class="text-xs text-slate-400">{{ $p->buku->penulis ?? '-' }} &bull; {{ $p->buku->kategoriRelasi->nama ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-600">
                                        {{ \Carbon\Carbon::parse($p->tanggal_pinjam)->translatedFormat('d F Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-600">
                                        @if ($p->tanggal_kembali)
                                            <span class="text-emerald-700 font-semibold">
                                                {{ \Carbon\Carbon::parse($p->tanggal_kembali)->translatedFormat('d F Y') }}
                                            </span>
                                        @else
                                            <span class="text-slate-400 italic">Belum kembali</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($p->status === 'menunggu')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800">
                                                <i class="fa-solid fa-hourglass-half text-[10px]"></i> Menunggu
                                            </span>
                                        @elseif ($p->status === 'dipinjam')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-sky-100 text-sky-800">
                                                <i class="fa-solid fa-book-reader text-[10px]"></i> Dipinjam
                                            </span>
                                        @elseif ($p->status === 'dikembalikan')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800">
                                                <i class="fa-solid fa-circle-check text-[10px]"></i> Dikembalikan
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-rose-100 text-rose-800">
                                                <i class="fa-solid fa-xmark text-[10px]"></i> Ditolak
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="inline-flex items-center gap-1.5 justify-end">
                                            <a href="{{ route('peminjaman.show', $p->id) }}" class="px-2.5 py-1.5 rounded-lg text-xs font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 transition">
                                                Detail
                                            </a>

                                            {{-- Tombol Tindakan Cepat sesuai status --}}
                                            @if ($p->status === 'menunggu')
                                                <form action="{{ route('peminjaman.setujui', $p->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="px-2.5 py-1.5 rounded-lg text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 shadow-2xs transition">
                                                        Setujui
                                                    </button>
                                                </form>
                                                <form action="{{ route('peminjaman.tolak', $p->id) }}" method="POST" onsubmit="return confirm('Tolak permohonan?')">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="px-2 py-1.5 rounded-lg text-xs font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 transition">
                                                        Tolak
                                                    </button>
                                                </form>
                                            @elseif ($p->status === 'dipinjam')
                                                <form action="{{ route('peminjaman.kembalikan', $p->id) }}" method="POST" onsubmit="return confirm('Konfirmasi pengembalian buku ini?')">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="px-2.5 py-1.5 rounded-lg text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 shadow-2xs transition">
                                                        Kembalikan
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-slate-400">
                                        Tidak ada data peminjaman yang cocok dengan kriteria pencarian.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($peminjamans->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100">
                        {{ $peminjamans->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
