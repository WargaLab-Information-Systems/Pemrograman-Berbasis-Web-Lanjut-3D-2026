@extends('layouts.main')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Laporan Rekapitulasi Perpustakaan</h5>
            <span class="badge bg-secondary">Total Pengguna: {{ $totalUser }} | Total Buku: {{ $bukus->count() }}</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 60px;" class="text-center">No</th>
                            <th>Kategori</th>
                            <th>Judul Buku</th>
                            <th>Penulis</th>
                            <th class="text-center">Tahun Terbit</th>
                            <th class="text-center">Status Koleksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bukus as $i => $buku)
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td>{{ $buku->kategori ? $buku->kategori->nama : 'Tanpa Kategori' }}</td>
                            <td>{{ $buku->judul }}</td>
                            <td>{{ $buku->penulis }}</td>
                            <td class="text-center">{{ $buku->tahun_terbit }}</td>
                            <td class="text-center"><span class="badge bg-success">Tersedia</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-3">Belum ada data laporan koleksi buku.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection