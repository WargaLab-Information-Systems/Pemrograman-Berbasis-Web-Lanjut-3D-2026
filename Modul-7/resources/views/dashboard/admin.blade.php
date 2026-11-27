@extends('layouts.main')

@section('content')
<div class="container">
    
    <!-- Hero / Header Panel Admin -->
    <div class="panel mb-4">
        <div class="panel-body d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <div>
                <span class="badge border bg-light text-secondary fw-normal px-2 py-1 mb-2" style="font-size:0.75rem;">
                    Konsol Manajemen Sistem
                </span>
                <h4 class="fw-bold mb-1 text-dark" style="letter-spacing: -0.02em;">Dashboard Administrator</h4>
                <p class="text-muted small mb-0">Selamat datang kembali, <strong>{{ auth()->user()->name }}</strong>. Pantau metrik data perpustakaan, kendali sirkulasi, dan otorisasi pengguna.</p>
            </div>
            <div class="text-md-end">
                <span class="text-muted small d-block">Hak Akses Aktif</span>
                <span class="badge bg-dark text-white fw-semibold px-2 py-1" style="font-size:0.75rem; letter-spacing:0.04em;">SUPER ADMIN</span>
            </div>
        </div>
    </div>

    <!-- Ringkasan Metrik Data -->
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
                    <span class="text-muted small">Pengguna Terdaftar</span>
                    <div class="fs-2 fw-bold text-dark my-1" style="letter-spacing: -0.03em;">{{ $totalUser }}</div>
                    <p class="text-muted small mb-3">Total akun pengguna (Admin, Petugas, dan Siswa).</p>
                </div>
                <div>
                    <a href="{{ route('admin.users') }}" class="btn-subtle text-decoration-none d-inline-block w-100 text-center py-2">
                        Kelola Data Akun & Role →
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="panel h-100 d-flex flex-column justify-content-between p-3">
                <div>
                    <span class="text-muted small">Layanan Sirkulasi</span>
                    <div class="fs-5 fw-bold text-dark my-2" style="letter-spacing: -0.02em;">Peminjaman & Laporan</div>
                    <p class="text-muted small mb-3">Akses log peminjaman, pengembalian, dan statistik.</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('peminjaman.index') }}" class="btn-subtle text-decoration-none flex-grow-1 text-center py-2">
                        Sirkulasi
                    </a>
                    <a href="{{ route('laporan.index') }}" class="btn-subtle text-decoration-none flex-grow-1 text-center py-2">
                        Laporan
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Pengguna Terdaftar Terbaru -->
    <div class="panel">
        <div class="panel-header d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-0 fw-semibold text-dark">Pengguna Terbaru</h6>
                <small class="text-muted">Lima akun pengguna terakhir yang dibuat atau terdaftar pada sistem.</small>
            </div>
            <a href="{{ route('admin.users') }}" class="btn-subtle text-decoration-none py-1 px-3" style="font-size:0.75rem;">
                Lihat Semua Pengguna
            </a>
        </div>
        <div class="table-responsive">
            <table class="table-minimal">
                <thead>
                    <tr>
                        <th style="width: 50px;" class="text-center">No</th>
                        <th>Nama Pengguna</th>
                        <th>Alamat Email</th>
                        <th style="width: 150px;" class="text-center">Role / Peran</th>
                        <th style="width: 180px;" class="text-end">Tgl Terdaftar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $u)
                    <tr>
                        <td class="text-center text-muted" style="font-size:0.8125rem;">{{ $index + 1 }}</td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $u->name }}</div>
                            @if($u->id === auth()->id())
                                <span class="badge border bg-light text-primary px-1 py-0" style="font-size:0.65rem;">Akun Anda</span>
                            @endif
                        </td>
                        <td class="text-secondary small">{{ $u->email }}</td>
                        <td class="text-center">
                            @php
                                $roleName = $u->roles->pluck('name')->first() ?? 'Tanpa Role';
                            @endphp
                            @if($roleName === 'admin')
                                <span class="status-pill status-ditolak" style="font-size:0.7rem;">ADMIN</span>
                            @elseif($roleName === 'petugas')
                                <span class="status-pill status-dipinjam" style="font-size:0.7rem;">PETUGAS</span>
                            @else
                                <span class="status-pill status-dikembalikan" style="font-size:0.7rem;">SISWA</span>
                            @endif
                        </td>
                        <td class="text-end text-secondary small" style="font-variant-numeric: tabular-nums;">
                            {{ $u->created_at ? $u->created_at->format('d M Y, H:i') : '—' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted small">Belum ada data pengguna yang tersimpan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection