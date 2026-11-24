let daftarBuku = [];

// 1. Fungsi untuk mengambil data buku dari API (GET)
async function fetchBuku() {
    try {
        const response = await fetch('/api/buku');
        if (!response.ok) throw new Error('Gagal mengambil data dari server');

        const result = await response.json();

        // Menyimpan data buku dari respons API
        daftarBuku = result.data;

        renderTabel();
    } catch (error) {
        console.error('Terjadi kesalahan:', error);
        document.getElementById('buku-table-body').innerHTML = `
            <tr><td colspan="5" class="baris-info baris-error">Gagal memuat data buku.</td></tr>
        `;
    }
}

// Render tabel ke HTML
function renderTabel() {
    const tableBody = document.getElementById('buku-table-body');
    tableBody.innerHTML = '';

    if (!daftarBuku || daftarBuku.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="5" class="baris-info">Tidak ada data buku.</td></tr>';
        return;
    }

    daftarBuku.forEach((buku, index) => {
        const row = document.createElement('tr');
        const namaKategori = buku.kategori ? buku.kategori : '-';

        row.innerHTML = `
            <td>${namaKategori}</td>
            <td>${buku.judul}</td>
            <td>${buku.penulissss}</td>
            <td>${buku.tahun_terbit}</td>
            <td>
                <button type="button" class="btn-ubah" onclick="siapEdit(${index})">Ubah</button>
                <button type="button" class="btn-hapus" onclick="hapusBuku(${buku.id})">Hapus</button>
            </td>
        `;
        tableBody.appendChild(row);
    });
}

// Panggil fetch saat halaman dimuat
document.addEventListener('DOMContentLoaded', fetchBuku);

// 2. Fungsi saat tombol "Ubah" diklik
function siapEdit(index) {
    const buku = daftarBuku[index];

    document.getElementById('buku_id').value = buku.id;
    document.getElementById('kategori_id').value = buku.kategori_id;
    document.getElementById('judul').value = buku.judul;
    document.getElementById('penulis').value = buku.penulis;
    document.getElementById('tahun_terbit').value = buku.tahun_terbit;

    // Ubah tampilan teks form dan tombol
    document.getElementById('form-title').innerText = 'Ubah Buku: ' + buku.judul;
    document.getElementById('btn-submit').innerText = 'Perbarui Buku';
    document.getElementById('btn-cancel').style.display = 'inline-block';

    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Fungsi Batal Edit
function resetForm() {
    document.getElementById('form-buku').reset();
    document.getElementById('buku_id').value = '';
    document.getElementById('form-title').innerText = 'Tambah Buku Baru';
    document.getElementById('btn-submit').innerText = 'Simpan Buku';
    document.getElementById('btn-cancel').style.display = 'none';
}

// 3. Event Listener untuk Submit Form (POST untuk Tambah, PUT untuk Ubah)
document.getElementById('form-buku').addEventListener('submit', async function (e) {
    e.preventDefault();

    const id = document.getElementById('buku_id').value;
    const formData = {
        kategori_id: document.getElementById('kategori_id').value,
        judul: document.getElementById('judul').value,
        penulis: document.getElementById('penulis').value,
        tahun_terbit: document.getElementById('tahun_terbit').value
    };

    const url = id ? `/api/buku/${id}` : '/api/buku';
    const method = id ? 'PUT' : 'POST';

    try {
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(formData)
        });

        if (!response.ok) {
            const errorData = await response.json();
            alert('Gagal menyimpan: ' + (errorData.message || JSON.stringify(errorData.errors)));
            return;
        }

        if (id) {
            alert('Data buku berhasil diperbarui!');
        } else {
            alert('Data buku berhasil ditambahkan!');
        }

        // Ambil ulang data terbaru dari server dan reset form
        await fetchBuku();
        resetForm();

    } catch (error) {
        console.error('Terjadi kesalahan:', error);
        alert('Terjadi kesalahan jaringan atau server.');
    }
});

// 4. Fungsi untuk Menghapus Buku (DELETE)
async function hapusBuku(id) {
    // Tampilkan dialog konfirmasi sebelum menghapus
    if (!confirm('Apakah Anda yakin ingin menghapus buku ini?')) {
        return; // Jika pengguna memilih Batal, proses dibatalkan
    }

    try {
        const response = await fetch(`/api/buku/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        if (!response.ok) {
            const errorData = await response.json();
            alert('Gagal menghapus: ' + (errorData.message || 'Terjadi kesalahan pada server'));
            return;
        }

        alert('Data buku berhasil dihapus!');

        // Perbarui tabel secara otomatis setelah berhasil menghapus
        await fetchBuku();

    } catch (error) {
        console.error('Terjadi kesalahan:', error);
        alert('Terjadi kesalahan jaringan atau server.');
    }
}