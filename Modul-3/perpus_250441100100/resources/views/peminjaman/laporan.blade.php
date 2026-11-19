<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Laporan Perpustakaan
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div>
                <span class="text-xs font-bold text-indigo-600 tracking-wider uppercase">Sistem Informasi Perpustakaan</span>
                <h1 class="text-3xl font-extrabold text-gray-900 mt-1">Laporan Perpustakaan</h1>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach ([
                    'Total Buku' => $totalBuku,
                    'Total Pengguna' => $totalUser,
                    'Total Peminjaman' => $totalPeminjaman,
                    'Menunggu Persetujuan' => $diajukan,
                    'Sedang Dipinjam' => $dipinjam,
                    'Sudah Dikembalikan' => $dikembalikan,
                    'Ditolak' => $ditolak,
                ] as $label => $nilai)
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                        <p class="text-xs text-gray-500">{{ $label }}</p>
                        <p class="text-3xl font-bold mt-1">{{ $nilai }}</p>
                    </div>
                @endforeach
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h2 class="text-lg font-bold text-gray-900 mb-4">5 Buku Paling Sering Dipinjam</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-gray-700 font-bold text-xs uppercase tracking-wider">
                                <th class="py-3 px-4">No</th>
                                <th class="py-3 px-4">Judul</th>
                                <th class="py-3 px-4">Kategori</th>
                                <th class="py-3 px-4">Jumlah Dipinjam</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($bukuPopuler as $index => $item)
                                <tr>
                                    <td class="py-3 px-4">{{ $index + 1 }}</td>
                                    <td class="py-3 px-4 font-semibold">{{ $item->judul }}</td>
                                    <td class="py-3 px-4 text-gray-600">{{ $item->kategori->nama ?? '-' }}</td>
                                    <td class="py-3 px-4">{{ $item->peminjamans_count }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="py-6 text-center text-gray-400">Belum ada data.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Data Peminjaman dan Pengembalian</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-gray-700 font-bold text-xs uppercase tracking-wider">
                                <th class="py-3 px-4">No</th>
                                <th class="py-3 px-4">Peminjam</th>
                                <th class="py-3 px-4">Judul Buku</th>
                                <th class="py-3 px-4">Tgl Pinjam</th>
                                <th class="py-3 px-4">Tgl Kembali</th>
                                <th class="py-3 px-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($peminjaman as $index => $item)
                                <tr>
                                    <td class="py-3 px-4">{{ $index + 1 }}</td>
                                    <td class="py-3 px-4">{{ $item->user->name ?? $item->anggota->nama ?? '-' }}</td>
                                    <td class="py-3 px-4">{{ $item->buku->judul ?? '-' }}</td>
                                    <td class="py-3 px-4 text-gray-600">{{ $item->tanggal_pinjam?->format('d-m-Y') ?? '-' }}</td>
                                    <td class="py-3 px-4 text-gray-600">{{ $item->tanggal_kembali?->format('d-m-Y') ?? '-' }}</td>
                                    <td class="py-3 px-4"><x-status-badge :status="$item->status" /></td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="py-6 text-center text-gray-400">Belum ada data peminjaman.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
