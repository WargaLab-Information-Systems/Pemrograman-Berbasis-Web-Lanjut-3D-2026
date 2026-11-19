<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Peminjaman Saya') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div>
                <span class="text-xs font-bold text-indigo-600 tracking-wider uppercase">Sistem Informasi Perpustakaan</span>
                <h1 class="text-3xl font-extrabold text-gray-900 mt-1">Riwayat Peminjaman Buku</h1>
            </div>

            <x-flash-message />

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-gray-700 font-bold text-xs uppercase tracking-wider">
                                <th class="py-3 px-4">No</th>
                                <th class="py-3 px-4">Judul Buku</th>
                                <th class="py-3 px-4">Tanggal Pinjam</th>
                                <th class="py-3 px-4">Tanggal Kembali</th>
                                <th class="py-3 px-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($peminjaman as $index => $item)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="py-3.5 px-4 font-medium text-gray-900">{{ $index + 1 }}</td>
                                    <td class="py-3.5 px-4 font-semibold text-gray-800">{{ $item->buku->judul ?? 'Buku Tidak Ditemukan' }}</td>
                                    <td class="py-3.5 px-4 text-gray-600">{{ $item->tanggal_pinjam?->format('d-m-Y') ?? '-' }}</td>
                                    <td class="py-3.5 px-4 text-gray-600">{{ $item->tanggal_kembali?->format('d-m-Y') ?? '-' }}</td>
                                    <td class="py-3.5 px-4"><x-status-badge :status="$item->status" /></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-gray-400">
                                        Anda belum memiliki riwayat peminjaman buku.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
