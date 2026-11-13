<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Validasi Persetujuan & Pengembalian Buku (Petugas)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Daftar Transaksi Peminjaman & Persetujuan</h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700">Nama Peminjam</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700">Judul Buku</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700">Tanggal Pinjam</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700">Tanggal Kembali</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700">Status</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700">Aksi / Validasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($peminjamans as $peminjaman)
                                <tr>
                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        {{ $peminjaman->user?->name ?? 'User #' . $peminjaman->user_id }}
                                        <div class="text-xs text-gray-500">{{ $peminjaman->user?->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-800">{{ $peminjaman->buku?->judul ?? '-' }}</td>
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
                                        @if ($peminjaman->status === 'menunggu')
                                            <div class="flex items-center gap-2">
                                                <form action="{{ route('petugas.peminjaman.setujui', $peminjaman->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="px-2.5 py-1 text-xs border border-gray-300 rounded text-gray-700 hover:bg-gray-100" onclick="return confirm('Setujui peminjaman ini?')">
                                                        Setujui
                                                    </button>
                                                </form>
                                                <form action="{{ route('petugas.peminjaman.tolak', $peminjaman->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="px-2.5 py-1 text-xs border border-gray-300 rounded text-gray-700 hover:bg-gray-100" onclick="return confirm('Tolak peminjaman ini?')">
                                                        Tolak
                                                    </button>
                                                </form>
                                            </div>
                                        @elseif ($peminjaman->status === 'disetujui')
                                            <form action="{{ route('petugas.peminjaman.kembali', $peminjaman->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1 text-xs border border-gray-300 rounded text-gray-700 hover:bg-gray-100" onclick="return confirm('Tandai buku ini sudah dikembalikan?')">
                                                    Kembalikan
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xs text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada transaksi peminjaman.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
