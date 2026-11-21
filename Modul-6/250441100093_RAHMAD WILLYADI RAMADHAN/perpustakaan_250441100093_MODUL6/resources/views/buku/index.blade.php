@php
$statusPinjam = [];
if(auth()->check() && auth()->user()->hasRole('siswa')) {
$statusPinjam = \App\Models\Peminjaman::where('anggota_id', auth()->id())
->whereIn('status', ['Menunggu Persetujuan', 'Disetujui'])
->pluck('status', 'buku_id')
->toArray();
}
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Buku</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            background-color: #f8f9fa;
            color: #333;
        }

        .navbar {
            max-width: 900px;
            margin: 0 auto 20px auto;
            background: #fff;
            padding: 15px 25px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }

        .badge {
            background: #e9ecef;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .container {
            max-width: 900px;
            margin: auto;
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        h2,
        h3 {
            margin-top: 0;
            color: #2c3e50;
        }

        .alert {
            padding: 12px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            display: none;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .form-group {
            margin-bottom: 12px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .btn {
            padding: 8px 14px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: #fff;
            font-weight: bold;
            display: inline-block;
            text-decoration: none;
        }

        .btn-primary {
            background-color: #007bff;
        }

        .btn-success {
            background-color: #28a745;
        }

        .btn-warning {
            background-color: #ffc107;
            color: #212529;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn-secondary {
            background-color: #6c757d;
            display: none;
        }

        .status-label {
            padding: 6px 12px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 12px;
            display: inline-block;
        }

        .status-menunggu {
            background-color: #ffc107;
            color: #000;
        }

        .status-disetujui {
            background-color: #17a2b8;
            color: #fff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #dee2e6;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #343a40;
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="navbar">
        <a href="@if(auth()->user()->hasRole('admin')) {{ url('/admin/dashboard') }} @elseif(auth()->user()->hasRole('petugas')) {{ url('/petugas/dashboard') }} @else {{ url('/dashboard') }} @endif">&larr; Kembali ke Dashboard</a>
        <div>
            <span style="margin-right: 10px;">{{ Auth::user()->name }}</span>
            <span class="badge">{{ Auth::user()->getRoleNames()->first() }}</span>
        </div>
    </div>
    <div class="container">
        <h2>Daftar Buku & Manajemen Buku</h2>

        @if(session('status'))
        <div class="alert alert-success" style="display:block;">{{ session('status') }}</div>
        @endif

        <div id="alertSuccess" class="alert alert-success"></div>
        <div id="alertError" class="alert alert-error"></div>

        @can('kelola-buku')
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
        @endcan

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

    <meta id="data-status" data-pinjam="{{ json_encode($statusPinjam ?? []) }}">
    <script>
        const API_URL = '/api/buku';
        const API_KATEGORI = '/api/kategori';

        // SOAL 3: Mengganti hasAnyRole menjadi can pada JavaScript
        const canManage = "{{ auth()->user()->can('kelola-buku') ? 'true' : 'false' }}" === "true";

        const isSiswa = "{{ auth()->user()->hasRole('siswa') ? 'true' : 'false' }}" === "true";
        const csrfToken = "{{ csrf_token() }}";

        const metaData = document.getElementById('data-status');
        const statusPinjamUser = JSON.parse(metaData.getAttribute('data-pinjam'));

        function tampilkanPesan(jenis, pesan) {
            const boxSuccess = document.getElementById('alertSuccess');
            const boxError = document.getElementById('alertError');
            boxSuccess.style.display = 'none';
            boxError.style.display = 'none';

            if (jenis === 'success') {
                boxSuccess.innerHTML = pesan;
                boxSuccess.style.display = 'block';
            } else {
                boxError.innerHTML = pesan;
                boxError.style.display = 'block';
            }
        }

        async function loadKategori() {
            const dropdown = document.getElementById('kategori_id');
            if (!dropdown) return;
            try {
                const response = await fetch(API_KATEGORI, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                const result = await response.json();
                dropdown.innerHTML = '<option value="">-- Pilih Kategori --</option>';
                result.data.forEach(item => {
                    const opt = document.createElement('option');
                    opt.value = item.id;
                    opt.innerText = item.kategori || item.nama_kategori || item.nama || 'Kategori ' + item.id;
                    dropdown.appendChild(opt);
                });
            } catch (error) {
                dropdown.innerHTML = '<option value="">-- Gagal mengambil data --</option>';
            }
        }

        async function loadBuku() {
            const tbody = document.getElementById('tabelBuku');
            try {
                const response = await fetch(API_URL, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                const result = await response.json();
                tbody.innerHTML = '';

                if (!result.data || result.data.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="6" style="text-align:center;">Belum ada data buku.</td></tr>`;
                    return;
                }

                result.data.forEach((item, index) => {
                    const tr = document.createElement('tr');
                    let tombolAksi = '';

                    if (canManage) {
                        tombolAksi = `
                            <button class="btn btn-warning" onclick='pilihBuku(${JSON.stringify(item).replace(/'/g, "&#39;")})'>Ubah</button>
                            <button class="btn btn-danger" onclick="hapusBuku(${item.id}, '${item.judul_buku.replace(/'/g, "\\'")}')">Hapus</button>
                        `;
                    }

                    // nomor 2c
                    // if (canManage) {
                    //     tombolAksi = `
                    //         <button class="btn btn-warning" onclick='pilihBuku(${JSON.stringify(item).replace(/'/g, "&#39;")})'>Ubah</button>
                    //         <form action="/buku/${item.id}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus buku ini?');">
                    //             <input type="hidden" name="_token" value="${csrfToken}">
                    //             <input type="hidden" name="_method" value="DELETE">
                    //             <button type="submit" class="btn btn-danger">Hapus</button>
                    //         </form>
                    //     `;
                    // }

                    if (isSiswa) {
                        const statusBukuIni = statusPinjamUser[item.id];

                        if (statusBukuIni === 'Menunggu Persetujuan') {
                            tombolAksi = `<span class="status-label status-menunggu">Menunggu Persetujuan</span>`;
                        } else if (statusBukuIni === 'Disetujui') {
                            tombolAksi = `<span class="status-label status-disetujui">Sedang Dipinjam</span>`;
                        } else {
                            tombolAksi = `
                                <form action="/pinjam/${item.id}" method="POST" style="display:inline;">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                    <button type="submit" class="btn btn-success">Pinjam Buku</button>
                                </form>
                            `;
                        }
                    }

                    tr.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${item.kategori}</td>
                        <td>${item.judul_buku.replace(/</g, "&lt;").replace(/>/g, "&gt;")}</td>
                        <td>${item.nama_penulis}</td>
                        <td>${item.tahun}</td>
                        <td>${tombolAksi}</td>
                    `;
                    tbody.appendChild(tr);
                });
            } catch (error) {
                tbody.innerHTML = `<tr><td colspan="6" style="text-align:center; color:red;">Gagal memuat data.</td></tr>`;
            }
        }

        const formBuku = document.getElementById('bukuForm');
        if (formBuku) {
            formBuku.addEventListener('submit', async function(e) {
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
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify(payload)
                    });
                    const result = await response.json();
                    if (!response.ok) throw new Error(result.message);
                    tampilkanPesan('success', result.message);
                    resetForm();
                    await loadBuku();
                } catch (error) {
                    tampilkanPesan('error', 'Gagal: ' + error.message);
                }
            });
        }

        function pilihBuku(buku) {
            document.getElementById('formTitle').innerText = 'Ubah Data Buku';
            document.getElementById('buku_id').value = buku.id;
            document.getElementById('kategori_id').value = buku.kategori_id || '';
            document.getElementById('judul').value = buku.judul_buku;
            document.getElementById('penulis').value = buku.nama_penulis;
            document.getElementById('tahun_terbit').value = buku.tahun;
            document.getElementById('btnSubmit').innerText = 'Perbarui Buku';
            document.getElementById('btnCancel').style.display = 'inline-block';
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function resetForm() {
            const form = document.getElementById('bukuForm');
            if (form) form.reset();
            document.getElementById('buku_id').value = '';
            document.getElementById('formTitle').innerText = 'Tambah Buku Baru';
            document.getElementById('btnSubmit').innerText = 'Simpan Buku';
            document.getElementById('btnCancel').style.display = 'none';
        }

        async function hapusBuku(id) {
            if (!confirm('Yakin ingin menghapus buku ini?')) return;

            try {
                const response = await fetch(`/buku/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });
                const result = await response.json();

                if (!response.ok) throw new Error(result.message);

                tampilkanPesan('success', result.message);
                await loadBuku(); // Refresh tabel otomatis
            } catch (error) {
                tampilkanPesan('error', 'Gagal: ' + error.message);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadKategori();
            loadBuku();
        });
    </script>
</body>

</html>