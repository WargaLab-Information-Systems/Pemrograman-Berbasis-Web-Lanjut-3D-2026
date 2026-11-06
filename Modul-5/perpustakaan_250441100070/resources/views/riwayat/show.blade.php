@extends('layouts.app')

@section('title', 'Peminjaman - ' . $user->name)

@section('content')
    <h2 class="page-title">
        @if (auth()->user()->is($user))
            Peminjaman Saya
        @else
            Peminjaman: {{ $user->name }}
        @endif
    </h2>

    @can('melihat status peminjaman sendiri')
        <h3>Status peminjaman</h3>
        <div class="table-wrap">
            <table class="tabel-buku">
                <thead>
                    <tr><th>No</th><th>Buku</th><th>Tgl Pinjam</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @forelse ($aktif as $p)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $p->buku->judul }}</td>
                            <td>{{ $p->tglPinjam() }}</td>
                            <td><span class="badge badge-{{ $p->status }}">{{ $p->status }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="kosong">Tidak ada pengajuan atau peminjaman yang sedang berjalan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endcan

    @can('melihat riwayat peminjaman sendiri')
        <h3 class="judul-seksi">Riwayat peminjaman</h3>
        <div class="table-wrap">
            <table class="tabel-buku">
                <thead>
                    <tr><th>No</th><th>Buku</th><th>Tgl Pinjam</th><th>Tgl Kembali</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @forelse ($riwayat as $p)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $p->buku->judul }}</td>
                            <td>{{ $p->tglPinjam() }}</td>
                            <td>{{ $p->tglKembali() }}</td>
                            <td><span class="badge badge-{{ $p->status }}">{{ $p->status }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="kosong">Belum ada riwayat peminjaman.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endcan
@endsection
