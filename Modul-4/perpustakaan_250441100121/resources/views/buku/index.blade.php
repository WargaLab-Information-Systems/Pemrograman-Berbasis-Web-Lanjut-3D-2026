
@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')
<div class="book-page">
    <h2>Manajemen Buku</h2>
    <p>Kelola data buku perpustakaan.</p>

    <div id="pesan"></div>

    <section class="book-section">
        <h3 id="judulForm">Tambah Buku</h3>

        <form id="formBuku">
            <input type="hidden" id="buku_id">

            <div class="form-grid">
                <div>
                    <label for="kategori_id">Kategori</label>
                    <select id="kategori_id" required>
                        <option value="">Pilih kategori</option>
                    </select>
                </div>

                <div>
                    <label for="judul">Judul Buku</label>
                    <input id="judul" minlength="3" maxlength="150"
                        placeholder="Judul buku" required>
                </div>

                <div>
                    <label for="penulis">Penulis</label>
                    <input id="penulis" minlength="3" maxlength="100"
                        placeholder="Nama penulis" required>
                </div>

                <div>
                    <label for="tahun">Tahun Terbit</label>
                    <input id="tahun" type="number" min="1900" max="2026"
                        placeholder="Contoh: 2025" required>
                </div>
            </div>

            <button class="btn-primary" id="tombolSimpan" type="submit">
                Tambah Buku
            </button>
            <button class="btn-secondary" id="tombolBatal"
                type="button" hidden>Batal</button>
        </form>
    </section>

    <section class="book-section">
        <h3>Daftar Buku</h3>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Kategori</th>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Tahun</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="daftarBuku">
                    <tr><td colspan="5">Memuat data...</td></tr>
                </tbody>
            </table>
        </div>
    </section>
</div>

@vite('resources/js/buku.js')
@endsection
