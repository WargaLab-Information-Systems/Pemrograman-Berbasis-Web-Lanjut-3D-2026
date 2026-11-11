@extends('layouts.main')

@section('title', 'Riwayat Peminjaman')

@section('content')
    <h2 style="color:#2f7a4c;">
        Riwayat Peminjaman {{ $pemilik->is(auth()->user()) ? 'Saya' : '— '.$pemilik->name }}
    </h2>

    <table class="tbl">
        <thead><tr><th>Buku</th><th>Tgl Pinjam</th><th>Tgl Kembali</th><th>Status</th></tr></thead>
        <tbody>
        @forelse ($peminjamans as $p)
            <tr>
                <td>{{ $p->buku->judul ?? '-' }}</td>
                <td>{{ $p->tanggal_pinjam?->format('d-m-Y') }}</td>
                <td>{{ $p->tanggal_kembali?->format('d-m-Y') ?? '-' }}</td>
                <td><span class="badge-st st-{{ $p->status }}">{{ ucfirst($p->status) }}</span></td>
            </tr>
        @empty
            <tr><td colspan="4" style="text-align:center">Belum ada riwayat peminjaman.</td></tr>
        @endforelse
        </tbody>
    </table>
@endsection
