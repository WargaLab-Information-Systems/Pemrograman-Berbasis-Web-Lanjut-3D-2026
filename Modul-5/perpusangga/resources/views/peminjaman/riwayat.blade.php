<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-xl text-slate-800 leading-tight">Riwayat Peminjaman Buku</h2>
                <p class="text-xs text-slate-500 mt-0.5">Daftar buku yang Anda pinjam beserta status pengembaliannya</p>
            </div>
            <span class="px-3 py-1 rounded-lg text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">Riwayat Saya</span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-sm font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white border border-slate-200/80 rounded-xl p-6 shadow-sm w-full">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">Status & Riwayat Transaksi</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50 text-xs font-bold text-slate-500 uppercase">
                                <th class="py-3 px-4">#</th>
                                <th class="py-3 px-4">Judul Buku</th>
                                <th class="py-3 px-4">Tanggal Pinjam</th>
                                <th class="py-3 px-4">Tanggal Kembali</th>
                                <th class="py-3 px-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                            @forelse($peminjamans as $index => $item)
                                <tr>
                                    <td class="py-3.5 px-4 font-semibold text-slate-400">{{ $index + 1 }}</td>
                                    <td class="py-3.5 px-4 font-medium text-slate-900">{{ $item->buku->judul ?? '-' }}</td>
                                    <td class="py-3.5 px-4 text-xs text-slate-600">{{ $item->tanggal_pinjam }}</td>
                                    <td class="py-3.5 px-4 text-xs text-slate-600">{{ $item->tanggal_kembali ?? '-' }}</td>
                                    <td class="py-3.5 px-4">
                                        @if($item->status == 'Dipinjam')
                                            <span class="px-2.5 py-1 rounded text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">Dipinjam</span>
                                        @else
                                            <span class="px-2.5 py-1 rounded text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Dikembalikan</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-slate-400 text-xs">Anda belum pernah meminjam buku.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>