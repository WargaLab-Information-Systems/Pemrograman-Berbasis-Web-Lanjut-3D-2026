@extends('layouts.app')

@section('title', 'Daftar Buku - Perpustakaan Digital')

@section('content')
    <h2 class="page-title">Daftar Buku</h2>

    {{-- Pesan sukses / gagal (diisi oleh resources/js/buku.js) --}}
    <div id="pesan" class="alert" role="alert" hidden></div>

    {{-- Soal 2 & 3: formulir tambah / ubah buku --}}
    <section class="panel">
        <h3 id="judul-form">Tambah Buku</h3>

        <form id="form-buku" novalidate>
            <input type="hidden" id="buku-id">

            <div class="form-grid">
                <div class="form-group">
                    <label for="kategori_id">Kategori</label>
                    <select id="kategori_id" name="kategori_id">
                        <option value="">-- Pilih kategori --</option>
                        @foreach ($kategoris as $kategori)
                            <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                        @endforeach
                    </select>
                    <small class="field-error" data-error-for="kategori_id"></small>
                </div>

                <div class="form-group">
                    <label for="judul">Judul</label>
                    <input type="text" id="judul" name="judul" autocomplete="off">
                    <small class="field-error" data-error-for="judul"></small>
                </div>

                <div class="form-group">
                    <label for="penulis">Penulis</label>
                    <input type="text" id="penulis" name="penulis" autocomplete="off">
                    <small class="field-error" data-error-for="penulis"></small>
                </div>

                <div class="form-group">
                    <label for="tahun_terbit">Tahun Terbit</label>
                    <input type="number" id="tahun_terbit" name="tahun_terbit">
                    <small class="field-error" data-error-for="tahun_terbit"></small>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" id="btn-simpan" class="btn btn-primary">Simpan</button>
                <button type="button" id="btn-batal" class="btn btn-secondary" hidden>Batal</button>
            </div>
        </form>
    </section>

    {{-- Soal 1: tabel diisi dari GET /api/buku --}}
    <div class="table-wrap">
        <table id="tabel-buku" class="tabel-buku"
               data-api-url="{{ url('/api/buku') }}"
               data-detail-url="{{ route('buku.show', ':id') }}">
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
            <tbody id="tbody-buku">
                <tr><td colspan="6" class="kosong">Memuat data...</td></tr>
            </tbody>
        </table>
    </div>

    <div class="paginasi">
        <button type="button" id="btn-sebelumnya" class="btn btn-secondary btn-small">&larr; Sebelumnya</button>
        <span id="info-halaman"></span>
        <button type="button" id="btn-berikutnya" class="btn btn-secondary btn-small">Berikutnya &rarr;</button>
    </div>
@endsection
