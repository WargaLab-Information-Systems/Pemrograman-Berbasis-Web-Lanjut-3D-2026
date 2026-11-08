@extends('layouts.main')

@section('content')
<div class="container">
    
    <!-- Hero / Header Panel Petugas -->
    <div class="panel mb-4">
        <div class="panel-body d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <div>
                <span class="badge border bg-light text-secondary fw-normal px-2 py-1 mb-2" style="font-size:0.75rem;">
                    Operasional Sirkulasi
                </span>
                <h4 class="fw-bold mb-1 text-dark" style="letter-spacing: -0.02em;">Dashboard Petugas</h4>
                <p class="text-muted small mb-0">Selamat datang kembali, <strong>{{ auth()->user()->name }}</strong>. Pantau sirkulasi peminjaman, verifikasi antrean pengajuan, dan perbarui data katalog buku.</p>
            </div>
            <div class="text-md-end">
                <span class="text-muted small d-block">Hak Akses Aktif</span>
                <span class="badge bg-secondary text-white fw-semibold px-2 py-1" style="font-size:0.75rem; letter-spacing:0.04em;">PETUGAS</span>
            </div>
        </div>
    </div>

    <!-- Ringkasan Metrik Operasional -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="panel h-100 d-flex flex-column justify-content-between p-3">
                <div>
                    <span class="text-muted small">Total Koleksi Buku</span>
                    <div class="fs-2 fw-bold text-dark my-1" style="letter-spacing: -0.03em;">{{ $totalBuku }}</div>
                    <p class="text-muted small mb-3">Judul literatur terdaftar pada sistem basis data.</p>
                </div>
                <div>
                    <a href="{{ route('buku.index') }}" class="btn-subtle text-decoration-none d-inline-block w-100 text-center py-2">
                        Kelola Katalog Koleksi →
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="panel h-100 d-flex flex-column justify-content-between p-3">
                <div>
                    <span class="text-muted small">Menunggu Persetujuan</span>
                    <div class="fs-2 fw-bold text-dark my-1" style="letter-spacing: -0.03em;">{{ $totalPengajuan ?? 0 }}</div>
                    <p class="text-muted small mb-3">Permohonan peminjaman siswa yang belum diverifikasi.</p>
                </div>
                <div>
                    <a href="{{ route('peminjaman.index') }}" class="btn-subtle text-decoration-none d-inline-block w-100 text-center py-2">
                        Proses Antrean Sirkulasi →
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="panel h-100 d-flex flex-column justify-content-between p-3">
                <div>
                    <span class="text-muted small">Peminjaman Berjalan</span>
                    <div class="fs-2 fw-bold text-dark my-1" style="letter-spacing: -0.03em;">{{ $totalPeminjamanAktif ?? 0 }}</div>
                    <p class="text-muted small mb-3">Buku fisik yang sedang berstatus dipinjam oleh siswa.</p>
                </div>
                <div>
                    <a href="{{ route('peminjaman.index') }}" class="btn-subtle text-decoration-none d-inline-block w-100 text-center py-2">
                        Catat Pengembalian →
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel Alur Kerja Operasional -->
    <div class="panel">
        <div class="panel-header d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-0 fw-semibold text-dark">Pedoman Operasional Petugas</h6>
                <small class="text-muted">Aksi langsung untuk penanganan layanan perpustakaan harian.</small>
            </div>
            <span class="badge border bg-light text-secondary fw-normal px-2 py-1" style="font-size:0.75rem;">
                Standar Modul 5
            </span>
        </div>
        <div class="panel-body p-0">
            <div class="table-responsive">
                <table class="table-minimal">
                    <thead>
                        <tr>
                            <th style="width: 50px;" class="text-center">No</th>
                            <th style="width: 240px;">Layanan Operasional</th>
                            <th>Deskripsi Tugas</th>
                            <th style="width: 180px;" class="text-end">Akses Cepat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center text-muted" style="font-size:0.8125rem;">1</td>
                            <td class="fw-semibold text-dark">Sirkulasi & Persetujuan</td>
                            <td class="text-secondary small">Memverifikasi pengajuan peminjaman dari siswa dan mencatat pengembalian fisik buku.</td>
                            <td class="text-end">
                                <a href="{{ route('peminjaman.index') }}" class="btn-subtle text-decoration-none py-1 px-3" style="font-size:0.75rem;">
                                    Buka Sirkulasi
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center text-muted" style="font-size:0.8125rem;">2</td>
                            <td class="fw-semibold text-dark">Manajemen Koleksi Buku</td>
                            <td class="text-secondary small">Menambahkan judul buku baru, memperbarui informasi penulis/tahun, atau menghapus data buku usang.</td>
                            <td class="text-end">
                                <a href="{{ route('buku.index') }}" class="btn-subtle text-decoration-none py-1 px-3" style="font-size:0.75rem;">
                                    Kelola Buku
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center text-muted" style="font-size:0.8125rem;">3</td>
                            <td class="fw-semibold text-dark">Rekapitulasi Laporan</td>
                            <td class="text-secondary small">Melihat data rekapitulasi buku terdaftar dan akumulasi peminjaman perpustakaan.</td>
                            <td class="text-end">
                                <a href="{{ route('laporan.index') }}" class="btn-subtle text-decoration-none py-1 px-3" style="font-size:0.75rem;">
                                    Buka Laporan
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection