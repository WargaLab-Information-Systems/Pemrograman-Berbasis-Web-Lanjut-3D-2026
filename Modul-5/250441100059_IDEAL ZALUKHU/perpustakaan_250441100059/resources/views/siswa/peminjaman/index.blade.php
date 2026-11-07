@php
    $layout = match (true) {
        auth()->user()->hasRole('admin') => 'admin-layout',
        auth()->user()->hasRole('petugas') => 'petugas-layout',
        default => 'siswa-layout',
    };

    $dashboardRoute ??= 'siswa.dashboard';
@endphp

<x-dynamic-component :component="$layout">
    <div class="p-6">
        <a href="{{ route($dashboardRoute) }}" class="inline-flex items-center gap-2 font-semibold text-red-800 hover:text-red-950">
            <span aria-hidden="true" class="text-xl">&larr;</span> Kembali
        </a>

        <h1 class="text-2xl font-bold text-gray-800 mt-6">Peminjaman Saya</h1>
        <p class="mt-2 text-gray-600">Status pengajuan dan riwayat peminjaman milik Anda.</p>

        @if (session('success'))
            <div class="mt-4 rounded bg-green-100 p-3 text-green-800">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="mt-4 rounded bg-red-100 p-3 text-red-800">{{ session('error') }}</div>
        @endif

        <div class="mt-6 overflow-x-auto rounded-lg bg-white p-6 shadow-sm">
            <table class="w-full border-collapse border border-gray-300">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border p-3">No</th>
                        <th class="border p-3">Judul Buku</th>
                        <th class="border p-3">Penulis</th>
                        <th class="border p-3">Tanggal Pengajuan</th>
                        <th class="border p-3">Tanggal Kembali</th>
                        <th class="border p-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($peminjamans as $peminjaman)
                        <tr>
                            <td class="border p-3 text-center">{{ $loop->iteration }}</td>
                            <td class="border p-3">{{ $peminjaman->buku->judul ?? '-' }}</td>
                            <td class="border p-3">{{ $peminjaman->buku->penulis ?? '-' }}</td>
                            <td class="border p-3 text-center">{{ $peminjaman->tanggal_pinjam }}</td>
                            <td class="border p-3 text-center">{{ $peminjaman->tanggal_kembali ?? '-' }}</td>
                            <td class="border p-3 text-center">
                                @switch($peminjaman->status)
                                    @case('menunggu')
                                        <span class="font-medium text-amber-600">Menunggu Persetujuan</span>
                                        @break
                                    @case('ditolak')
                                        <span class="font-medium text-red-600">Ditolak</span>
                                        @break
                                    @case('dipinjam')
                                    @case('disetujui')
                                        <span class="inline-flex rounded-full border border-blue-200 bg-blue-50 px-2.5 py-1 font-medium text-blue-700">● Sedang Dipinjam</span>
                                        @break
                                    @case('dikembalikan')
                                        <span class="inline-flex rounded-full border border-green-200 bg-green-50 px-2.5 py-1 font-medium text-green-700">✓ Dikembalikan</span>
                                        @break
                                    @default
                                        <span class="font-medium text-orange-600">Sedang Dipinjam</span>
                                @endswitch
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="border p-4 text-center text-gray-500">
                                Belum ada pengajuan atau riwayat peminjaman.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-dynamic-component>