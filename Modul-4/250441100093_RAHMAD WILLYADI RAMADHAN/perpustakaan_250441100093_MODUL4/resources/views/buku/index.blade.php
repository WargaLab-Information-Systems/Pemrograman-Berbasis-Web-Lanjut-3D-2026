<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Buku</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; background-color: #f8f9fa; color: #333; }
        .container { max-width: 900px; margin: auto; background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        h2, h3 { margin-top: 0; color: #2c3e50; }
        .alert { padding: 12px 15px; border-radius: 5px; margin-bottom: 20px; display: none; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .form-group { margin-bottom: 12px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        input[type="text"], input[type="number"], select { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        .btn { padding: 8px 14px; border: none; border-radius: 4px; cursor: pointer; color: #fff; font-weight: bold; }
        .btn-primary { background-color: #007bff; }
        .btn-warning { background-color: #ffc107; color: #212529; }
        .btn-danger { background-color: #dc3545; }
        .btn-secondary { background-color: #6c757d; display: none; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #dee2e6; }
        th, td { padding: 10px; text-align: left; }
        th { background-color: #343a40; color: #fff; }
    </style>
</head>
<body>

<div class="container">
    <h2>Daftar Buku & Manajemen Buku</h2>

    <div id="alertSuccess" class="alert alert-success"></div>
    <div id="alertError" class="alert alert-error"></div>

    <h3 id="formTitle">Tambah Buku Baru</h3>
    <form id="bukuForm">
        <input type="hidden" id="buku_id">

        <div class="form-group">
            <label for="kategori_id">Kategori Buku:</label>
            <select id="kategori_id" required>
                <option value="">-- Memuat Kategori... --</option>
            </select>
        </div>

        <div class="form-group">
            <label for="judul">Judul Buku:</label>
            <input type="text" id="judul" placeholder="Masukkan judul buku" required>
        </div>

        <div class="form-group">
            <label for="penulis">Penulis:</label>
            <input type="text" id="penulis" placeholder="Masukkan nama penulis" required>
        </div>

        <div class="form-group">
            <label for="tahun_terbit">Tahun Terbit:</label>
            <input type="number" id="tahun_terbit" placeholder="Contoh: 2024" required>
        </div>

        <button type="submit" id="btnSubmit" class="btn btn-primary">Simpan Buku</button>
        <button type="button" id="btnCancel" class="btn btn-secondary" onclick="resetForm()">Batal Ubah</button>
    </form>

    <hr style="margin: 25px 0;">

    <h3>Tabel Data Buku</h3>
    <table>
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
        <tbody id="tabelBuku">
            <tr>
                <td colspan="6" style="text-align:center;">Memuat data...</td>
            </tr>
        </tbody>
    </table>
</div>

<script>
    const API_URL = '/api/buku';
    const API_KATEGORI = '/api/kategori';

    function tampilkanPesan(jenis, pesanHtml) {
        const boxSuccess = document.getElementById('alertSuccess');
        const boxError = document.getElementById('alertError');
        boxSuccess.style.display = 'none';
        boxError.style.display = 'none';

        if (jenis === 'success') {
            boxSuccess.innerHTML = pesanHtml;
            boxSuccess.style.display = 'block';
        } else {
            boxError.innerHTML = pesanHtml;
            boxError.style.display = 'block';
        }
    }

    async function loadKategori() {
        const dropdown = document.getElementById('kategori_id');
        try {
            const response = await fetch(API_KATEGORI, { method: 'GET', headers: { 'Accept': 'application/json' } });
            const result = await response.json();
            if (!response.ok) throw new Error('Gagal memuat kategori');

            dropdown.innerHTML = '<option value="">-- Pilih Kategori --</option>';
            result.data.forEach(item => {
                const opt = document.createElement('option');
                opt.value = item.id;
                opt.innerText = item.nama_kategori || item.nama || item.kategori || 'Kategori ' + item.id; 
                dropdown.appendChild(opt);
            });
        } catch (error) {
            dropdown.innerHTML = '<option value="">-- Gagal mengambil data database --</option>';
        }
    }

    async function loadBuku() {
        const tbody = document.getElementById('tabelBuku');
        try {
            const response = await fetch(API_URL, { method: 'GET', headers: { 'Accept': 'application/json' } });
            const result = await response.json();

            if (!response.ok) throw new Error(result.message || 'Gagal mengambil data dari server.');
            tbody.innerHTML = '';

            if (result.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;">Belum ada data buku.</td></tr>';
                return;
            }

            result.data.forEach((item, index) => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${item.kategori}</td>
                    <td>${item.judul_buku}</td>
                    <td>${item.nama_penulis}</td>
                    <td>${item.tahun}</td>
                    <td>
                        <button class="btn btn-warning" onclick='pilihBuku(${JSON.stringify(item)})'>Ubah</button>
                        <button class="btn btn-danger" onclick="hapusBuku(${item.id}, '${item.judul_buku.replace(/'/g, "\\'")}')">Hapus</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        } catch (error) {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align:center; color:red;">Gagal memuat data.</td></tr>';
            tampilkanPesan('error', '<strong>Kesalahan Pengambilan Data:</strong> ' + error.message);
        }
    }

    document.getElementById('bukuForm').addEventListener('submit', async function(e) {
        e.preventDefault(); 
        const id = document.getElementById('buku_id').value;
        const payload = {
            kategori_id: document.getElementById('kategori_id').value,
            judul: document.getElementById('judul').value,
            penulis: document.getElementById('penulis').value,
            tahun_terbit: document.getElementById('tahun_terbit').value
        };
        const method = id ? 'PUT' : 'POST';
        const url = id ? `${API_URL}/${id}` : API_URL;

        try {
            const response = await fetch(url, {
                method: method,
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(payload)
            });
            const result = await response.json();
            if (!response.ok) {
                if (result.errors) {
                    const daftarError = Object.values(result.errors).flat().join('<br>- ');
                    throw new Error('<br>- ' + daftarError);
                }
                throw new Error(result.message || 'Terjadi kesalahan saat menyimpan data.');
            }
            tampilkanPesan('success', result.message);
            resetForm();
            await loadBuku();
        } catch (error) {
            tampilkanPesan('error', '<strong>Permintaan Gagal:</strong> ' + error.message);
        }
    });

    function pilihBuku(buku) {
        document.getElementById('formTitle').innerText = 'Ubah Data Buku (ID: ' + buku.id + ')';
        document.getElementById('buku_id').value = buku.id;
        document.getElementById('kategori_id').value = buku.kategori;
        document.getElementById('judul').value = buku.judul_buku;
        document.getElementById('penulis').value = buku.nama_penulis;
        document.getElementById('tahun_terbit').value = buku.tahun;
        document.getElementById('btnSubmit').innerText = 'Perbarui Buku';
        document.getElementById('btnCancel').style.display = 'inline-block';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function resetForm() {
        document.getElementById('bukuForm').reset();
        document.getElementById('buku_id').value = '';
        document.getElementById('formTitle').innerText = 'Tambah Buku Baru';
        document.getElementById('btnSubmit').innerText = 'Simpan Buku';
        document.getElementById('btnCancel').style.display = 'none';
    }

    async function hapusBuku(id, judul) {
        const yakin = confirm(`Apakah Anda yakin ingin menghapus buku "${judul}"?`);
        if (!yakin) return;
        try {
            const response = await fetch(`${API_URL}/${id}`, { method: 'DELETE', headers: { 'Accept': 'application/json' } });
            const result = await response.json();
            if (!response.ok) throw new Error(result.message || 'Gagal menghapus buku.');
            tampilkanPesan('success', result.message);
            await loadBuku();
        } catch (error) {
            tampilkanPesan('error', '<strong>Gagal Menghapus:</strong> ' + error.message);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        loadKategori();
        loadBuku();
    });
</script>

</body>
</html>