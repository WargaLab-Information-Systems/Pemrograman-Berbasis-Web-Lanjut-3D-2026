let daftarBuku = [];

// Periksa apakah user memiliki hak kelola buku (Admin/Petugas)
const canManage = typeof window.canManageBooks !== 'undefined' ? window.canManageBooks : true;

// Helper untuk mengambil CSRF token dari meta tag atau window
function getCsrfToken() {
    const tokenEl = document.querySelector('meta[name="csrf-token"]');
    if (tokenEl && tokenEl.getAttribute('content')) {
        return tokenEl.getAttribute('content');
    }
    return typeof window.csrfToken !== 'undefined' ? window.csrfToken : '';
}

// Helper untuk escaping HTML jika dibutuhkan
function escapeHtml(value) {
    const div = document.createElement('div');
    div.textContent = value ?? '';
    return div.innerHTML;
}

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
            tbody.innerHTML = '';
            const tr = document.createElement('tr');
            const td = document.createElement('td');
            td.colSpan = 5;
            td.className = 'baris-info baris-error';
            td.textContent = 'Gagal memuat data buku.';
            tr.appendChild(td);
            tbody.appendChild(tr);
        }
    }
}

// Render tabel ke HTML secara aman (menghindari XSS di innerHTML)
function renderTabel() {
    const tableBody = document.getElementById('buku-table-body');
    if (!tableBody) return;

    tableBody.innerHTML = '';

    if (!daftarBuku || daftarBuku.length === 0) {
        const tr = document.createElement('tr');
        const td = document.createElement('td');
        td.colSpan = 5;
        td.className = 'baris-info';
        td.textContent = 'Tidak ada data buku.';
        tr.appendChild(td);
        tableBody.appendChild(tr);
        return;
    }

    const csrfToken = getCsrfToken();

    daftarBuku.forEach((buku, index) => {
        const row = document.createElement('tr');

        let namaKategori = '-';
        if (buku.kategori) {
            namaKategori = typeof buku.kategori === 'object' ? buku.kategori.nama : buku.kategori;
        }

        const tdKategori = document.createElement('td');
        tdKategori.textContent = namaKategori ?? '-';

        const tdJudul = document.createElement('td');
        tdJudul.textContent = buku.judul ?? '';

        const tdPenulis = document.createElement('td');
        tdPenulis.textContent = buku.penulis ?? '';

        const tdTahun = document.createElement('td');
        tdTahun.textContent = buku.tahun_terbit ?? '';

        const tdAksi = document.createElement('td');
        const safeId = Number(buku.id) || 0;

        if (canManage) {
            const btnUbah = document.createElement('button');
            btnUbah.type = 'button';
            btnUbah.className = 'btn-ubah';
            btnUbah.textContent = 'Ubah';
            btnUbah.addEventListener('click', () => siapEdit(index));

            // Form POST dengan _token dan _method=DELETE ke route DELETE /buku/{id}
            const formHapus = document.createElement('form');
            formHapus.action = `/buku/${safeId}`;
            formHapus.method = 'POST';
            formHapus.style.display = 'inline';
            formHapus.addEventListener('submit', (e) => {
                if (!confirm('Apakah Anda yakin ingin menghapus buku ini?')) {
                    e.preventDefault();
                }
            });

            const inputToken = document.createElement('input');
            inputToken.type = 'hidden';
            inputToken.name = '_token';
            inputToken.value = csrfToken;

            const inputMethod = document.createElement('input');
            inputMethod.type = 'hidden';
            inputMethod.name = '_method';
            inputMethod.value = 'DELETE';

            const btnHapus = document.createElement('button');
            btnHapus.type = 'submit';
            btnHapus.className = 'btn-hapus';
            btnHapus.textContent = 'Hapus';

            formHapus.appendChild(inputToken);
            formHapus.appendChild(inputMethod);
            formHapus.appendChild(btnHapus);

            tdAksi.appendChild(btnUbah);
            tdAksi.appendChild(document.createTextNode(' '));
            tdAksi.appendChild(formHapus);
        } else {
            const linkDetail = document.createElement('a');
            linkDetail.href = `/buku/${safeId}`;
            linkDetail.className = 'btn-detail';
            linkDetail.textContent = 'Detail';

            const linkPinjam = document.createElement('a');
            linkPinjam.href = `/peminjaman/create?buku_id=${safeId}`;
            linkPinjam.className = 'btn-pinjam';
            linkPinjam.textContent = 'Pinjam';

            tdAksi.appendChild(linkDetail);
            tdAksi.appendChild(document.createTextNode(' '));
            tdAksi.appendChild(linkPinjam);
        }

        row.appendChild(tdKategori);
        row.appendChild(tdJudul);
        row.appendChild(tdPenulis);
        row.appendChild(tdTahun);
        row.appendChild(tdAksi);

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
            const token = getCsrfToken();

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