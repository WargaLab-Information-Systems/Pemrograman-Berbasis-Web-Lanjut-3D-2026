@extends('layouts.app')

@section('konten')
<div class="container py-4">
    <h2>Data Buku Perpustakaan</h2>

    <div id="alert-area"></div>

    <form id="form-buku">
        <input type="hidden" id="buku_id">

        <div class="mb-3">
            <label for="kategori_id">Kategori</label>
            <select id="kategori_id" required>
                <option value="">Pilih Kategori</option>
                <option value="1">Pemrograman</option>
                <option value="2">Teknologi</option>
                <option value="3">Pendidikan</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="judul">Judul Buku</label>
            <input type="text" id="judul" required>
        </div>

        <div class="mb-3">
            <label for="penulis">Penulis</label>
            <input type="text" id="penulis" required>
        </div>

        <div class="mb-3">
            <label for="tahun_terbit">Tahun Terbit</label>
            <input type="number" id="tahun_terbit"
                   min="1900" max="2100" required>
        </div>

        <button type="submit" id="tombol-simpan">
            Simpan Buku
        </button>

        <button type="button" id="tombol-batal" hidden>
            Batal Ubah
        </button>
    </form>

    <hr>

    <h3>Daftar Buku</h3>

    <div style="overflow-x: auto;">
        <table border="1" cellpadding="8" width="100%">
            <thead>
                <tr>
                    <th>Kategori</th>
                    <th>Judul</th>
                    <th>Penulis</th>
                    <th>Tahun</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="daftar-buku">
                <tr>
                    <td colspan="5">Memuat data buku...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/buku-client.js') }}"></script>
@endpush
@endsection
