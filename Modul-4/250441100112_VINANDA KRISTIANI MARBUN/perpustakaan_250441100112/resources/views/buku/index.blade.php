@extends('layouts.app')

@section('title', 'Manajemen Data Buku - Modul IV')

@section('content')

    <div class="page-title">
        <h2>Manajemen Buku</h2>
        <p>
            Koleksi buku perpustakaan 
        </p>
    </div>

    
    <div id="alert-box" class="alert" style="display: none;"></div>


    <div class="crud-card form-card">
        <div class="crud-card-header">
            <div>
                <span id="form-badge" class="badge-pill badge-primary">Form Tambah</span>
                <h3 id="form-title" style="margin-top: 6px;">Tambah Buku Baru</h3>
            </div>
            <button type="button" id="btn-cancel-top" class="btn btn-sm btn-secondary" style="display: none;" onclick="batalEdit()">
                ✕ Batal Ubah
            </button>
        </div>

        <form id="form-buku" onsubmit="handleFormSubmit(event)">
            
            <input type="hidden" id="buku-id" value="">

            <div class="form-grid">
                <div class="form-group">
                    <label for="kategori_id">Kategori Buku <span class="required">*</span></label>
                    <select id="kategori_id" name="kategori_id" class="form-control" required>
                        <option value="">-- Pilih Kategori --</option>
                        @isset($kategoris)
                            @foreach($kategoris as $kat)
                                <option value="{{ $kat->id }}">{{ $kat->nama }}</option>
                            @endforeach
                        @endisset
                    </select>
                </div>

                <div class="form-group">
                    <label for="judul">Judul Buku <span class="required">*</span></label>
                    <input type="text" id="judul" name="judul" class="form-control" placeholder="Masukkan judul buku" required minlength="3" maxlength="150">
                </div>

                <div class="form-group">
                    <label for="penulis">Penulis <span class="required">*</span></label>
                    <input type="text" id="penulis" name="penulis" class="form-control" placeholder="Masukkan nama penulis" required minlength="3" maxlength="100">
                </div>

                <div class="form-group">
                    <label for="tahun_terbit">Tahun Terbit <span class="required">*</span></label>
                    <input type="number" id="tahun_terbit" name="tahun_terbit" class="form-control" placeholder="Contoh: 2024" required min="1900" max="{{ date('Y') }}">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" id="btn-submit" class="btn btn-primary">
                    <span id="btn-submit-text">Simpan Buku</span>
                </button>
                <button type="button" id="btn-cancel" class="btn btn-secondary" style="display: none;" onclick="batalEdit()">
                    Batal
                </button>
            </div>
        </form>
    </div>

    
    <div class="crud-card table-card">
        <div class="crud-card-header">
            <div>
                <h3>Daftar Buku</h3>
                <small class="text-muted">Data diambil dari endpoint <code>GET /api/buku</code> melalui API Resource</small>
            </div>
            <button type="button" class="btn btn-sm btn-outline" onclick="tampilkanBuku()">
                🔄 Muat Ulang
            </button>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Judul Buku</th>
                        <th>Kategori</th>
                        <th>Penulis</th>
                        <th>Tahun Terbit</th>
                        <th style="width: 170px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tabel-buku-body">
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <span class="spinner"></span> Memuat data buku dari API...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('js/buku.js') }}"></script>
@endpush