@extends('layouts.main')

@section('content')
<div class="container" id="buku-app" data-can-manage="{{ auth()->user()->can('kelola data buku') ? 'true' : 'false' }}">
    
    <!-- Notifikasi Dinamis Asinkron -->
    <div id="alert-box" style="display: none; padding: 10px 14px; margin-bottom: 1.25rem; border-radius: 6px; font-size: 0.875rem;"></div>

    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="letter-spacing: -0.02em;">Katalog Koleksi Buku</h4>
            <p class="text-muted small mb-0">Daftar buku literatur yang terdaftar pada pangkalan data perpustakaan.</p>
        </div>
        @can('mengajukan peminjaman buku')
            <span class="badge border bg-white text-secondary py-2 px-3" style="font-size: 0.75rem; font-weight: 500;">
                Akses Peminjaman: Aktif
            </span>
        @endcan
    </div>

    <!-- form kelola data buku) -->
    @can('kelola data buku')
    <div class="panel mb-4">
        <div class="panel-header d-flex justify-content-between align-items-center">
            <h6 id="form-title" class="mb-0 fw-semibold fs-6">Tambah Koleksi Baru</h6>
            <span class="text-muted" style="font-size: 0.75rem;">Operasi CRUD RESTful</span>
        </div>
        <div class="panel-body">
            <form id="form-tambah-buku" novalidate>
                <input type="hidden" id="buku_id" value="">

                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="kategori_id" class="form-label">Kategori</label>
                        <select id="kategori_id" class="form-select" required>
                            <option value="">Pilih Kategori</option>
                            @if(isset($kategoris))
                                @foreach ($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="judul" class="form-label">Judul Buku</label>
                        <input type="text" id="judul" class="form-control" placeholder="Contoh: Pemrograman Web Lanjut" required>
                    </div>

                    <div class="col-md-3">
                        <label for="penulis" class="form-label">Penulis</label>
                        <input type="text" id="penulis" class="form-control" placeholder="Nama penulis" required>
                    </div>

                    <div class="col-md-2">
                        <label for="tahun_terbit" class="form-label">Tahun</label>
                        <input type="number" id="tahun_terbit" class="form-control" placeholder="YYYY" required>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-3 pt-3 border-top">
                    <button type="submit" id="btn-submit" class="btn-brand">Simpan Koleksi</button>
                    <button type="button" id="btn-cancel" class="btn-subtle" style="display: none;" onclick="resetForm()">Batal</button>
                </div>
            </form>
        </div>
    </div>
    @endcan

    <!-- Tabel Daftar Koleksi -->
    <div class="panel">
        <div class="table-responsive">
            <table class="table-minimal">
                <thead>
                    <tr>
                        <th style="width: 50px;" class="text-center">No</th>
                        <th style="width: 140px;">Kategori</th>
                        <th>Judul Buku</th>
                        <th style="width: 200px;">Penulis</th>
                        <th style="width: 100px;" class="text-center">Tahun</th>
                        <th style="width: 220px;" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tabel-buku-body">
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted small">Memuat pangkalan data...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@vite(['resources/js/buku.js'])
@endsection