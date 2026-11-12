<x-app-layout>
    <div class="bg-white p-6 rounded-lg shadow-md max-w-6xl mx-auto space-y-6">
        <div class="flex flex-wrap items-center justify-between border-b pb-4 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Data Peminjaman & Pengembalian Buku</h1>
                <p class="text-gray-600 text-sm mt-1">Kelola proses validasi persetujuan peminjaman dan pencatatan pengembalian buku.</p>
            </div>
            @role('admin')
                <a href="{{ route('admin.dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded text-sm transition">
                    &larr; Dashboard Admin
                </a>
            @else
                <a href="{{ route('petugas.dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded text-sm transition">
                    &larr; Dashboard Petugas
                </a>
            @endrole
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-600">No</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600">Nama Peminjam</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600">Judul Buku</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600">Tanggal Pinjam</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600">Tanggal Kembali</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600">Status</th>
                        <th class="px-4 py-3 text-center font-medium text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($peminjamans as $index => $p)
                        @php
                            // Prioritaskan kolom status jika ada, fallback ke tanggal_kembali
                            $status = $p->status ?? ($p->tanggal_kembali ? 'kembali' : 'dipinjam');
                        @endphp
                        <tr>
                            <td class="px-4 py-3 text-gray-500">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 font-semibold text-gray-900">{{ $p->anggota->nama ?? 'Anggota #' . $p->anggota_id }}</td>
                            <td class="px-4 py-3 text-gray-800">{{ $p->buku->judul ?? 'Buku #' . $p->buku_id }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $p->tanggal_pinjam }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $p->tanggal_kembali ?? '-' }}</td>
                            <td class="px-4 py-3">
                                @if($status === 'menunggu')
                                    <span class="bg-orange-100 text-orange-800 px-2.5 py-1 rounded-full text-xs font-semibold">Menunggu Persetujuan</span>
                                @elseif($status === 'dipinjam')
                                    <span class="bg-yellow-100 text-yellow-800 px-2.5 py-1 rounded-full text-xs font-semibold">Sedang Dipinjam</span>
                                @elseif($status === 'ditolak')
                                    <span class="bg-red-100 text-red-800 px-2.5 py-1 rounded-full text-xs font-semibold">Ditolak</span>
                                @else
                                    <span class="bg-green-100 text-green-800 px-2.5 py-1 rounded-full text-xs font-semibold">Sudah Dikembalikan</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($status === 'menunggu')
                                    <form action="{{ route('peminjaman.setujui', $p->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Setujui peminjaman ini?')" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs px-3 py-1.5 rounded font-medium transition">
                                            Setujui
                                        </button>
                                    </form>
                                    <form action="{{ route('peminjaman.tolak', $p->id) }}" method="POST" class="inline ml-1">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Tolak peminjaman ini?')" class="bg-red-600 hover:bg-red-700 text-white text-xs px-3 py-1.5 rounded font-medium transition">
                                            Tolak
                                        </button>
                                    </form>
                                @elseif($status === 'dipinjam')
                                    <form action="{{ route('peminjaman.kembalikan', $p->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Proses pengembalian buku ini?')" class="bg-blue-600 hover:bg-blue-700 text-white text-xs px-3 py-1.5 rounded font-medium transition">
                                            Proses Kembali
                                        </button>
                                    </form>
                                @elseif($status === 'ditolak')
                                    <span class="text-xs text-red-400">Ditolak</span>
                                @else
                                    <span class="text-xs text-gray-400">Selesai</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-6 text-center text-gray-500">Belum ada data transaksi peminjaman buku.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
