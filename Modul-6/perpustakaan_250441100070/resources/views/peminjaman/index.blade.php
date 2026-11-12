@extends('layouts.app')

@section('title', 'Peminjaman - Perpustakaan Digital')

@section('content')
    <h2 class="page-title">Data Peminjaman</h2>

    <div class="table-wrap">
        <table class="tabel-buku">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Anggota</th>
                    <th>Buku</th>
                    <th>Tgl Pinjam</th>
                    <th>Status</th>
                    <th>Diproses oleh</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($peminjamans as $p)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $p->anggota->nama }}</td>
                        <td>{{ $p->buku->judul }}</td>
                        <td>{{ $p->tglPinjam() }}</td>
                        <td><span class="badge badge-{{ $p->status }}">{{ $p->status }}</span></td>
                        <td>{{ $p->pemroses->name ?? '-' }}</td>
                        <td class="aksi">
                            @if ($p->status === 'diajukan')
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
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="kosong">Belum ada data peminjaman.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
