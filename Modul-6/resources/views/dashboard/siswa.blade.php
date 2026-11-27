@extends('layouts.main')

@section('content')
<div class="container">
    
    <!-- Hero / Ringkasan Akun -->
    <div class="panel mb-4">
        <div class="panel-body d-flex justify-content-between align-items-center">
            <div>
                <span class="badge border bg-light text-secondary fw-normal px-2 py-1 mb-2" style="font-size:0.75rem;">Portal Anggota</span>
                <h4 class="fw-bold mb-1" style="letter-spacing: -0.02em;">Selamat Datang, {{ auth()->user()->name }}</h4>
                <p class="text-muted small mb-0">Kelola peminjaman literatur, periksa status berkas, dan akses katalog buku secara mandiri.</p>
            </div>
            <div class="text-end d-none d-md-block">
                <span class="text-muted small">Status Akun</span>
                <div class="fw-semibold text-dark small">Siswa Terverifikasi</div>
            </div>
        </div>
    </div>

    <!-- Metrik & Aksi -->
    <div class="row g-3">
        <div class="col-md-4">
            <div class="panel h-100 d-flex flex-column justify-content-between p-3">
                <div>
                    <span class="text-muted small">Total Koleksi Terdaftar</span>
                    <div class="fs-2 fw-bold text-dark my-1" style="letter-spacing: -0.03em;">{{ $totalBuku }}</div>
                    <p class="text-muted small mb-3">Buku siap dibaca dan diajukan peminjaman.</p>
                </div>
                <div>
                    <a href="{{ route('buku.index') }}" class="btn-subtle text-decoration-none d-inline-block w-100 text-center py-2">
                        Buka Katalog Koleksi →
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="panel h-100 d-flex flex-column justify-content-between p-3">
                <div>
                    <span class="text-muted small">Peminjaman Berjalan</span>
                    <div class="fs-2 fw-bold text-dark my-1" style="letter-spacing: -0.03em;">{{ $pinjamanAktif ?? 0 }}</div>
                    <p class="text-muted small mb-3">Buku berstatus menunggu atau sedang dipinjam.</p>
                </div>
                <div>
                    <a href="{{ route('peminjaman.riwayat.sendiri') }}" class="btn-subtle text-decoration-none d-inline-block w-100 text-center py-2">
                        Lihat Riwayat Saya →
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="panel h-100 d-flex flex-column justify-content-between p-3">
                <div>
                    <span class="text-muted small">Pengaturan Akun</span>
                    <div class="fs-5 fw-bold text-dark my-2" style="letter-spacing: -0.02em;">Profil Pribadi</div>
                    <p class="text-muted small mb-3">Perbarui nama tampilan dan alamat surat elektronik Anda.</p>
                </div>
                <div>
                    <a href="{{ route('profile.edit') }}" class="btn-subtle text-decoration-none d-inline-block w-100 text-center py-2">
                        Kelola Profil →
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection