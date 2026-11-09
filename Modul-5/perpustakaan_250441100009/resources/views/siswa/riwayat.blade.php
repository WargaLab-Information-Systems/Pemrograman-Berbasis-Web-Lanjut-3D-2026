<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Riwayat Peminjaman Buku Saya (Siswa)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-2">Riwayat Peminjaman Milik Sendiri</h3>
                <p class="text-sm text-gray-500 mb-6">Berikut adalah status pengajuan dan daftar buku yang Anda pinjam.</p>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700">Judul Buku</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700">Tanggal Pinjam</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700">Tanggal Kembali</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700">Status</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($peminjamans as $peminjaman)
                                <tr>
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $peminjaman->buku?->judul ?? '-' }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $peminjaman->tanggal_pinjam }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $peminjaman->tanggal_kembali ?? '-' }}</td>
                                    <td class="px-6 py-4 text-xs text-gray-700">
                                        @if ($peminjaman->status === 'menunggu')
                                            Menunggu Persetujuan
                                        @elseif ($peminjaman->status === 'disetujui')
                                            Sedang Dipinjam
                                        @elseif ($peminjaman->status === 'dikembalikan')
                                            Sudah Dikembalikan
                                        @elseif ($peminjaman->status === 'ditolak')
                                            Ditolak
                                        @else
                                            {{ ucfirst($peminjaman->status) }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('siswa.riwayat.detail', $peminjaman->id) }}" class="text-xs border border-gray-300 rounded px-2.5 py-1 text-gray-700 hover:bg-gray-100">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">Anda belum memiliki riwayat peminjaman buku.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
