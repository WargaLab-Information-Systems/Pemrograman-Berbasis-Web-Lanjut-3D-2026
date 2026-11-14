document.addEventListener('DOMContentLoaded', () => {
    muatDataBuku();
    inisialisasiForm();
});

function tampilkanPesan(pesan, tipe = 'success') {
    const alertBox = document.getElementById('alert-box');
    if (!alertBox) return;

    if (tipe === 'success') {
        alertBox.style.background = '#f0fdf4';
        alertBox.style.border = '1px solid #bbf7d0';
        alertBox.style.color = '#166534';
    } else {
        alertBox.style.background = '#fef2f2';
        alertBox.style.border = '1px solid #fecaca';
        alertBox.style.color = '#991b1b';
    }

    alertBox.style.display = 'flex';
    alertBox.style.justifyContent = 'space-between';
    alertBox.style.alignItems = 'center';
    alertBox.innerHTML = `
        <div>${pesan}</div>
        <button type="button" class="btn-close shadow-none" style="font-size:0.65rem;" onclick="this.parentElement.style.display='none'"></button>
    `;

    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function resetForm() {
    const form = document.getElementById('form-tambah-buku');
    if (!form) return;

    form.reset();
    document.getElementById('buku_id').value = '';
    document.getElementById('form-title').innerText = 'Tambah Koleksi Baru';
    document.getElementById('btn-submit').innerText = 'Simpan Koleksi';
    document.getElementById('btn-cancel').style.display = 'none';
}

function inisialisasiForm() {
    const form = document.getElementById('form-tambah-buku');
    if (!form) return;

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        const id = document.getElementById('buku_id').value;
        const isEdit = id !== '';
        const url = isEdit ? `/api/buku/${id}` : '/api/buku';
        const method = isEdit ? 'PUT' : 'POST';
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

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
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(payload)
            });

            const result = await response.json();

            if (!response.ok) {
                if (response.status === 422 && result.errors) {
                    const pesanError = Object.values(result.errors)
                        .flat()
                        .map(err => `<div>• ${err}</div>`)
                        .join('');
                    throw new Error(pesanError);
                }
                throw new Error(result.message || 'Gagal memproses data buku.');
            }

            tampilkanPesan(result.message || 'Katalog berhasil diperbarui.', 'success');
            resetForm();
            await muatDataBuku();

        } catch (error) {
            tampilkanPesan(`<strong>Perhatian:</strong><br>${error.message}`, 'error');
        }
    });
}

async function muatDataBuku() {
    const tbody = document.getElementById('tabel-buku-body');
    if (!tbody) return;

    const canManageBuku = document.getElementById('buku-app')?.dataset.canManage === 'true';
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    try {
        const response = await fetch('/api/buku', {
            method: 'GET',
            headers: { 
                'Accept': 'application/json' 
            }
        });

        if (!response.ok) {
            throw new Error(`Katalog tidak dapat diakses (Status: ${response.status})`);
        }

        const result = await response.json();
        const daftarBuku = result.data || [];

        tbody.innerHTML = '';

        if (daftarBuku.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4 text-muted small">Belum ada koleksi buku yang terdaftar.</td></tr>';
            return;
        }

        daftarBuku.forEach((item, index) => {
            let tombolAksi = `
                <a href="/buku/${item.id}" class="btn btn-sm btn-subtle py-1 px-2" style="font-size:0.75rem;">Detail</a>
            `;

            if (canManageBuku) {
                tombolAksi += `
                    <button class="btn btn-sm btn-subtle py-1 px-2" style="font-size:0.75rem;" onclick="pilihBuku(${item.id})">Ubah</button>
                    <button class="btn-action-danger" onclick="hapusBuku(${item.id})">Hapus</button>
                `;
            } else {
                tombolAksi += `
                    <form action="/peminjaman/ajukan" method="POST" class="d-inline mb-0">
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <input type="hidden" name="buku_id" value="${item.id}">
                        <button type="submit" class="btn btn-sm btn-brand py-1 px-2" style="font-size:0.75rem;" onclick="return confirm('Ajukan pinjaman buku ini?')">Pinjam</button>
                    </form>
                `;
            }

            tbody.innerHTML += `
                <tr>
                    <td class="text-center text-muted" style="font-size:0.8125rem;">${index + 1}</td>
                    <td>
                        <span class="badge border bg-light text-secondary fw-normal px-2 py-1" style="font-size:0.75rem;">${item.kategori}</span>
                    </td>
                    <td>
                        <div class="fw-semibold text-dark">${item.judul}</div>
                    </td>
                    <td class="text-secondary small">${item.penulis}</td>
                    <td class="text-center text-secondary small" style="font-variant-numeric: tabular-nums;">${item.tahun}</td>
                    <td class="text-end">
                        <div class="d-inline-flex align-items-center gap-1">
                            ${tombolAksi}
                        </div>
                    </td>
                </tr>
            `;
        });

    } catch (error) {
        tbody.innerHTML = `<tr><td colspan="6" class="text-center py-4 text-danger small">Gagal memuat data koleksi: ${error.message}</td></tr>`;
        tampilkanPesan('Gagal mengambil data dari server.', 'error');
    }
}

async function pilihBuku(id) {
    try {
        const response = await fetch(`/api/buku/${id}`, {
            method: 'GET',
            headers: { 
                'Accept': 'application/json' 
            }
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || 'Gagal memuat data buku');
        }

        const buku = result.data;
        document.getElementById('buku_id').value = buku.id;
        document.getElementById('kategori_id').value = buku.kategori_id;
        document.getElementById('judul').value = buku.judul;
        document.getElementById('penulis').value = buku.penulis;
        document.getElementById('tahun_terbit').value = buku.tahun;

        document.getElementById('form-title').innerText = 'Perbarui Data Koleksi';
        document.getElementById('btn-submit').innerText = 'Simpan Perubahan';
        document.getElementById('btn-cancel').style.display = 'inline-block';

        window.scrollTo({ top: 0, behavior: 'smooth' });

    } catch (error) {
        tampilkanPesan('Gagal mengambil data buku: ' + error.message, 'error');
    }
}

async function hapusBuku(id) {
    if (!confirm('Hapus koleksi buku ini secara permanen?')) return;

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    try {
        const response = await fetch(`/api/buku/${id}`, {
            method: 'DELETE',
            headers: { 
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
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