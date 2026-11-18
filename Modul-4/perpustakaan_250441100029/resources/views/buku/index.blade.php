@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')
<div class="container py-4">
    <h2 class="judul-halaman mb-4">Kelola Data Buku (Modul 4)</h2>

    <!-- Wadah Pesan Status / Error Validasi -->
    <div id="alert-container"></div>

    <!-- 1. Formulir Tambah / Ubah Buku -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white" id="form-title">Tambah Buku Baru</div>
        <div class="card-body">
            <form id="form-buku">
                <!-- ID Tersembunyi (Digunakan saat mode edit/UPDATE) -->
                <input type="hidden" id="buku-id" value="">

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="kategori_id" class="form-label">ID Kategori</label>
                        <input type="number" class="form-control" id="kategori_id" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="judul" class="form-label">Judul Buku</label>
                        <input type="text" class="form-control" id="judul" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="penulis" class="form-label">Penulis</label>
                        <input type="text" class="form-control" id="penulis" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
                        <input type="number" class="form-control" id="tahun_terbit" placeholder="Contoh: 2024" required>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-success" id="btn-simpan">Simpan Data</button>
                <button type="button" class="btn btn-secondary d-none" id="btn-batal" onclick="resetForm()">Batal</button>
            </form>
        </div>
    </div>

    <!-- 2. Tabel Daftar Buku -->
    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Kategori</th>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Tahun Terbit</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="buku-table-body">
                    <tr>
                        <td colspan="6" class="text-center">Memuat data...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        loadBuku();

        // Event listener saat form disubmit (Nomor 2 & 3)
        document.getElementById('form-buku').addEventListener('submit', function(e) {
            e.preventDefault();
            saveBuku();
        });
    });

    // 1. GET /api/buku - Memuat data menggunakan Fetch API & async/await (Nomor 1 & 5)
    async function loadBuku() {
        const tbody = document.getElementById('buku-table-body');
        
        try {
            const response = await fetch('/api/buku', {
                headers: { 'Accept': 'application/json' }
            });

            if (!response.ok) {
                throw new Error(`Gagal mengambil data. Status: ${response.status}`);
            }

            const result = await response.json();
            const dataBuku = result.data; // Mengambil data hasil pemetaan BukuResource

            if (dataBuku.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" class="text-center">Belum ada data buku.</td></tr>`;
                return;
            }

            let html = '';
            dataBuku.forEach((buku, index) => {
                const bukuJson = JSON.stringify(buku).replace(/'/g, "&apos;");

                html += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${buku.kategori}</td>
                        <td>${buku.judul}</td>
                        <td>${buku.penulis}</td>
                        <td>${buku.tahun_terbit}</td>
                        <td class="text-center">
                            <button class="btn btn-warning btn-sm me-1" onclick='editBuku(${bukuJson})'>Ubah</button>
                            <button class="btn btn-danger btn-sm" onclick="deleteBuku(${buku.id})">Hapus</button>
                        </td>
                    </tr>
                `;
            });

            tbody.innerHTML = html;

        } catch (error) {
            showAlert(error.message, 'danger');
            tbody.innerHTML = `<tr><td colspan="6" class="text-center text-danger">Gagal memuat data.</td></tr>`;
        }
    }

    // 2 & 3. POST /api/buku (Simpan Baru) & PUT /api/buku/{id} (Update Data) (Nomor 2, 3 & 5)
    async function saveBuku() {
        const id = document.getElementById('buku-id').value;
        const payload = {
            kategori_id: document.getElementById('kategori_id').value,
            judul: document.getElementById('judul').value,
            penulis: document.getElementById('penulis').value,
            tahun_terbit: document.getElementById('tahun_terbit').value,
        };

        const isEdit = id !== '' && id !== null && id !== undefined;
        const url = isEdit ? `/api/buku/${id}` : '/api/buku';
        const method = isEdit ? 'PUT' : 'POST';

        try {
            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            const result = await response.json();

            // Penanganan Kesalahan Validasi (422) atau Server Error (Nomor 5)
            if (!response.ok) {
                if (response.status === 422) {
                    let errorMessages = Object.values(result.errors).flat().join('<br>');
                    throw new Error(errorMessages);
                }
                throw new Error(result.message || 'Terjadi kesalahan pada server.');
            }

            // Berhasil
            showAlert(isEdit ? 'Buku berhasil diperbarui!' : 'Buku berhasil ditambahkan!', 'success');
            resetForm();
            loadBuku(); // Perbarui tabel tanpa reload halaman

        } catch (error) {
            showAlert(error.message, 'danger');
        }
    }

    // Isikan data ke Form saat tombol Ubah diklik (Nomor 3)
    function editBuku(buku) {
        document.getElementById('buku-id').value = buku.id;
        document.getElementById('kategori_id').value = buku.kategori_id;
        document.getElementById('judul').value = buku.judul;
        document.getElementById('penulis').value = buku.penulis;
        document.getElementById('tahun_terbit').value = buku.tahun_terbit;

        document.getElementById('form-title').innerText = 'Ubah Data Buku';
        document.getElementById('btn-simpan').innerText = 'Perbarui Data';
        document.getElementById('btn-batal').classList.remove('d-none');
    }

    // 4. DELETE /api/buku/{id} - Menghapus data dengan konfirmasi (Nomor 4 & 5)
    async function deleteBuku(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus buku ini?')) {
            return;
        }

        try {
            const response = await fetch(`/api/buku/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message || 'Gagal menghapus data.');
            }

            showAlert('Buku berhasil dihapus!', 'success');
            loadBuku(); // Perbarui tabel

        } catch (error) {
            showAlert(error.message, 'danger');
        }
    }

    // Reset Form ke keadaan semula
    function resetForm() {
        document.getElementById('buku-id').value = '';
        document.getElementById('form-buku').reset();
        document.getElementById('form-title').innerText = 'Tambah Buku Baru';
        document.getElementById('btn-simpan').innerText = 'Simpan Data';
        document.getElementById('btn-batal').classList.add('d-none');
    }

    // Helper untuk menampilkan alert Bootstrap
    function showAlert(message, type) {
        const container = document.getElementById('alert-container');
        container.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
    }
</script>
@endsection