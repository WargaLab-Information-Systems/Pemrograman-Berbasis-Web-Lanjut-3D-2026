@extends('layouts.main')

@section('title', 'Pengembalian')

@section('content')
    <h2 style="color:#2f7a4c;">Pengembalian Buku</h2>

    <h3>Sedang Dipinjam</h3>
    <table class="tbl">
        <thead><tr><th>Peminjam</th><th>Buku</th><th>Tgl Pinjam</th>@can('mencatat pengembalian buku')<th>Aksi</th>@endcan</tr></thead>
        <tbody>
        @forelse ($aktif as $p)
            <tr>
                <td>{{ $p->user->name ?? $p->anggota->nama ?? '-' }}</td>
                <td>{{ $p->buku->judul ?? '-' }}</td>
                <td>{{ $p->tanggal_pinjam?->format('d-m-Y') }}</td>
                @can('mencatat pengembalian buku')
                    <td>
                        <form method="POST" action="{{ route('pengembalian.store', $p) }}">@csrf
                            <button class="tombol" type="submit">Catat Pengembalian</button>
                        </form>
                    </td>
                @endcan
            </tr>
        @empty
            <tr><td colspan="4" style="text-align:center">Tidak ada buku yang sedang dipinjam.</td></tr>
        @endforelse
        </tbody>
    </table>

    <h3>Riwayat Pengembalian Terbaru</h3>
    <table class="tbl">
        <thead><tr><th>Peminjam</th><th>Buku</th><th>Tgl Pinjam</th><th>Tgl Kembali</th></tr></thead>
        <tbody>
        @forelse ($selesai as $p)
            <tr>
                <td>{{ $p->user->name ?? $p->anggota->nama ?? '-' }}</td>
                <td>{{ $p->buku->judul ?? '-' }}</td>
                <td>{{ $p->tanggal_pinjam?->format('d-m-Y') }}</td>
                <td>{{ $p->tanggal_kembali?->format('d-m-Y') }}</td>
            </tr>
        @empty
            <tr><td colspan="4" style="text-align:center">Belum ada pengembalian.</td></tr>
        @endforelse
        </tbody>
    </table>
@endsection
