@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <!-- Kotak Notifikasi Berhasil / Gagal -->
    <div id="alert-box" style="display: none; padding: 12px 16px; margin-bottom: 20px; border-radius: 6px;"></div>

    <!-- Formulir Tambah / Ubah Buku -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 id="form-title" class="mb-0">Tambah Buku Baru</h5>
        </div>
        <div class="card-body">
            <form id="form-tambah-buku">
                <!-- ID Tersembunyi untuk Mode Edit -->
                <input type="hidden" id="buku_id" value="">

                <div class="mb-3">
                    <label for="kategori_id" class="form-label">Kategori</label>
                    <select id="kategori_id" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>
                        @if(isset($kategoris))
                            @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="mb-3">
                    <label for="judul" class="form-label">Judul Buku</label>
                    <input type="text" id="judul" class="form-control" placeholder="Masukkan judul buku" required>
                </div>

                <div class="mb-3">
                    <label for="penulis" class="form-label">Penulis</label>
                    <input type="text" id="penulis" class="form-control" placeholder="Masukkan nama penulis" required>
                </div>

                <div class="mb-3">
                    <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
                    <input type="number" id="tahun_terbit" class="form-control" placeholder="Contoh: 2024" min="1900" max="{{ date('Y') }}" required>
                </div>

                <button type="submit" id="btn-submit" class="btn btn-primary">Simpan Buku</button>
                <button type="button" id="btn-cancel" class="btn btn-secondary" style="display: none;" onclick="resetForm()">Batal</button>
            </form>
        </div>
    </div>

    <!-- Tabel Daftar Buku -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Daftar Buku</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kategori</th>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Tahun Terbit</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="tabel-buku-body">
                    <tr>
                        <td colspan="6" style="text-align: center;">Memuat data buku...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@vite(['resources/js/buku.js'])
@endsection