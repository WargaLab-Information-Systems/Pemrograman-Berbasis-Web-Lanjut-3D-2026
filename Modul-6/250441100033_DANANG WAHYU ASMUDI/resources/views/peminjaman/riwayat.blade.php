<x-app-layout>
    <div class="bg-white p-6 rounded-lg shadow-md max-w-4xl mx-auto space-y-6">
        <div class="flex flex-wrap items-center justify-between border-b pb-4 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Riwayat Peminjaman Buku Saya</h1>
                <p class="text-gray-600 text-sm mt-1">Daftar buku yang diajukan, sedang dipinjam, atau pernah Anda kembalikan.</p>
            </div>
            <a href="{{ route('dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded text-sm transition">
                &larr; Dashboard
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded text-sm font-medium">
                {{ session('error') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-600">No</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600">Judul Buku</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600">Penulis</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600">Tanggal Pinjam</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600">Tanggal Kembali</th>
                        <th class="px-4 py-3 text-center font-medium text-gray-600">Status</th>
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
                            <td class="px-4 py-3 font-semibold text-gray-900">{{ $p->buku->judul ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $p->buku->penulis ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $p->tanggal_pinjam }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $p->tanggal_kembali ?? '-' }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($status === 'menunggu')
                                    <span class="bg-orange-100 text-orange-800 px-2.5 py-1 rounded-full text-xs font-semibold">Menunggu Persetujuan</span>
                                @elseif($status === 'ditolak')
                                    <span class="bg-red-100 text-red-800 px-2.5 py-1 rounded-full text-xs font-semibold">Ditolak</span>
                                @elseif($status === 'dipinjam')
                                    <span class="bg-yellow-100 text-yellow-800 px-2.5 py-1 rounded-full text-xs font-semibold">Sedang Dipinjam</span>
                                @else
                                    <span class="bg-green-100 text-green-800 px-2.5 py-1 rounded-full text-xs font-semibold">Sudah Dikembalikan</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                <!-- SOAL 1 (A4): Tautan menuju rute /peminjaman/{id} -->
                                <a href="{{ route('peminjaman.detail', $p->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white text-xs px-2.5 py-1.5 rounded transition font-medium">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                                Belum ada riwayat peminjaman buku untuk akun ini.
                                <div class="mt-2">
                                    <a href="{{ route('buku.index') }}" class="text-blue-600 hover:underline text-xs font-semibold">
                                        &rarr; Lihat daftar buku untuk meminjam
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
