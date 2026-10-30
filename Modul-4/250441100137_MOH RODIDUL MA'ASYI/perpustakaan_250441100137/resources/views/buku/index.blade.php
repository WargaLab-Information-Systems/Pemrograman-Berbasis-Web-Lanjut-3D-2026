@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/buku.css') }}">

    <!-- Form Tambah / Ubah Buku -->
    <div class="form-card">
        <h3 id="form-title">Tambah Buku Baru</h3>
        <form id="form-buku">
            <input type="hidden" id="buku_id"> <!-- Penanda ID untuk mode edit -->
            
            <div class="form-group">
                <label for="kategori_id">Kategori:</label>
                <select name="kategori_id" id="kategori_id" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="judul">Judul Buku:</label>
                <input type="text" name="judul" id="judul" required>
            </div>
            <div class="form-group">
                <label for="penulis">Penulis:</label>
                <input type="text" name="penulis" id="penulis" required>
            </div>
            <div class="form-group">
                <label for="tahun_terbit">Tahun Terbit (2000-2025):</label>
                <input type="number" name="tahun_terbit" id="tahun_terbit" min="2000" max="2025" required>
            </div>
            
            <button type="submit" id="btn-submit">Simpan Buku</button>
            <button type="button" id="btn-cancel" onclick="resetForm()">Batal</button>
        </form>
    </div>
    
    <!-- Tabel Daftar Buku -->
    <table>
        <thead>
            <tr>
                <th>Kategori</th>
                <th>Judul</th>
                <th>Penulis</th>
                <th>Tahun Terbit</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="buku-table-body">
            <tr>
                <td colspan="5" class="baris-info">Sedang memuat data...</td>
            </tr>
        </tbody>
    </table>

    <script src="{{ asset('js/buku.js') }}"></script>
@endsection