@extends('layouts.main')

@section('title', 'Daftar Buku')

@section('content')
    <style>
        .form-container {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 25px;
            border: 1px solid #e0e0e0;
        }
        .form-row {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }
        .form-control {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            flex: 1;
            min-width: 150px;
        }
        .btn {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            color: white;
        }
        .btn-primary { background: #2f7a4c; }
        .btn-secondary { background: #6c757d; }
        .btn-warning { background: #f0ad4e; color: white; padding: 5px 10px; }
        .btn-danger { background: #d9534f; color: white; padding: 5px 10px; }
        
        .styled-table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        .styled-table thead tr {
            background-color: #2f7a4c;
            color: #ffffff;
            text-align: left;
        }
        .styled-table th, .styled-table td {
            padding: 12px 15px;
        }
        .styled-table tbody tr {
            border-bottom: 1px solid #dddddd;
        }
        .styled-table tbody tr:nth-of-type(even) {
            background-color: #f3f3f3;
        }
        .styled-table tbody tr:last-of-type {
            border-bottom: 2px solid #2f7a4c;
        }
    </style>

    <h2 class="page-title" style="color: #2f7a4c; font-weight: bold; margin-bottom: 20px;">Daftar Buku</h2>

    <div class="form-container">
        <h3 style="margin-top: 0; color: #333;">Borang Buku</h3>
        <div id="pesan-error" style="color: red; margin-bottom: 10px; white-space: pre-line;"></div>
        
        <form id="form-buku">
            <input type="hidden" id="buku_id"> 
            
            <div class="form-row">
                <input list="daftar-kategori" class="form-control" id="kategori_id" placeholder="ID Kategori (Pilih 1-5)" required>
                <datalist id="daftar-kategori">
                    <option value="1" label="Fiksi">
                    <option value="2" label="Pelajaran">
                    <option value="3" label="Sejarah">
                    <option value="4" label="Teknologi">
                    <option value="5" label="Sastra">
                </datalist>

                <input type="text" class="form-control" id="judul" placeholder="Judul Buku" required>
                <input type="text" class="form-control" id="penulis" placeholder="Penulis" required>
                <input type="number" class="form-control" id="tahun_terbit" placeholder="Tahun Terbit" min="1901" max="2026" required>
            </div>
            
            <div>
                <button type="submit" class="btn btn-primary">Simpan Data</button>
                <button type="button" class="btn btn-secondary" onclick="resetForm()">Batal</button>
            </div>
        </form>
    </div>

    <table class="styled-table">
        <thead>
            <tr>
                <th>Kategori</th>
                <th>Judul</th>
                <th>Penulis</th>
                <th>Tahun Terbit</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="tabel-buku">
            <tr><td colspan="5" style="text-align: center; padding: 20px;">Memuatkan data...</td></tr>
        </tbody>
    </table>

    <script>
    const apiUrl = 'http://127.0.0.1:8000/api/buku';

    async function fetchBuku() {
        try {
            const response = await fetch(apiUrl);
            if (!response.ok) throw new Error('Gagal mengambil data dari API.');
            
            const result = await response.json();
            const dataBuku = result.data;
            let letakHTML = '';

            if (dataBuku.length === 0) {
                letakHTML = '<tr><td colspan="5" style="text-align: center; padding: 20px;">Belum ada data buku.</td></tr>';
            } else {
                dataBuku.forEach(buku => {
                    letakHTML += `
                        <tr>
                            <td>${buku.kategori}</td>
                            <td>${buku.judul}</td>
                            <td>${buku.penulis}</td>
                            <td>${buku.tahun_terbit}</td>
                            <td>
                                <button class="btn btn-warning" onclick="editBuku(${buku.id}, '${buku.judul}', '${buku.penulis}', ${buku.tahun_terbit}, ${buku.kategori_id})">Ubah</button>
                                <button class="btn btn-danger" onclick="hapusBuku(${buku.id})">Hapus</button>
                            </td>
                        </tr>
                    `;
                });
            }
            document.getElementById('tabel-buku').innerHTML = letakHTML;
        } catch (error) {
            document.getElementById('pesan-error').innerText = error.message;
        }
    }

    document.getElementById('form-buku').addEventListener('submit', async function(e) {
        e.preventDefault();
        document.getElementById('pesan-error').innerText = ''; 
        
        const id = document.getElementById('buku_id').value;
        const inputData = {
            kategori_id: document.getElementById('kategori_id').value,
            judul: document.getElementById('judul').value,
            penulis: document.getElementById('penulis').value,
            tahun_terbit: document.getElementById('tahun_terbit').value
        };

        const method = id ? 'PUT' : 'POST';
        const url = id ? `${apiUrl}/${id}` : apiUrl;

        try {
            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(inputData)
            });

            if (!response.ok) {
                const errorData = await response.json();
                if (errorData.errors) {
                    let pesanValidasi = '';
                    for (const key in errorData.errors) {
                        pesanValidasi += errorData.errors[key][0] + '\n';
                    }
                    throw new Error(pesanValidasi);
                } else {
                    throw new Error(errorData.message || 'Proses gagal.');
                }
            }

            resetForm();
            fetchBuku();

        } catch (error) {
            document.getElementById('pesan-error').innerText = error.message;
        }
    });

    function editBuku(id, judul, penulis, tahun, kategori_id) {
        document.getElementById('buku_id').value = id;
        document.getElementById('judul').value = judul;
        document.getElementById('penulis').value = penulis;
        document.getElementById('tahun_terbit').value = tahun;
        document.getElementById('kategori_id').value = kategori_id;
        document.getElementById('pesan-error').innerText = '';
    }

    function resetForm() {
        document.getElementById('form-buku').reset();
        document.getElementById('buku_id').value = '';
        document.getElementById('pesan-error').innerText = '';
    }

    async function hapusBuku(id) {
        if (!confirm('Adakah anda pasti mahu menghapus buku ini?')) return;

        try {
            const response = await fetch(`${apiUrl}/${id}`, {
                method: 'DELETE',
                headers: { 'Accept': 'application/json' }
            });

            if (!response.ok) throw new Error('Gagal menghapus data.');

            fetchBuku();
        } catch (error) {
            document.getElementById('pesan-error').innerText = error.message;
        }
    }

    fetchBuku();
    </script>
@endsection