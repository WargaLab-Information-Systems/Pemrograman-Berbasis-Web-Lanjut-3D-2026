@extends('layouts.dashboard')
@section('title', 'Laporan Perpustakaan')
@section('content')
<div class="dashboard-heading">
    <h2>Laporan Perpustakaan</h2>
    <p>Ringkasan koleksi dan aktivitas peminjaman.</p>
</div>

<section class="dashboard-grid">
    @foreach ($ringkasan as $label => $jumlah)
        <div class="dashboard-card">
            <h3>{{ $label }}</h3>
            <p class="dashboard-number">{{ $jumlah }}</p>
        </div>
    @endforeach
</section>

<section class="laporan-duo">

    <div class="laporan-card">
        <h3>Jumlah Peminjaman per Buku</h3>
        <div class="laporan-scroll">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($peminjamanPerBuku as $b)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $b->judul }}</td>
                            <td>{{ $b->kategori->nama ?? '-' }}</td>
                            <td>{{ $b->peminjamans_count }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="laporan-card">
        <h3>Jumlah Buku per Kategori</h3>
        <div class="laporan-scroll">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kategori</th>
                        <th>Jumlah Buku</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bukuPerKategori as $k)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $k->nama }}</td>
                            <td>{{ $k->bukus_count }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</section>

<div class="laporan-card laporan-full">
    <h3>5 Transaksi Peminjaman Terbaru</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Peminjam</th>
                <th>Buku</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transaksiTerbaru as $t)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $t->anggota->nama ?? '-' }}</td>
                    <td>{{ $t->buku->judul ?? '-' }}</td>
                    <td>{{ $t->tanggal_pinjam }}</td>
                    <td>{{ $t->tanggal_kembali ?? '-' }}</td>
                    <td><span class="badge badge-{{ $t->status }}">{{ ucfirst($t->status) }}</span></td>
                </tr>
            @empty
                <tr><td colspan="6">Belum ada transaksi.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection