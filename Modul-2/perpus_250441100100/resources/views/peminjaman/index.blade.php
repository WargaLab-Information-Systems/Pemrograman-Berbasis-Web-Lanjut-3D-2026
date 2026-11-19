<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Peminjaman') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                <div>
                    <span class="text-xs font-bold text-indigo-600 tracking-wider uppercase">Sistem Informasi Perpustakaan</span>
                    <h1 class="text-3xl font-extrabold text-gray-900 mt-1">Data Peminjaman</h1>
                    <p class="text-sm text-gray-500 mt-1">{{ $menunggu }} pengajuan menunggu persetujuan.</p>
                </div>

                <div class="flex flex-wrap gap-2 text-xs font-semibold">
                    <a href="{{ route('peminjaman.index') }}" class="px-3 py-2 rounded-lg {{ $status ? 'bg-white text-gray-700' : 'bg-slate-800 text-white' }}">Semua</a>
                    @foreach (['diajukan' => 'Diajukan', 'dipinjam' => 'Dipinjam', 'dikembalikan' => 'Dikembalikan', 'ditolak' => 'Ditolak'] as $key => $label)
                        <a href="{{ route('peminjaman.index', ['status' => $key]) }}" class="px-3 py-2 rounded-lg {{ $status === $key ? 'bg-slate-800 text-white' : 'bg-white text-gray-700' }}">{{ $label }}</a>
                    @endforeach
                </div>
            </div>

            <x-flash-message />

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
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
                                <th class="py-3 px-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($peminjaman as $index => $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-3.5 px-4">{{ $index + 1 }}</td>
                                    <td class="py-3.5 px-4">{{ $item->user->name ?? $item->anggota->nama ?? '-' }}</td>
                                    <td class="py-3.5 px-4 font-semibold text-gray-800">{{ $item->buku->judul ?? '-' }}</td>
                                    <td class="py-3.5 px-4 text-gray-600">{{ $item->tanggal_pinjam?->format('d-m-Y') ?? '-' }}</td>
                                    <td class="py-3.5 px-4 text-gray-600">{{ $item->tanggal_kembali?->format('d-m-Y') ?? '-' }}</td>
                                    <td class="py-3.5 px-4"><x-status-badge :status="$item->status" /></td>
                                    <td class="py-3.5 px-4">
                                        <div class="flex gap-2">
                                            @if ($item->status === 'diajukan')
                                                @can('memproses persetujuan peminjaman')
                                                    <form action="{{ route('peminjaman.setujui', $item) }}" method="POST">
                                                        @csrf
                                                        <button class="bg-green-600 hover:bg-green-700 text-white text-xs px-3 py-1 rounded-lg">Setujui</button>
                                                    </form>
                                                    <form action="{{ route('peminjaman.tolak', $item) }}" method="POST">
                                                        @csrf
                                                        <button class="bg-red-600 hover:bg-red-700 text-white text-xs px-3 py-1 rounded-lg">Tolak</button>
                                                    </form>
                                                @endcan
                                            @elseif ($item->status === 'dipinjam')
                                                @can('mencatat pengembalian buku')
                                                    <form action="{{ route('peminjaman.kembalikan', $item) }}" method="POST">
                                                        @csrf
                                                        <button class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs px-3 py-1 rounded-lg">Catat Pengembalian</button>
                                                    </form>
                                                @endcan
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-8 text-center text-gray-400">Belum ada data peminjaman.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
