<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-600 flex items-center justify-center text-base">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </span>
                    Riwayat Peminjaman Buku Saya
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Halo, <strong>{{ Auth::user()->name }}</strong>. Berikut adalah daftar buku yang pernah dan sedang Anda pinjam di perpustakaan.
                </p>
            </div>
            <div>
                <a href="{{ route('buku.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-2xs transition">
                    <i class="fa-solid fa-book-open"></i> Cari Buku Lainnya
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50/75 text-xs uppercase tracking-wider text-slate-400 font-semibold border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3.5">No</th>
                                <th class="px-6 py-3.5">Judul Buku</th>
                                <th class="px-6 py-3.5">Tanggal Pengajuan/Pinjam</th>
                                <th class="px-6 py-3.5">Tanggal Dikembalikan</th>
                                <th class="px-6 py-3.5">Status Peminjaman</th>
                                <th class="px-6 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($peminjamans as $index => $p)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-6 py-4 font-semibold text-slate-400 text-xs">
                                        {{ $peminjamans->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-slate-800">{{ $p->buku->judul ?? '-' }}</div>
                                        <div class="text-xs text-slate-400">
                                            Penulis: {{ $p->buku->penulis ?? '-' }} &bull; {{ $p->buku->kategoriRelasi->nama ?? '-' }}
                                        </div>
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
                                            <span class="text-slate-400 italic">Belum dikembalikan</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($p->status === 'menunggu')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full bg-amber-50 text-amber-700 border border-amber-200">
                                                <i class="fa-solid fa-hourglass-half text-[10px]"></i> Menunggu Verifikasi
                                            </span>
                                        @elseif ($p->status === 'dipinjam')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full bg-sky-50 text-sky-700 border border-sky-200">
                                                <i class="fa-solid fa-book-reader text-[10px]"></i> Sedang Dipinjam
                                            </span>
                                        @elseif ($p->status === 'dikembalikan')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <i class="fa-solid fa-circle-check text-[10px]"></i> Selesai Dikembalikan
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full bg-rose-50 text-rose-700 border border-rose-200">
                                                <i class="fa-solid fa-circle-xmark text-[10px]"></i> Ditolak
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('peminjaman.show', $p->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-medium text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition">
                                            <i class="fa-solid fa-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                        <div class="flex flex-col items-center justify-center gap-2">
                                            <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center text-xl">
                                                <i class="fa-solid fa-book-open"></i>
                                            </div>
                                            <p class="font-medium text-slate-600 text-sm">Belum Ada Riwayat Peminjaman</p>
                                            <p class="text-xs text-slate-400 max-w-sm">Anda belum pernah mengajukan peminjaman buku. Buka katalog untuk memilih buku bacaan Anda.</p>
                                            <a href="{{ route('buku.index') }}" class="mt-2 inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 transition">
                                                Buka Katalog Buku &rarr;
                                            </a>
                                        </div>
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
