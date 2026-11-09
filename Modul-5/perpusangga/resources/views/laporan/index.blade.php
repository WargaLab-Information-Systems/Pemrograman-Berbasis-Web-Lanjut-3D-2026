<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight">
            Laporan Perpustakaan
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Ringkasan Statistik -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Koleksi Buku</span>
                    <span class="text-2xl font-extrabold text-slate-900 mt-1 block">{{ $totalBuku }}</span>
                </div>
                <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Peminjaman</span>
                    <span class="text-2xl font-extrabold text-slate-900 mt-1 block">{{ $totalPeminjaman }}</span>
                </div>
                <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Buku Sedang Dipinjam</span>
                    <span class="text-2xl font-extrabold text-amber-600 mt-1 block">{{ $totalDipinjam }}</span>
                </div>
                <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Buku Sudah Dikembalikan</span>
                    <span class="text-2xl font-extrabold text-emerald-600 mt-1 block">{{ $totalDikembalikan }}</span>
                </div>
            </div>

            <!-- Tabel Rekapitulasi -->
            <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">Rekapitulasi Transaksi Peminjaman</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50 text-xs font-bold text-slate-500 uppercase">
                                <th class="py-3 px-4">#</th>
                                <th class="py-3 px-4">Peminjam</th>
                                <th class="py-3 px-4">Buku</th>
                                <th class="py-3 px-4">Tgl Pinjam</th>
                                <th class="py-3 px-4">Tgl Kembali</th>
                                <th class="py-3 px-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                            @forelse($peminjamans as $index => $item)
                                <tr>
                                    <td class="py-3 px-4 font-semibold text-slate-400">{{ $index + 1 }}</td>
                                    <td class="py-3 px-4 font-medium text-slate-900">{{ $item->user->name ?? '-' }}</td>
                                    <td class="py-3 px-4">{{ $item->buku->judul ?? '-' }}</td>
                                    <td class="py-3 px-4">{{ $item->tanggal_pinjam }}</td>
                                    <td class="py-3 px-4">{{ $item->tanggal_kembali ?? '-' }}</td>
                                    <td class="py-3 px-4">
                                        @if($item->status == 'Dipinjam')
                                            <span class="px-2 py-0.5 rounded text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">Dipinjam</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Dikembalikan</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-6 text-center text-slate-400 text-xs">Belum ada riwayat transaksi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>