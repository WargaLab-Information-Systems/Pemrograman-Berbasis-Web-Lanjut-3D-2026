@extends('layouts.main')

@section('title', 'Kelola Transaksi Peminjaman')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-lg font-bold text-gray-800">🔄 Kelola Peminjaman & Pengembalian</h3>
            <p class="text-sm text-gray-600">Daftar seluruh transaksi sirkulasi buku perpustakaan.</p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-200">
            <thead>
                <tr class="bg-gray-100 text-left text-xs uppercase tracking-wider text-gray-600">
                    <th class="border p-3">ID</th>
                    <th class="border p-3">Nama Peminjam</th>
                    <th class="border p-3">Judul Buku</th>
                    <th class="border p-3">Tgl Pinjam</th>
                    <th class="border p-3">Tgl Kembali</th>
                    <th class="border p-3 text-center">Status</th>
                    <th class="border p-3 text-center">Aksi Petugas</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse($peminjamans as $trx)
                <tr class="hover:bg-gray-50">
                    <td class="border p-3 font-semibold">#{{ $trx->id }}</td>
                    <td class="border p-3">{{ $trx->anggota->nama ?? 'Anggota #'.$trx->anggota_id }} ({{ $trx->anggota->email ?? '-' }})</td>
                    <td class="border p-3 font-medium text-gray-900">{{ $trx->buku->judul ?? '-' }}</td>
                    <td class="border p-3 text-gray-600">{{ $trx->tanggal_pinjam }}</td>
                    <td class="border p-3 text-gray-600">{{ $trx->tanggal_kembali ?? '-' }}</td>
                    <td class="border p-3 text-center">
                        @if($trx->tanggal_kembali)
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                Dikembalikan
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800">
                                Sedang Dipinjam
                            </span>
                        @endif
                    </td>
                    <td class="border p-3 text-center">
                        @if(!$trx->tanggal_kembali)
                            @can('mencatat pengembalian buku')
                            <form method="POST" action="{{ route('peminjaman.proses', $trx->id) }}" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-medium rounded shadow-sm transition">
                                    Catat Kembali
                                </button>
                            </form>
                            @else
                                <span class="text-xs text-gray-400">Tidak ada aksi</span>
                            @endcan
                        @else
                            <span class="text-xs text-gray-400">Selesai</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="border p-6 text-center text-gray-500">
                        Belum ada data transaksi peminjaman buku.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $peminjamans->links() }}
    </div>
</div>
@endsection
