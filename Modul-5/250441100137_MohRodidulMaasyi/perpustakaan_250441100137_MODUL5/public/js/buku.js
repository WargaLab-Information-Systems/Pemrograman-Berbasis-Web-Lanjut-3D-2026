let daftarBuku = [];

// Periksa apakah user memiliki hak kelola buku (Admin/Petugas)
const canManage = typeof window.canManageBooks !== 'undefined' ? window.canManageBooks : true;

// 1. Fungsi untuk mengambil data buku dari API (GET)
async function fetchBuku() {
    try {
        const response = await fetch('/api/buku');
        if (!response.ok) throw new Error('Gagal mengambil data dari server');

        const result = await response.json();

        // Menyesuaikan berbagai format respons JSON (Array langsung, objek .data, atau pagination)
        if (Array.isArray(result)) {
            daftarBuku = result;
        } else if (result.data && Array.isArray(result.data)) {
            daftarBuku = result.data;
        } else if (result.data && result.data.data && Array.isArray(result.data.data)) {
            daftarBuku = result.data.data;
        } else {
            daftarBuku = [];
        }

        renderTabel();
    } catch (error) {
        console.error('Terjadi kesalahan:', error);
        const tbody = document.getElementById('buku-table-body');
        if (tbody && tbody.children.length === 0) {
            tbody.innerHTML = `
                <tr><td colspan="5" class="baris-info baris-error">Gagal memuat data buku.</td></tr>
            `;
        }
    }
}

// Render tabel ke HTML persis seperti tampilan gambar
function renderTabel() {
    const tableBody = document.getElementById('buku-table-body');
    if (!tableBody) return;

    tableBody.innerHTML = '';

    if (!daftarBuku || daftarBuku.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="5" class="baris-info">Tidak ada data buku.</td></tr>';
        return;
    }

    daftarBuku.forEach((buku, index) => {
        const row = document.createElement('tr');
        
        let namaKategori = '-';
        if (buku.kategori) {
            namaKategori = typeof buku.kategori === 'object' ? buku.kategori.nama : buku.kategori;
        }

        let aksiHtml = '';
        if (canManage) {
            aksiHtml = `
                <button type="button" class="btn-ubah" onclick="siapEdit(${index})">Ubah</button>
                <button type="button" class="btn-hapus" onclick="hapusBuku(${buku.id})">Hapus</button>
            `;
        } else {
            aksiHtml = `
                <a href="/buku/${buku.id}" class="btn-detail">Detail</a>
                <a href="/peminjaman/create?buku_id=${buku.id}" class="btn-pinjam">Pinjam</a>
            `;
        }

        row.innerHTML = `
            <td>${namaKategori}</td>
            <td>${buku.judul}</td>
            <td>${buku.penulis}</td>
            <td>${buku.tahun_terbit}</td>
            <td>${aksiHtml}</td>
        `;
        tableBody.appendChild(row);
    });
}

// Panggil fetch saat halaman dimuat
document.addEventListener('DOMContentLoaded', fetchBuku);

// 2. Fungsi saat tombol "Ubah" diklik
function siapEdit(index) {
    const buku = daftarBuku[index];
    if (!buku) return;

    const elId = document.getElementById('buku_id');
    const elKat = document.getElementById('kategori_id');
    const elJudul = document.getElementById('judul');
    const elPenulis = document.getElementById('penulis');
    const elTahun = document.getElementById('tahun_terbit');

    if (elId) elId.value = buku.id;
    if (elKat) elKat.value = buku.kategori_id;
    if (elJudul) elJudul.value = buku.judul;
    if (elPenulis) elPenulis.value = buku.penulis;
    if (elTahun) elTahun.value = buku.tahun_terbit;

    const elTitle = document.getElementById('form-title');
    const elSubmit = document.getElementById('btn-submit');
    const elCancel = document.getElementById('btn-cancel');

    if (elTitle) elTitle.innerText = 'Ubah Buku: ' + buku.judul;
    if (elSubmit) elSubmit.innerText = 'Perbarui Buku';
    if (elCancel) elCancel.style.display = 'inline-block';

    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Fungsi Batal Edit
function resetForm() {
    const form = document.getElementById('form-buku');
    if (form) form.reset();
    
    const elId = document.getElementById('buku_id');
    const elTitle = document.getElementById('form-title');
    const elSubmit = document.getElementById('btn-submit');
    const elCancel = document.getElementById('btn-cancel');

    if (elId) elId.value = '';
    if (elTitle) elTitle.innerText = 'Tambah Buku Baru';
    if (elSubmit) elSubmit.innerText = 'Simpan Buku';
    if (elCancel) elCancel.style.display = 'none';
}

// 3. Event Listener untuk Submit Form
const formBuku = document.getElementById('form-buku');
if (formBuku) {
    formBuku.addEventListener('submit', async function (e) {
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
            const tokenEl = document.querySelector('meta[name="csrf-token"]');
            const token = tokenEl ? tokenEl.getAttribute('content') : '';

            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
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

            await fetchBuku();
            resetForm();

        } catch (error) {
            console.error('Terjadi kesalahan:', error);
            alert('Terjadi kesalahan jaringan atau server.');
        }
    });
}

// 4. Fungsi untuk Menghapus Buku (DELETE)
async function hapusBuku(id) {
    if (!confirm('Apakah Anda yakin ingin menghapus buku ini?')) {
        return;
    }

    try {
        const tokenEl = document.querySelector('meta[name="csrf-token"]');
        const token = tokenEl ? tokenEl.getAttribute('content') : '';

        const response = await fetch(`/api/buku/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token
            }
        });

        if (!response.ok) {
            const errorData = await response.json();
            alert('Gagal menghapus: ' + (errorData.message || 'Terjadi kesalahan pada server'));
            return;
        }

        alert('Data buku berhasil dihapus!');
        await fetchBuku();

    } catch (error) {
        console.error('Terjadi kesalahan:', error);
        alert('Terjadi kesalahan jaringan atau server.');
    }
}
