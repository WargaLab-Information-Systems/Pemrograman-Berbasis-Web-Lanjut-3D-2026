<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Status Peminjaman Saya') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div>
                <span class="text-xs font-bold text-indigo-600 tracking-wider uppercase">Sistem Informasi Perpustakaan</span>
                <h1 class="text-3xl font-extrabold text-gray-900 mt-1">Status Peminjaman Saya</h1>
                <p class="text-sm text-gray-500 mt-1">Pengajuan dan peminjaman yang sedang berjalan, serta pengajuan yang ditolak.</p>
            </div>

            <x-flash-message />

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-gray-700 font-bold text-xs uppercase tracking-wider">
                                <th class="py-3 px-4">No</th>
                                <th class="py-3 px-4">Judul Buku</th>
                                <th class="py-3 px-4">Tanggal</th>
                                <th class="py-3 px-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($peminjaman as $index => $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-3.5 px-4">{{ $index + 1 }}</td>
                                    <td class="py-3.5 px-4 font-semibold text-gray-800">{{ $item->buku->judul ?? 'Buku Tidak Ditemukan' }}</td>
                                    <td class="py-3.5 px-4 text-gray-600">{{ $item->tanggal_pinjam?->format('d-m-Y') ?? '-' }}</td>
                                    <td class="py-3.5 px-4"><x-status-badge :status="$item->status" /></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-gray-400">
                                        Tidak ada pengajuan atau peminjaman yang sedang berjalan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 text-sm">
                    <a href="{{ route('peminjaman.riwayat.sendiri') }}" class="text-indigo-600 hover:underline">Lihat seluruh riwayat &rarr;</a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
