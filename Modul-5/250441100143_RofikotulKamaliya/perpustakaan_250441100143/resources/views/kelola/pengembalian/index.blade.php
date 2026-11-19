@extends('layouts.dashboard')

@section('title', 'Kelola Pengembalian')

@section('content')
<div class="dashboard-heading">
    <h2>Kelola Pengembalian</h2>
    <p>Daftar buku yang sedang dipinjam. Catat pengembalian saat buku sudah kembali.</p>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Peminjam</th>
                <th>Buku</th>
                <th>Tgl Pinjam</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($peminjamans as $p)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $p->anggota->nama ?? '-' }}</td>
                    <td>{{ $p->buku->judul ?? '-' }}</td>
                    <td>{{ $p->tanggal_pinjam }}</td>
                    <td>
                        @can('mencatat pengembalian buku')
                            <form action="{{ route('kelola.pengembalian.store', $p->id) }}" method="POST" class="inline-form"
                                  onsubmit="return confirm('Catat buku ini sebagai sudah dikembalikan?')">
                                @csrf
                                <button type="submit" class="btn-sm">Catat Pengembalian</button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">Tidak ada buku yang sedang dipinjam.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
