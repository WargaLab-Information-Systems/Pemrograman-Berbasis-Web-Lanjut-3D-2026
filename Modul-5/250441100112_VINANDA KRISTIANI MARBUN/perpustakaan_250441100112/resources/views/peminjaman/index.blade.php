@extends('layouts.main')

@section('title', 'Kelola Peminjaman & Pengembalian')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Kelola Peminjaman & Pengembalian</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400">Persetujuan peminjaman, pemantauan pengembalian buku, dan pencatatan operasional perpustakaan.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-200 text-green-800 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-200 text-red-800 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- Filter Status -->
    <div class="mb-6 flex flex-wrap gap-2">
        <a href="{{ route('peminjaman.index') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ !request('status') ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200' }}">
            Semua
        </a>
        <a href="{{ route('peminjaman.index', ['status' => 'diajukan']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ request('status') === 'diajukan' ? 'bg-amber-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200' }}">
            Diajukan
        </a>
        <a href="{{ route('peminjaman.index', ['status' => 'dipinjam']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ request('status') === 'dipinjam' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200' }}">
            Dipinjam
        </a>
        <a href="{{ route('peminjaman.index', ['status' => 'menunggu_pengembalian']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ request('status') === 'menunggu_pengembalian' ? 'bg-purple-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200' }}">
            Menunggu Pengembalian
        </a>
        <a href="{{ route('peminjaman.index', ['status' => 'dikembalikan']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ request('status') === 'dikembalikan' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200' }}">
            Dikembalikan
        </a>
        <a href="{{ route('peminjaman.index', ['status' => 'ditolak']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ request('status') === 'ditolak' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200' }}">
            Ditolak
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Peminjam</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Judul Buku</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tgl Pinjam</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tgl Kembali</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($peminjamans as $index => $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-750">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $peminjamans->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            <div class="font-semibold">{{ $item->anggota?->nama ?? '-' }}</div>
                            <div class="text-xs text-gray-500">{{ $item->anggota?->email ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            <div class="font-medium">{{ $item->buku?->judul ?? '-' }}</div>
                            <div class="text-xs text-gray-500">{{ $item->buku?->kategori?->nama ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                            {{ $item->tanggal_pinjam ? \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                            {{ $item->tanggal_kembali ? \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($item->status === 'diajukan')
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200">
                                    Diajukan
                                </span>
                            @elseif($item->status === 'dipinjam')
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    Dipinjam
                                </span>
                            @elseif($item->status === 'menunggu_pengembalian')
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                    Menunggu Pengembalian
                                </span>
                            @elseif($item->status === 'dikembalikan')
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    Dikembalikan
                                </span>
                            @else
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                    Ditolak
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-2">
                            <a href="{{ route('peminjaman.show', $item->id) }}" class="px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200 rounded text-xs">
                                Detail
                            </a>

                            @if($item->status === 'diajukan')
                                @can('memproses persetujuan peminjaman')
                                    <form action="{{ route('peminjaman.setujui', $item->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-2.5 py-1 bg-green-600 hover:bg-green-700 text-white rounded text-xs">
                                            Setujui
                                        </button>
                                    </form>

                                    <form action="{{ route('peminjaman.tolak', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Tolak permohonan peminjaman ini?');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-2.5 py-1 bg-red-600 hover:bg-red-700 text-white rounded text-xs">
                                            Tolak
                                        </button>
                                    </form>
                                @endcan
                            @elseif(in_array($item->status, ['dipinjam', 'menunggu_pengembalian']))
                                @can('mencatat pengembalian buku')
                                    <form action="{{ route('peminjaman.konfirmasi', $item->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-2.5 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded text-xs">
                                            {{ $item->status === 'menunggu_pengembalian' ? 'Konfirmasi Kembali' : 'Catat Pengembalian' }}
                                        </button>
                                    </form>
                                @endcan
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            Tidak ada data peminjaman yang ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $peminjamans->links() }}
        </div>
    </div>
</div>
@endsection

