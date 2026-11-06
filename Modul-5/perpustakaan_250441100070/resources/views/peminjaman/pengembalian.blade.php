@extends('layouts.app')

@section('title', 'Pengembalian - Perpustakaan Digital')

@section('content')
    <h2 class="page-title">Pengembalian Buku</h2>

    <h3>Sedang dipinjam</h3>
    <div class="table-wrap">
        <table class="tabel-buku">
            <thead>
                <tr><th>No</th><th>Anggota</th><th>Buku</th><th>Tgl Pinjam</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse ($dipinjam as $p)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $p->anggota->nama }}</td>
                        <td>{{ $p->buku->judul }}</td>
                        <td>{{ $p->tglPinjam() }}</td>
                        <td class="aksi">
                            @can('mencatat pengembalian buku')
                                <form method="POST" action="{{ route('peminjaman.kembali', $p) }}" class="inline-form">
                                    @csrf
                                    <button type="submit" class="btn btn-small btn-primary">Catat Pengembalian</button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="kosong">Tidak ada buku yang sedang dipinjam.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <h3 class="judul-seksi">Sudah dikembalikan (20 terbaru)</h3>
    <div class="table-wrap">
        <table class="tabel-buku">
            <thead>
                <tr><th>No</th><th>Anggota</th><th>Buku</th><th>Tgl Pinjam</th><th>Tgl Kembali</th></tr>
            </thead>
            <tbody>
                @forelse ($dikembalikan as $p)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $p->anggota->nama }}</td>
                        <td>{{ $p->buku->judul }}</td>
                        <td>{{ $p->tglPinjam() }}</td>
                        <td>{{ $p->tglKembali() }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="kosong">Belum ada pengembalian.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
