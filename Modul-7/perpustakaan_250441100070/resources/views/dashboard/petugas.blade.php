@extends('layouts.app')

@section('title', 'Dashboard Petugas - Perpustakaan Digital')

@section('content')
    <h2 class="page-title">Dashboard Petugas</h2>
    <p>Selamat datang, {{ auth()->user()->name }}.</p>

    <div class="stat-grid">
        <div class="stat"><span class="stat__angka">{{ $jumlahBuku }}</span><span class="stat__label">Buku</span></div>
        <div class="stat"><span class="stat__angka">{{ $menunggu->count() }}</span><span class="stat__label">Pengajuan terbaru</span></div>
        <div class="stat"><span class="stat__angka">{{ $dipinjam }}</span><span class="stat__label">Sedang dipinjam</span></div>
    </div>

    <h3>Pengajuan menunggu persetujuan</h3>
    <div class="table-wrap">
        <table class="tabel-buku">
            <thead>
                <tr><th>Anggota</th><th>Buku</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse ($menunggu as $p)
                    <tr>
                        <td>{{ $p->anggota->nama }}</td>
                        <td>{{ $p->buku->judul }}</td>
                        <td class="aksi">
                            @can('memproses persetujuan peminjaman')
                                <form method="POST" action="{{ route('peminjaman.setujui', $p) }}" class="inline-form">
                                    @csrf
                                    <button type="submit" class="btn btn-small btn-primary">Setujui</button>
                                </form>
                                <form method="POST" action="{{ route('peminjaman.tolak', $p) }}" class="inline-form">
                                    @csrf
                                    <button type="submit" class="btn btn-small btn-danger">Tolak</button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="kosong">Tidak ada pengajuan yang menunggu.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="form-actions">
        @can('kelola data buku')
            <a href="{{ route('buku.index') }}" class="btn btn-primary">Kelola Buku</a>
        @endcan
        @can('kelola peminjaman')
            <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary">Peminjaman</a>
        @endcan
        @can('kelola pengembalian')
            <a href="{{ route('pengembalian.index') }}" class="btn btn-secondary">Pengembalian</a>
        @endcan
        @can('lihat laporan perpustakaan')
            <a href="{{ route('laporan.index') }}" class="btn btn-secondary">Laporan</a>
        @endcan
    </div>
@endsection
