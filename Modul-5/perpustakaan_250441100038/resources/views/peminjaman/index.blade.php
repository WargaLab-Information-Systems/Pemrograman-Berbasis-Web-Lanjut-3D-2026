@extends('layouts.main')

@section('title', 'Data Peminjaman')

@section('content')
    <h2 style="color:#2f7a4c;">Data Peminjaman</h2>

    <table class="tbl">
        <thead><tr><th>Peminjam</th><th>Buku</th><th>Tgl Pinjam</th><th>Tgl Kembali</th><th>Status</th>@can('memproses persetujuan peminjaman')<th>Aksi</th>@endcan</tr></thead>
        <tbody>
        @forelse ($peminjamans as $p)
            <tr>
                <td>{{ $p->user->name ?? $p->anggota->nama ?? '-' }}</td>
                <td>{{ $p->buku->judul ?? '-' }}</td>
                <td>{{ $p->tanggal_pinjam?->format('d-m-Y') }}</td>
                <td>{{ $p->tanggal_kembali?->format('d-m-Y') ?? '-' }}</td>
                <td><span class="badge-st st-{{ $p->status }}">{{ ucfirst($p->status) }}</span></td>
                @can('memproses persetujuan peminjaman')
                    <td>
                        @if ($p->status === 'diajukan')
                            <form method="POST" action="{{ route('peminjaman.setujui', $p) }}" style="display:inline">@csrf<button class="tombol" type="submit">Setujui</button></form>
                            <form method="POST" action="{{ route('peminjaman.tolak', $p) }}" style="display:inline">@csrf<button class="tombol tombol-merah" type="submit">Tolak</button></form>
                        @else
                            -
                        @endif
                    </td>
                @endcan
            </tr>
        @empty
            <tr><td colspan="6" style="text-align:center">Belum ada data peminjaman.</td></tr>
        @endforelse
        </tbody>
    </table>
@endsection
