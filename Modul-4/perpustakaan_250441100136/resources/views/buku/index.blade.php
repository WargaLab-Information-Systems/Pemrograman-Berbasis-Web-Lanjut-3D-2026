@extends('layouts.main')

@section('title', 'Daftar Koleksi Buku')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md-8">
        <h2 class="fw-bold mb-1 text-primary">
            <i class="bi bi-collection-fill me-2"></i>Daftar Koleksi Buku
        </h2>
        <p class="text-muted mb-0">Kelola dan lihat koleksi buku secara langsung via RESTful API</p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <button type="button" class="btn btn-outline-primary btn-sm rounded-pill shadow-sm" onclick="fetchBuku()">
            <i class="bi bi-arrow-clockwise me-1"></i> Muat Ulang Data
        </button>
    </div>
</div>

<!-- Container Notifikasi Error & Berhasil -->
<div id="alertContainer"></div>

<!-- Formulir Tambah & Ubah Buku -->
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-header bg-primary text-white py-3 rounded-top-3 d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0 fw-semibold" id="formHeader">
            <i class="bi bi-plus-circle me-2"></i>Tambah Buku Baru
        </h5>
        <span class="badge bg-light text-primary" id="formBadge">Mode Tambah</span>
    </div>
    <div class="card-body p-4">
        <form id="formBuku" novalidate>
            <input type="hidden" id="bukuId" value="">
            
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="kategori" class="form-label fw-semibold">
                        <i class="bi bi-tag me-1 text-secondary"></i>Kategori (1 / 2)
                    </label>
                    <input type="text" class="form-control" id="kategori" placeholder="Ketik 1 atau 2">
                    <div class="form-text">(1 = Pemrograman, 2 = Basis Data)</div>
                </div>
                <div class="col-md-3">
                    <label for="judul" class="form-label fw-semibold">
                        <i class="bi bi-book me-1 text-secondary"></i>Judul Buku
                    </label>
                    <input type="text" class="form-control" id="judul" placeholder="Masukkan judul buku">
                </div>
                <div class="col-md-3">
                    <label for="penulis" class="form-label fw-semibold">
                        <i class="bi bi-person me-1 text-secondary"></i>Penulis
                    </label>
                    <input type="text" class="form-control" id="penulis" placeholder="Nama penulis">
                </div>
                <div class="col-md-3">
                    <label for="tahun_terbit" class="form-label fw-semibold">
                        <i class="bi bi-calendar3 me-1 text-secondary"></i>Tahun Terbit
                    </label>
                    <input type="text" class="form-control" id="tahun_terbit" placeholder="Contoh: 2024">
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" id="btnSubmit" class="btn btn-success px-4 rounded-pill shadow-sm">
                    <i class="bi bi-check-circle me-1"></i> Simpan Buku
                </button>
                <button type="button" id="btnCancel" class="btn btn-secondary px-3 rounded-pill d-none" onclick="resetForm()">
                    <i class="bi bi-x-circle me-1"></i> Batal Edit
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Data Buku -->
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-semibold text-secondary">
            <i class="bi bi-table me-2"></i>Koleksi Terdaftar
        </h5>
        <span class="badge bg-secondary rounded-pill" id="totalBukuBadge">Memuat...</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-secondary text-uppercase fs-7">
                    <tr>
                        <th class="ps-4" style="width: 20%;">Kategori</th>
                        <th style="width: 30%;">Judul</th>
                        <th style="width: 25%;">Penulis</th>
                        <th class="text-center" style="width: 10%;">Tahun</th>
                        <th class="text-center pe-4" style="width: 15%;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tabelBukuBody">
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                            Sedang memuat data dari API...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    const API_URL = '/api/buku';
    let dataBukuCache = [];

    document.addEventListener('DOMContentLoaded', () => {
        fetchBuku();
        document.getElementById('formBuku').addEventListener('submit', handleFormSubmit);
    });

    // Helper Penanganan Kesalahan & Alert
    function showAlert(message, type = 'danger') {
        const alertContainer = document.getElementById('alertContainer');
        const icon = type === 'success' ? 'check-circle-fill' : (type === 'warning' ? 'exclamation-circle-fill' : 'exclamation-triangle-fill');
        alertContainer.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show d-flex align-items-center gap-2 shadow-sm rounded-3" role="alert">
                <i class="bi bi-${icon} fs-5"></i>
                <div class="flex-grow-1">${message}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
    }

    // Ekstraksi pesan kesalahan yang informatif dan ramah pengguna
    async function extractErrorMessage(response, defaultMsg = 'Terjadi kesalahan pada sistem.') {
        try {
            const data = await response.json();
            if (response.status === 422 && data.errors) {
                return Object.values(data.errors).flat().join('<br>');
            }
            return data.message || `Kesalahan (${response.status}): ${defaultMsg}`;
        } catch (_) {
            return `Kesalahan HTTP ${response.status}: ${response.statusText || defaultMsg}`;
        }
    }

    // 1. GET /api/buku menggunakan Fetch & async/await
    async function fetchBuku() {
        const tbody = document.getElementById('tabelBukuBody');
        const totalBadge = document.getElementById('totalBukuBadge');
        
        try {
            const response = await fetch(API_URL, {
                headers: { 'Accept': 'application/json' }
            });

            if (!response.ok) {
                const errorMsg = await extractErrorMessage(response, 'Gagal mengambil data buku.');
                throw new Error(errorMsg);
            }

            const json = await response.json();
            dataBukuCache = json.data;

            tbody.innerHTML = '';
            totalBadge.innerText = `${dataBukuCache ? dataBukuCache.length : 0} Buku`;

            if (!dataBukuCache || dataBukuCache.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                            Belum ada data koleksi buku. Silakan tambahkan buku melalui form di atas.
                        </td>
                    </tr>
                `;
                return;
            }

            dataBukuCache.forEach(buku => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="ps-4">
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2 fw-medium">
                            <i class="bi bi-bookmark-fill me-1"></i>${buku.kategori ?? '-'}
                        </span>
                    </td>
                    <td>
                        <strong class="text-dark">${buku.judulll ?? '-'}</strong>
                    </td>
                    <td class="text-secondary">${buku.penulis ?? '-'}</td>
                    <td class="text-center"><span class="badge bg-light text-dark border">${buku.tahun_terbit ?? '-'}</span></td>
                    <td class="text-center pe-4">
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-warning" onclick="editBuku(${buku.id})" title="Ubah Data">
                                <i class="bi bi-pencil-square"></i> Ubah
                            </button>
                            <button class="btn btn-outline-danger" onclick="deleteBuku(${buku.id})" title="Hapus Data">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </div>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        } catch (error) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center py-4 text-danger">
                        <i class="bi bi-exclamation-triangle me-1"></i> Gagal memuat data buku. Silakan segarkan halaman.
                    </td>
                </tr>
            `;
            totalBadge.innerText = 'Gagal';
            showAlert(error.message, 'danger');
        }
    }

    // 2 & 3. POST & PUT /api/buku
    async function handleFormSubmit(event) {
        event.preventDefault();
        
        const submitBtn = document.getElementById('btnSubmit');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-1" role="status"></span> Menyimpan...`;

        const id = document.getElementById('bukuId').value;
        const payload = {
            kategori: document.getElementById('kategori').value,
            judul: document.getElementById('judul').value,
            penulis: document.getElementById('penulis').value,
            tahun_terbit: document.getElementById('tahun_terbit').value
        };

        const isEdit = id !== '';
        const url = isEdit ? `${API_URL}/${id}` : API_URL;
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

            if (!response.ok) {
                const errorMsg = await extractErrorMessage(response, isEdit ? 'Gagal memperbarui data buku.' : 'Gagal menambahkan buku baru.');
                throw new Error(errorMsg);
            }

            showAlert(`Buku berhasil ${isEdit ? 'diperbarui' : 'ditambahkan'}!`, 'success');
            resetForm();
            fetchBuku();
        } catch (error) {
            showAlert(error.message, 'danger');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    }

    // 3. Menampilkan data buku terpilih di formulir
    function editBuku(id) {
        const buku = dataBukuCache.find(item => item.id === id);
        if (!buku) return;

        document.getElementById('bukuId').value = buku.id;
        document.getElementById('kategori').value = buku.kategori_id ?? buku.kategori;
        document.getElementById('judul').value = buku.judul;
        document.getElementById('penulis').value = buku.penulis;
        document.getElementById('tahun_terbit').value = buku.tahun_terbit;

        document.getElementById('formHeader').innerHTML = '<i class="bi bi-pencil-square me-2"></i>Ubah Data Buku';
        document.getElementById('formBadge').innerText = 'Mode Ubah';
        document.getElementById('formBadge').className = 'badge bg-warning text-dark';
        
        document.getElementById('btnSubmit').innerHTML = '<i class="bi bi-check-circle me-1"></i> Perbarui Buku';
        document.getElementById('btnSubmit').className = 'btn btn-warning px-4 rounded-pill shadow-sm';
        document.getElementById('btnCancel').classList.remove('d-none');

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function resetForm() {
        document.getElementById('bukuId').value = '';
        document.getElementById('formBuku').reset();
        document.getElementById('formHeader').innerHTML = '<i class="bi bi-plus-circle me-2"></i>Tambah Buku Baru';
        document.getElementById('formBadge').innerText = 'Mode Tambah';
        document.getElementById('formBadge').className = 'badge bg-light text-primary';

        document.getElementById('btnSubmit').innerHTML = '<i class="bi bi-check-circle me-1"></i> Simpan Buku';
        document.getElementById('btnSubmit').className = 'btn btn-success px-4 rounded-pill shadow-sm';
        document.getElementById('btnCancel').classList.add('d-none');
    }

    // 4. Konfirmasi & Hapus Data DELETE /api/buku/{id}
    async function deleteBuku(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus buku ini?')) {
            return;
        }

        try {
            const response = await fetch(`${API_URL}/${id}`, {
                method: 'DELETE',
                headers: { 'Accept': 'application/json' }
            });

            if (!response.ok) {
                const errorMsg = await extractErrorMessage(response, 'Gagal menghapus buku dari server.');
                throw new Error(errorMsg);
            }

            showAlert('Buku berhasil dihapus dari basis data!', 'warning');
            fetchBuku();
        } catch (error) {
            showAlert(error.message, 'danger');
        }
    }
</script>
@endsection
