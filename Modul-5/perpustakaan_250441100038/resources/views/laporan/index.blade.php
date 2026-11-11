@extends('layouts.main')

@section('title', 'Laporan Perpustakaan')

@section('content')
    <h2 class="page-title">Laporan Perpustakaan</h2>

    {{-- Filter --}}
    <form method="GET" action="{{ route('laporan.index') }}" class="panel filter">
        <div class="form-grup">
            <label for="dari">Dari tanggal</label>
            <input type="date" id="dari" name="dari" value="{{ $filter['dari'] ?? '' }}">
        </div>
        <div class="form-grup">
            <label for="sampai">Sampai tanggal</label>
            <input type="date" id="sampai" name="sampai" value="{{ $filter['sampai'] ?? '' }}">
        </div>
        <div class="form-grup">
            <label for="status">Status</label>
            <select id="status" name="status">
                <option value="">Semua status</option>
                @foreach (['diajukan', 'dipinjam', 'dikembalikan', 'ditolak'] as $st)
                    <option value="{{ $st }}" {{ ($filter['status'] ?? '') === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="tombol">Terapkan Filter</button>
        <a href="{{ route('laporan.index') }}" class="tombol tombol-abu">Reset</a>
        <a href="{{ route('laporan.cetak', $filter) }}" target="_blank" class="tombol tombol-biru">🖨️ Cetak Laporan</a>
    </form>

    <div class="card-grid">
        @foreach ($ringkasan as $label => $nilai)
            <div class="card-box"><div class="angka">{{ $nilai }}</div>{{ $label }}</div>
        @endforeach
    </div>

    <h3>Data Peminjaman</h3>
    <table class="tbl">
        <thead><tr><th>No</th><th>Peminjam</th><th>Buku</th><th>Tgl Pinjam</th><th>Tgl Kembali</th><th>Status</th></tr></thead>
        <tbody>
        @forelse ($peminjamans as $p)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $p->user->name ?? $p->anggota->nama ?? '-' }}</td>
                <td>{{ $p->buku->judul ?? '-' }}</td>
                <td>{{ $p->tanggal_pinjam?->format('d-m-Y') }}</td>
                <td>{{ $p->tanggal_kembali?->format('d-m-Y') ?? '-' }}</td>
                <td><span class="badge-st st-{{ $p->status }}">{{ ucfirst($p->status) }}</span></td>
            </tr>
        @empty
            <tr><td colspan="6" style="text-align:center">Tidak ada data pada filter ini.</td></tr>
        @endforelse
        </tbody>
    </table>

    <h3>Jumlah Buku per Kategori</h3>
    <table class="tbl">
        <thead><tr><th>Kategori</th><th>Jumlah Buku</th></tr></thead>
        <tbody>
        @foreach ($perKategori as $k)
            <tr><td>{{ $k->nama }}</td><td>{{ $k->bukus_count }}</td></tr>
        @endforeach
        </tbody>
    </table>

    <h3>Buku Paling Sering Dipinjam</h3>
    <table class="tbl">
        <thead><tr><th>Judul</th><th>Jumlah Peminjaman</th></tr></thead>
        <tbody>
        @foreach ($populer as $b)
            <tr><td>{{ $b->judul }}</td><td>{{ $b->peminjamans_count }}</td></tr>
        @endforeach
        </tbody>
    </table>
@endsection
