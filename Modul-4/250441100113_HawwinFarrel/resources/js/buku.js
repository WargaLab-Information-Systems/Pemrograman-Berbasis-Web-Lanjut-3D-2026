document.addEventListener('DOMContentLoaded', () => {
    muatDataBuku();
    inisialisasiForm();
});

// Menampilkan pesan notifikasi sukses / error
function tampilkanPesan(pesan, tipe = 'success') {
    const alertBox = document.getElementById('alert-box');
    if (!alertBox) return;

    alertBox.style.display = 'block';
    if (tipe === 'success') {
        alertBox.style.backgroundColor = '#d4edda';
        alertBox.style.color = '#155724';
        alertBox.style.border = '1px solid #c3e6cb';
    } else {
        alertBox.style.backgroundColor = '#f8d7da';
        alertBox.style.color = '#721c24';
        alertBox.style.border = '1px solid #f5c6cb';
    }
    alertBox.innerHTML = pesan;
}

// Reset isi formulir
function resetForm() {
    const form = document.getElementById('form-tambah-buku');
    if (!form) return;

    form.reset();
    document.getElementById('buku_id').value = '';
    document.getElementById('form-title').innerText = 'Tambah Buku Baru';
    document.getElementById('btn-submit').innerText = 'Simpan Buku';
    document.getElementById('btn-cancel').style.display = 'none';
}

// Inisialisasi event submit form (POST / PUT)
function inisialisasiForm() {
    const form = document.getElementById('form-tambah-buku');
    if (!form) return;

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        const id = document.getElementById('buku_id').value;
        const isEdit = id !== '';
        const url = isEdit ? `/api/buku/${id}` : '/api/buku';
        const method = isEdit ? 'PUT' : 'POST';

        const payload = {
            kategori_id: document.getElementById('kategori_id').value,
            judul: document.getElementById('judul').value,
            penulis: document.getElementById('penulis').value,
            tahun_terbit: document.getElementById('tahun_terbit').value
        };

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

            if (!response.ok) {
                if (response.status === 422 && result.errors) {
                    const daftarError = Object.values(result.errors).flat().join('<br>• ');
                    throw new Error('• ' + daftarError);
                }
                throw new Error(result.message || 'Gagal memproses data buku.');
            }

            tampilkanPesan(result.message || 'Data berhasil disimpan!', 'success');
            resetForm();
            await muatDataBuku();

        } catch (error) {
            tampilkanPesan('<strong>Kesalahan:</strong><br>' + error.message, 'error');
        }
    });
}

// get /api/buku 
async function muatDataBuku() {
    const tbody = document.getElementById('tabel-buku-body');
    if (!tbody) return;

    try {
        const response = await fetch('/api/buku', {
            method: 'GET',
            headers: { 'Accept': 'application/json' }
        });

        if (!response.ok) {
            throw new Error(`API tidak dapat diakses (Status: ${response.status})`);
        }

        const result = await response.json();
        const daftarBuku = result.data || [];

        tbody.innerHTML = '';

        if (daftarBuku.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align: center;">Belum ada data buku.</td></tr>';
            return;
        }

        daftarBuku.forEach((item, index) => {
            tbody.innerHTML += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.kategori}</td>
                    <td>${item.judul}</td>
                    <td>${item.penulis}</td>
                    <td>${item.tahun}</td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="pilihBuku(${item.id})">Ubah</button>
                        <button class="btn btn-sm btn-danger" onclick="hapusBuku(${item.id})">Hapus</button>
                    </td>
                </tr>
            `;
        });

    } catch (error) {
        tbody.innerHTML = `<tr><td colspan="6" style="text-align: center; color: red;">${error.message}</td></tr>`;
        tampilkanPesan('Gagal memuat data buku dari server.', 'error');
    }
}

// Mengambil data satu buku untuk dimasukkan ke formulir ubah
async function pilihBuku(id) {
    try {
        const response = await fetch(`/api/buku/${id}`, {
            method: 'GET',
            headers: { 'Accept': 'application/json' }
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || 'Gagal mengambil detail buku');
        }

        const buku = result.data;
        document.getElementById('buku_id').value = buku.id;
        document.getElementById('kategori_id').value = buku.kategori_id;
        document.getElementById('judul').value = buku.judul;
        document.getElementById('penulis').value = buku.penulis;
        document.getElementById('tahun_terbit').value = buku.tahun;

        document.getElementById('form-title').innerText = 'Ubah Data Buku';
        document.getElementById('btn-submit').innerText = 'Perbarui Buku';
        document.getElementById('btn-cancel').style.display = 'inline-block';

        window.scrollTo({ top: 0, behavior: 'smooth' });

    } catch (error) {
        tampilkanPesan('Gagal mengambil data untuk diubah: ' + error.message, 'error');
    }
}

// Menghapus data buku (DELETE /api/buku/{id})
async function hapusBuku(id) {
    if (!confirm('Apakah Anda yakin ingin menghapus buku ini?')) return;

    try {
        const response = await fetch(`/api/buku/${id}`, {
            method: 'DELETE',
            headers: { 'Accept': 'application/json' }
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || 'Gagal menghapus buku');
        }

        tampilkanPesan(result.message || 'Buku berhasil dihapus.', 'success');
        await muatDataBuku();

    } catch (error) {
        tampilkanPesan('Gagal menghapus data: ' + error.message, 'error');
    }
}

window.pilihBuku = pilihBuku;
window.hapusBuku = hapusBuku;
window.resetForm = resetForm;