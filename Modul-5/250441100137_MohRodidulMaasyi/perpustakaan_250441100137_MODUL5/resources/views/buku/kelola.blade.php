@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/buku.css') }}">

<div class="buku-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 700; color: #342a26; margin: 0;">Pengelolaan Data Buku</h2>
            <p style="color: #666; font-size: 0.875rem; margin: 4px 0 0 0;">Tambah, perbarui, dan hapus data buku perpustakaan.</p>
        </div>
        <a href="{{ route('buku.index') }}" class="btn-primary-custom" style="text-decoration: none;">
            &larr; Lihat Tampilan Katalog
        </a>
    </div>

    <!-- Form Tambah / Ubah Buku -->
    <div class="form-card">
        <h3 id="form-title">Tambah Buku Baru</h3>
        <form id="form-buku">
            <input type="hidden" id="buku_id">
            
            <div class="form-grid">
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
                    <input type="text" name="judul" id="judul" placeholder="Masukkan judul buku" required>
                </div>

                <div class="form-group">
                    <label for="penulis">Penulis:</label>
                    <input type="text" name="penulis" id="penulis" placeholder="Nama penulis" required>
                </div>

                <div class="form-group">
                    <label for="tahun_terbit">Tahun Terbit:</label>
                    <input type="number" name="tahun_terbit" id="tahun_terbit" min="1900" max="2030" placeholder="2024" required>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" id="btn-submit">Simpan Buku</button>
                <button type="button" id="btn-cancel" onclick="resetForm()">Batal</button>
            </div>
        </form>
    </div>

    <!-- Tabel Daftar Buku Sesuai Gambar -->
    <div class="table-wrapper">
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
                @forelse($buku as $index => $item)
                    <tr>
                        <td>{{ $item->kategori->nama ?? '-' }}</td>
                        <td>{{ $item->judul }}</td>
                        <td>{{ $item->penulis }}</td>
                        <td>{{ $item->tahun_terbit }}</td>
                        <td>
                            <button type="button" class="btn-ubah" onclick="siapEdit({{ $index }})">Ubah</button>
                            <button type="button" class="btn-hapus" onclick="hapusBuku({{ $item->id }})">Hapus</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="baris-info">Belum ada data buku.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    window.canManageBooks = true;
</script>
<script src="{{ asset('js/buku.js') }}"></script>
@endsection
