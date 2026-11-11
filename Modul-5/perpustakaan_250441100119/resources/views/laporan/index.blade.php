@extends('layouts.app')

@section('title', 'Laporan Perpustakaan')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"
     style="padding-top: 36px; padding-bottom: 32px;">

    <h1 class="text-2xl font-bold text-gray-800"
        style="margin-bottom: 12px;">
        Laporan Perpustakaan
    </h1>

    <p class="text-gray-600"
       style="margin-bottom: 32px;">
        Ringkasan data buku, anggota, dan aktivitas peminjaman perpustakaan.
    </p>

    <!-- Statistik Utama -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4"
         style="margin-bottom: 32px;">

        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-gray-600 mb-2">Total Buku</p>
            <p class="text-3xl font-bold text-blue-700">
                {{ $totalBuku }}
            </p>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-gray-600 mb-2">Total Anggota</p>
            <p class="text-3xl font-bold text-green-700">
                {{ $totalAnggota }}
            </p>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-gray-600 mb-2">Total Peminjaman</p>
            <p class="text-3xl font-bold text-purple-700">
                {{ $totalPeminjaman }}
            </p>
        </div>

    </div>

    <!-- Rekap Status -->
    <div class="bg-white shadow rounded-lg p-6"
         style="margin-bottom: 32px;">

        <h2 class="text-lg font-semibold text-gray-800 mb-4">
            Rekap Status Peminjaman
        </h2>

        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">

            @foreach ($rekapStatus as $status => $jumlah)
                <div class="border border-gray-200 rounded-lg p-4 text-center">
                    <p class="text-sm text-gray-600 mb-2">
                        {{ ucfirst($status) }}
                    </p>

                    <p class="text-2xl font-bold text-gray-800">
                        {{ $jumlah }}
                    </p>
                </div>
            @endforeach

        </div>
    </div>

    <!-- Tabel Peminjaman -->
    <div class="bg-white shadow rounded-lg overflow-hidden">

        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">
                Daftar Peminjaman Terbaru
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[850px] divide-y divide-gray-200">

                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left">No.</th>
                        <th class="px-4 py-3 text-left">Anggota</th>
                        <th class="px-4 py-3 text-left">Judul Buku</th>
                        <th class="px-4 py-3 text-left">Tanggal Pinjam</th>
                        <th class="px-4 py-3 text-left">Tanggal Kembali</th>
                        <th class="px-4 py-3 text-left">Status</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    @forelse ($peminjamans as $peminjaman)
                        <tr>
                            <td class="px-4 py-4">
                                {{ $peminjamans->firstItem() + $loop->index }}
                            </td>

                            <td class="px-4 py-4">
                                {{ $peminjaman->anggota?->nama ?? '-' }}
                            </td>

                            <td class="px-4 py-4">
                                {{ $peminjaman->buku?->judul ?? '-' }}
                            </td>

                            <td class="px-4 py-4">
                                {{ $peminjaman->tanggal_pinjam }}
                            </td>

                            <td class="px-4 py-4">
                                {{ $peminjaman->tanggal_kembali ?? '-' }}
                            </td>

                            <td class="px-4 py-4">
                                {{ ucfirst($peminjaman->status) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6"
                                class="px-4 py-6 text-center text-gray-500">
                                Belum ada data peminjaman.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $peminjamans->links() }}
    </div>

</div>

@endsection