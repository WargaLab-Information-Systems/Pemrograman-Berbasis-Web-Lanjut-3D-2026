<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-lg bg-violet-500/10 text-violet-600 flex items-center justify-center text-base">
                        <i class="fa-solid fa-chart-pie"></i>
                    </span>
                    Laporan & Rekapitulasi Perpustakaan
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Laporan statistik peminjaman, buku populer, dan ringkasan aktivitas sirkulasi digital.
                </p>
            </div>
            <div>
                <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2.5 text-xs font-semibold rounded-xl text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 shadow-xs transition">
                    <i class="fa-solid fa-print"></i> Cetak Dokumen Laporan
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- 5 KARTU REKAP STATUS --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Transaksi</p>
                    <p class="text-2xl font-extrabold text-slate-800 mt-1">{{ number_format($totalPeminjaman) }}</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-amber-500">Menunggu</p>
                    <p class="text-2xl font-extrabold text-amber-600 mt-1">{{ number_format($totalMenunggu) }}</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-sky-500">Sedang Dipinjam</p>
                    <p class="text-2xl font-extrabold text-sky-600 mt-1">{{ number_format($totalDipinjam) }}</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-emerald-500">Dikembalikan</p>
                    <p class="text-2xl font-extrabold text-emerald-600 mt-1">{{ number_format($totalDikembalikan) }}</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-rose-500">Ditolak</p>
                    <p class="text-2xl font-extrabold text-rose-600 mt-1">{{ number_format($totalDitolak) }}</p>
                </div>
            </div>

            {{-- BUKU PALING POPULER --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-trophy text-amber-500"></i> 5 Buku Terpopuler (Paling Sering Dipinjam)
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    @forelse ($bukuPopuler as $index => $buku)
                        <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-4 relative overflow-hidden flex flex-col justify-between">
                            <span class="absolute top-2 right-2 text-2xl font-black text-slate-200">#{{ $index + 1 }}</span>
                            <div>
                                <span class="text-[10px] font-semibold text-indigo-600 uppercase tracking-wider">
                                    {{ $buku->kategoriRelasi->nama ?? 'Buku' }}
                                </span>
                                <h4 class="font-bold text-slate-800 text-sm mt-1 line-clamp-2">{{ $buku->judul }}</h4>
                                <p class="text-xs text-slate-500 mt-0.5">{{ $buku->penulis }}</p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-slate-200/60 text-xs font-semibold text-indigo-700 flex items-center justify-between">
                                <span>Total Dipinjam:</span>
                                <span class="px-2 py-0.5 rounded-full bg-indigo-100">{{ $buku->peminjamans_count }} kali</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 col-span-5">Belum ada statistik peminjaman buku.</p>
                    @endforelse
                </div>
            </div>

            {{-- TABEL DATA TRANSAKSI LENGKAP --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h3 class="text-base font-bold text-slate-800">
                        Log Seluruh Aktivitas Sirkulasi
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50/75 text-xs uppercase tracking-wider text-slate-400 font-semibold border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3">No</th>
                                <th class="px-6 py-3">Nama Anggota</th>
                                <th class="px-6 py-3">Judul Buku</th>
                                <th class="px-6 py-3">Tgl Pinjam</th>
                                <th class="px-6 py-3">Tgl Kembali</th>
                                <th class="px-6 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($riwayatLengkap as $index => $row)
                                <tr class="hover:bg-slate-50/40">
                                    <td class="px-6 py-3 text-xs text-slate-400">{{ $riwayatLengkap->firstItem() + $index }}</td>
                                    <td class="px-6 py-3 font-semibold text-slate-800">{{ $row->user->name ?? $row->anggota->nama ?? '-' }}</td>
                                    <td class="px-6 py-3 text-slate-800">{{ $row->buku->judul ?? '-' }}</td>
                                    <td class="px-6 py-3 text-xs">{{ \Carbon\Carbon::parse($row->tanggal_pinjam)->translatedFormat('d M Y') }}</td>
                                    <td class="px-6 py-3 text-xs">{{ $row->tanggal_kembali ? \Carbon\Carbon::parse($row->tanggal_kembali)->translatedFormat('d M Y') : '-' }}</td>
                                    <td class="px-6 py-3 text-xs">
                                        <span class="font-bold uppercase tracking-wider text-[10px]">
                                            {{ $row->status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($riwayatLengkap->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100">
                        {{ $riwayatLengkap->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
