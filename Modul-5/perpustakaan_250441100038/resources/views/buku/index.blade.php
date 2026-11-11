@extends('layouts.main')

@section('title', 'Daftar Buku')

@section('content')

    <h2 class="page-title">Daftar Buku</h2>

    @can('kelola data buku')
    <div class="form-container">
        <h3 style="margin-top: 0;">Form Buku</h3>
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
    @endcan

    <table class="styled-table">
        <thead>
            <tr>
                <th>Kategori</th>
                <th>Judul</th>
                <th>Penulis</th>
                <th>Tahun Terbit</th>
                <th>Pinjam</th>
                @can('kelola data buku')
                <th>Kelola</th>
                @endcan
            </tr>
        </thead>
        <tbody id="tabel-buku">
            <tr><td colspan="@can('kelola data buku') 6 @else 5 @endcan" style="text-align: center; padding: 20px;">Memuatkan data...</td></tr>
        </tbody>
    </table>

    <script>
    const apiUrl = '{{ url('/api/buku') }}';

    async function fetchBuku() {
        try {
            const response = await fetch(apiUrl, {
                headers: { 'Accept': 'application/json' },
                credentials: 'include'
            });
            if (!response.ok) throw new Error('Gagal mengambil data dari API.');
            
            const result = await response.json();
            const dataBuku = result.data;
            let letakHTML = '';

            if (dataBuku.length === 0) {
                letakHTML = '<tr><td colspan="@can('kelola data buku') 6 @else 5 @endcan" style="text-align: center; padding: 20px;">Belum ada data buku.</td></tr>';
            } else {
                dataBuku.forEach(buku => {
                    const tautanDetail = `
                        <td>
                            <a class="btn" href="{{ url('/buku') }}/${buku.id}">Detail &amp; Pinjam</a>
                        </td>
                    `;
                    let kolomAksi = '';
                    @can('kelola data buku')
                        kolomAksi = `
                            <td>
                                <button class="btn btn-warning" onclick="editBuku(${buku.id}, '${buku.judul}', '${buku.penulis}', ${buku.tahun_terbit}, ${buku.kategori_id})">Ubah</button>
                                <button class="btn btn-danger" onclick="hapusBuku(${buku.id})">Hapus</button>
                            </td>
                        `;
                    @endcan

                    // Logika pendeteksi nama kategori diperbarui di baris ini
                    const namaKategori = buku.kategori ? (buku.kategori.nama || buku.kategori.nama_kategori) : buku.kategori_id;

                    letakHTML += `
                        <tr>
                            <td>${namaKategori}</td>
                            <td><a href="{{ url('/buku') }}/${buku.id}">${buku.judul}</a></td>
                            <td>${buku.penulis}</td>
                            <td>${buku.tahun_terbit}</td>
                            ${tautanDetail}
                            ${kolomAksi}
                        </tr>
                    `;
                });
            }
            document.getElementById('tabel-buku').innerHTML = letakHTML;
        } catch (error) {
            console.error(error.message);
        }
    }

    const formBuku = document.getElementById('form-buku');
    if (formBuku) {
        formBuku.addEventListener('submit', async function(e) {
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
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    credentials: 'include',
                    body: JSON.stringify(inputData)
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    if (errorData.errors) {
                        let pesanValidasi = '';
                        for (const key in errorData.errors) pesanValidasi += errorData.errors[key][0] + '\n';
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
    }

    function editBuku(id, judul, penulis, tahun, kategori_id) {
        if (!document.getElementById('buku_id')) return;
        document.getElementById('buku_id').value = id;
        document.getElementById('judul').value = judul;
        document.getElementById('penulis').value = penulis;
        document.getElementById('tahun_terbit').value = tahun;
        document.getElementById('kategori_id').value = kategori_id;
        document.getElementById('pesan-error').innerText = '';
    }

    function resetForm() {
        if (!document.getElementById('form-buku')) return;
        document.getElementById('form-buku').reset();
        document.getElementById('buku_id').value = '';
        document.getElementById('pesan-error').innerText = '';
    }

    async function hapusBuku(id) {
        if (!confirm('Adakah anda pasti mahu menghapus buku ini?')) return;
        try {
            const response = await fetch(`${apiUrl}/${id}`, {
                method: 'DELETE',
                headers: { 
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                credentials: 'include'
            });
            if (!response.ok) throw new Error('Gagal menghapus data.');
            fetchBuku();
        } catch (error) {
            console.error(error.message);
            alert('Gagal menghapus buku. Pastikan Anda memiliki hak akses.');
        }
    }

    fetchBuku();
    </script>
@endsection