

let daftarBukuCache = [];


function tampilkanPesan(pesan, jenis = 'success') {
    const alertBox = document.getElementById('alert-box');
    if (!alertBox) return;

    alertBox.className = 'alert ' + (jenis === 'success' ? 'alert-success' : 'alert-danger');
    alertBox.innerHTML = `
        <span>${pesan}</span>
        <button type="button"
            style="background:none;border:none;cursor:pointer;font-size:16px;color:inherit;font-weight:bold;margin-left:12px;"
            onclick="tutupPesan()">&times;</button>
    `;
    alertBox.style.display = 'flex';

    if (jenis === 'success') {
        setTimeout(() => {
            tutupPesan();
        }, 5000);
    }
}

function tutupPesan() {
    const alertBox = document.getElementById('alert-box');
    if (alertBox) {
        alertBox.style.display = 'none';
    }
}

async function tampilkanBuku() {
    const tbody = document.getElementById('tabel-buku-body');
    if (!tbody) return;

    try {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center py-4">
                    <span class="spinner"></span> Memuat data buku dari API...
                </td>
            </tr>
        `;

        const response = await fetch('/api/buku', {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error(`Gagal mengambil data buku dari server (Status: ${response.status})`);
        }

        const hasil = await response.json();
        const dataBuku = hasil.data ?? hasil;

        daftarBukuCache = Array.isArray(dataBuku) ? dataBuku : [];
        tbody.innerHTML = '';

        if (daftarBukuCache.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">
                        Belum ada koleksi buku yang tersedia.
                    </td>
                </tr>
            `;
            return;
        }

        daftarBukuCache.forEach((item, index) => {
            const tr = document.createElement('tr');

            const tdNo = document.createElement('td');
            tdNo.textContent = index + 1;

            const tdJudul = document.createElement('td');
            const strong = document.createElement('strong');
            strong.textContent = item.judul ?? '-';
            tdJudul.appendChild(strong);

            const tdKategori = document.createElement('td');
            const spanKategori = document.createElement('span');
            spanKategori.className = 'badge-kategori';
            spanKategori.textContent = item.kategori ?? '-';
            tdKategori.appendChild(spanKategori);

            const tdPenulis = document.createElement('td');
            tdPenulis.textContent = item.penulis ?? '-';

            const tdTahun = document.createElement('td');
            tdTahun.textContent = item.tahun_terbit ?? '-';

            const tdAksi = document.createElement('td');
            tdAksi.className = 'action-cell';

            const btnUbah = document.createElement('button');
            btnUbah.type = 'button';
            btnUbah.className = 'btn-edit';
            btnUbah.textContent = ' Ubah';
            btnUbah.onclick = () => isiFormUbah(item.id);

            const btnHapus = document.createElement('button');
            btnHapus.type = 'button';
            btnHapus.className = 'btn-delete';
            btnHapus.textContent = ' Hapus';
            btnHapus.onclick = () => hapusBuku(item.id, item.judul ?? 'Buku ini');

            tdAksi.append(btnUbah, btnHapus);
            tr.append(tdNo, tdJudul, tdKategori, tdPenulis, tdTahun, tdAksi);
            tbody.appendChild(tr);
        });

    } catch (error) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center py-4" style="color: #dc2626;">
                    ${escapeHtml(error.message || 'Terjadi kesalahan saat mengambil data buku.')}
                </td>
            </tr>
        `;

        tampilkanPesan(escapeHtml(error.message || 'Gagal memuat data buku dari server.'), 'danger');
    }
}

async function handleFormSubmit(event) {
    event.preventDefault();

    const bukuId = document.getElementById('buku-id').value;
    const kategoriId = document.getElementById('kategori_id').value;
    const judul = document.getElementById('judul').value.trim();
    const penulis = document.getElementById('penulis').value.trim();
    const tahunTerbit = document.getElementById('tahun_terbit').value;

    if (!kategoriId || !judul || !penulis || !tahunTerbit) {
        tampilkanPesan('Semua kolom formulir wajib diisi.', 'danger');
        return;
    }

    const payload = {
        kategori_id: parseInt(kategoriId),
        judul: judul,
        penulis: penulis,
        tahun_terbit: parseInt(tahunTerbit)
    };

    const isEdit = Boolean(bukuId);
    const endpoint = isEdit ? `/api/buku/${bukuId}` : '/api/buku';
    const method = isEdit ? 'PUT' : 'POST';

    const btnSubmit = document.getElementById('btn-submit');
    const btnSubmitText = document.getElementById('btn-submit-text');
    const originalText = btnSubmitText ? btnSubmitText.textContent : 'Simpan Buku';

    try {
        if (btnSubmit) btnSubmit.disabled = true;
        if (btnSubmitText) btnSubmitText.textContent = isEdit ? 'Memperbarui...' : 'Menyimpan...';

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        };
        if (csrfToken) {
            headers['X-CSRF-TOKEN'] = csrfToken;
        }

        const response = await fetch(endpoint, {
            method: method,
            headers: headers,
            body: JSON.stringify(payload)
        });

        const data = await response.json();

        if (!response.ok) {
            if (response.status === 422 && data.errors) {
                const errorMessages = Object.values(data.errors)
                    .flat()
                    .map(escapeHtml)
                    .join('<br>');
                throw new Error(errorMessages || 'Validasi formulir gagal.');
            }
            throw new Error(data.message || `Operasi gagal dengan status HTTP ${response.status}`);
        }

        batalEdit();
        await tampilkanBuku();

        tampilkanPesan(
            escapeHtml(data.message || (isEdit ? 'Buku berhasil diperbarui!' : 'Buku berhasil ditambahkan!')),
            'success'
        );

    } catch (error) {
        tampilkanPesan(error.message || 'Terjadi kesalahan saat memproses data.', 'danger');
    } finally {
        if (btnSubmit) btnSubmit.disabled = false;
        if (btnSubmitText) btnSubmitText.textContent = originalText;
    }
}


function isiFormUbah(id) {
    const buku = daftarBukuCache.find(b => b.id == id);

    if (!buku) {
        tampilkanPesan('Data buku tidak ditemukan.', 'danger');
        return;
    }

    document.getElementById('buku-id').value = buku.id;
    document.getElementById('kategori_id').value = buku.kategori_id ?? '';
    document.getElementById('judul').value = buku.judul ?? '';
    document.getElementById('penulis').value = buku.penulis ?? '';
    document.getElementById('tahun_terbit').value = buku.tahun_terbit ?? '';

    const formBadge = document.getElementById('form-badge');
    if (formBadge) {
        formBadge.className = 'badge-pill badge-warning';
        formBadge.textContent = 'Mode Ubah';
    }

    const formTitle = document.getElementById('form-title');
    if (formTitle) {
        formTitle.textContent = `Ubah Buku: "${buku.judul ?? ''}"`;
    }

    const btnSubmitText = document.getElementById('btn-submit-text');
    if (btnSubmitText) {
        btnSubmitText.textContent = 'Simpan Perubahan';
    }

    const btnCancel = document.getElementById('btn-cancel');
    if (btnCancel) btnCancel.style.display = 'inline-block';

    const btnCancelTop = document.getElementById('btn-cancel-top');
    if (btnCancelTop) btnCancelTop.style.display = 'inline-block';

    const formCard = document.querySelector('.form-card');
    if (formCard) {
        formCard.scrollIntoView({ behavior: 'smooth' });
    }
}


function batalEdit() {
    const form = document.getElementById('form-buku');
    if (form) form.reset();

    const bukuId = document.getElementById('buku-id');
    if (bukuId) bukuId.value = '';

    const formBadge = document.getElementById('form-badge');
    if (formBadge) {
        formBadge.className = 'badge-pill badge-primary';
        formBadge.textContent = 'Form Tambah';
    }

    const formTitle = document.getElementById('form-title');
    if (formTitle) formTitle.textContent = 'Tambah Buku Baru';

    const btnSubmitText = document.getElementById('btn-submit-text');
    if (btnSubmitText) btnSubmitText.textContent = 'Simpan Buku';

    const btnCancel = document.getElementById('btn-cancel');
    if (btnCancel) btnCancel.style.display = 'none';

    const btnCancelTop = document.getElementById('btn-cancel-top');
    if (btnCancelTop) btnCancelTop.style.display = 'none';
}


async function hapusBuku(id, judul) {
    const yakin = confirm(`Apakah Anda yakin ingin menghapus buku "${judul}"?`);
    if (!yakin) return;

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const headers = {
            'Accept': 'application/json'
        };
        if (csrfToken) {
            headers['X-CSRF-TOKEN'] = csrfToken;
        }

        const response = await fetch(`/api/buku/${id}`, {
            method: 'DELETE',
            headers: headers
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || `Gagal menghapus buku (Status: ${response.status})`);
        }

        if (document.getElementById('buku-id').value == id) {
            batalEdit();
        }

        await tampilkanBuku();

        tampilkanPesan(
            escapeHtml(data.message || 'Buku berhasil dihapus dari basis data.'),
            'success'
        );

    } catch (error) {
        tampilkanPesan(escapeHtml(error.message || 'Gagal menghapus buku.'), 'danger');
    }
}

function escapeHtml(text) {
    if (typeof text !== 'string') return String(text ?? '');
    return text
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', tampilkanBuku);
} else {
    tampilkanBuku();
}
