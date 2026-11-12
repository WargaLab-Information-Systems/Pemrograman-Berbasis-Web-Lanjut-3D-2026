@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')

    <h2>Daftar Buku</h2>
    <h3 id="judul-form">Tambah Buku Baru</h3>

    <form id="form-tambah-buku">
        <div>
            <label for="judul">Judul Buku</label>
            <input type="text" id="judul" name="judul" required>
        </div>

        <div>
            <label for="penulis">Penulis</label>
            <input type="text" id="penulis" name="penulis" required>
        </div>

        <div>
            <label for="tahun_terbit">Tahun Terbit</label>
            <input type="number" id="tahun_terbit" name="tahun_terbit" required>
        </div>

        <div>
            <label for="kategori_id">ID Kategori</label>
            <input type="number" id="kategori_id" name="kategori_id" required>
        </div>

        <button type="submit" id="tombol-simpan">Tambah Buku</button>
        <button type="button" id="tombol-batal" hidden>Batal Ubah</button>
    </form>

    <p id="pesan-tambah-buku"></p>

    <p id="status-buku">sedang memuat data buku...</p>

    <table border="1" cellpadding="10" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Kategori</th>
                <th>Judul Buku</th>
                <th>Penulis</th>
                <th>Tahun terbit</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody id="daftar-buku">

        </tbody>
    </table>

@vite('resources/js/buku.js')
@endsection