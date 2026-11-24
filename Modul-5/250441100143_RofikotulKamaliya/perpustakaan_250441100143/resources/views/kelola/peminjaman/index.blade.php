@extends('layouts.dashboard')

@section('title', 'Kelola Peminjaman')

@section('content')
<div class="page-top">
    <div class="dashboard-heading">
        <h2>Kelola Peminjaman</h2>
        <p>Setujui atau tolak pengajuan peminjaman buku.</p>
    </div>

    <form method="GET" action="{{ route('kelola.peminjaman.index') }}">
        <select name="status" onchange="this.form.submit()">
            <option value="">Semua status</option>
            @foreach (['diajukan', 'dipinjam', 'ditolak', 'dikembalikan'] as $opsi)
                <option value="{{ $opsi }}" {{ $status === $opsi ? 'selected' : '' }}>{{ ucfirst($opsi) }}</option>
            @endforeach
        </select>
    </form>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Peminjam</th>
                <th>Buku</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
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
                    <td>{{ $p->tanggal_kembali ?? '-' }}</td>
                    <td><span class="badge badge-{{ $p->status }}">{{ ucfirst($p->status) }}</span></td>
                    <td>
                        @if ($p->status === 'diajukan')
                            @can('memproses persetujuan peminjaman')
                                <form action="{{ route('kelola.peminjaman.setujui', $p->id) }}" method="POST" class="inline-form">
                                    @csrf
                                    <button type="submit" class="btn-sm">Setujui</button>
                                </form>
                                <form action="{{ route('kelola.peminjaman.tolak', $p->id) }}" method="POST" class="inline-form">
                                    @csrf
                                    <button type="submit" class="btn-sm btn-danger">Tolak</button>
                                </form>
                            @endcan
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7">Belum ada data peminjaman.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
