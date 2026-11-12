const API_URL = '/api/buku';

document.addEventListener('DOMContentLoaded', () => {
    loadBukuData();
});

async function loadBukuData() {
    const tbody = document.getElementById('tabel-buku-body');
    try {
        const response = await fetch(API_URL, {
            headers: { 'Accept': 'application/json' }
        });

        if (!response.ok) {
            throw new Error(`HTTP Error! Status: ${response.status}`);
        }

        const result = await response.json();
        tbody.innerHTML = '';

        if (result.data && result.data.length > 0) {
            result.data.forEach((buku, index) => {
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-slate-50/70 transition-colors';
                
                const kategoriNama = (buku.kategori && buku.kategori !== '-') ? buku.kategori : 'Tanpa Kategori';

                tr.innerHTML = `
                    <td class="px-5 py-4 text-center font-medium text-slate-400 text-xs">${index + 1}</td>
                    <td class="px-5 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-blue-50 text-blue-600 border border-blue-100">
                            ${escapeHtml(kategoriNama)}
                        </span>
                    </td>
                    <td class="px-5 py-4 font-semibold text-slate-800">${escapeHtml(buku.judul)}</td>
                    <td class="px-5 py-4 text-slate-600">${escapeHtml(buku.penulis)}</td>
                    <td class="px-5 py-4 text-center text-slate-500 font-medium">${buku.tahun}</td>
                    <td class="px-5 py-4 text-center">
                        <div class="flex items-center justify-center gap-1.5">
                            <button onclick="editBuku(${buku.id}, '${buku.kategori_id}', '${escapeHtml(buku.judul)}', '${escapeHtml(buku.penulis)}', ${buku.tahun})" 
                                class="px-3 py-1.5 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 border border-amber-200/60 font-medium text-xs transition active:scale-95">
                                Ubah
                            </button>
                            <button onclick="deleteBuku(${buku.id})" 
                                class="px-3 py-1.5 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 border border-rose-200/60 font-medium text-xs transition active:scale-95">
                                Hapus
                            </button>
                        </div>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        } else {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-5 py-12 text-center text-slate-400">
                        Belum ada koleksi buku yang tersimpan.
                    </td>
                </tr>`;
        }
    } catch (error) {
        showAlert('Gagal mengambil data buku: ' + error.message, 'danger');
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="px-5 py-8 text-center text-rose-500 font-medium text-xs">
                    Gagal memuat data dari server. Coba refresh halaman.
                </td>
            </tr>`;
    }
}

async function handleFormSubmit(event) {
    event.preventDefault();

    const id = document.getElementById('buku_id').value;
    const payload = {
        kategori_id: document.getElementById('kategori_id').value,
        judul: document.getElementById('judul').value,
        penulis: document.getElementById('penulis').value,
        tahun_terbit: document.getElementById('tahun_terbit').value
    };

    const isEdit = id !== '';
    const url = isEdit ? `${API_URL}/${id}` : API_URL;
    const method = isEdit ? 'PUT' : 'POST';

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
                const errorMessages = Object.values(result.errors).flat().join('<br>');
                showAlert('<strong>Validasi Gagal:</strong><br>' + errorMessages, 'danger');
            } else {
                showAlert(result.message || 'Terjadi kesalahan saat menyimpan data.', 'danger');
            }
            return;
        }

        showAlert(isEdit ? 'Buku berhasil diperbarui!' : 'Buku baru berhasil ditambahkan!', 'success');
        resetForm();
        loadBukuData();
    } catch (error) {
        showAlert('Gagal menghubungkan ke server: ' + error.message, 'danger');
    }
}

function editBuku(id, kategori_id, judul, penulis, tahun) {
    document.getElementById('form-title').innerText = 'Ubah Data Buku';
    document.getElementById('buku_id').value = id;
    document.getElementById('kategori_id').value = kategori_id;
    document.getElementById('judul').value = judul;
    document.getElementById('penulis').value = penulis;
    document.getElementById('tahun_terbit').value = tahun;

    const btnSubmit = document.getElementById('btn-submit');
    btnSubmit.innerText = 'Perbarui Buku';
    btnSubmit.className = 'w-full bg-amber-500 hover:bg-amber-600 text-white font-semibold text-sm py-2.5 px-4 rounded-xl shadow-sm transition active:scale-[0.98]';

    const btnCancel = document.getElementById('btn-cancel');
    btnCancel.classList.remove('hidden');

    window.scrollTo({ top: 0, behavior: 'smooth' });
}

async function deleteBuku(id) {
    if (!confirm('Apakah kamu yakin ingin menghapus buku ini?')) {
        return;
    }

    try {
        const response = await fetch(`${API_URL}/${id}`, {
            method: 'DELETE',
            headers: { 'Accept': 'application/json' }
        });

        const result = await response.json();

        if (!response.ok) {
            showAlert(result.message || 'Gagal menghapus buku.', 'danger');
            return;
        }

        showAlert('Buku berhasil dihapus dari sistem!', 'success');
        loadBukuData();
    } catch (error) {
        showAlert('Gagal menghapus data: ' + error.message, 'danger');
    }
}

function resetForm() {
    document.getElementById('form-title').innerText = 'Tambah Buku Baru';
    document.getElementById('form-buku').reset();
    document.getElementById('buku_id').value = '';

    const btnSubmit = document.getElementById('btn-submit');
    btnSubmit.innerText = 'Simpan Buku';
    btnSubmit.className = 'w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm py-2.5 px-4 rounded-xl shadow-sm hover:shadow transition active:scale-[0.98]';

    const btnCancel = document.getElementById('btn-cancel');
    btnCancel.classList.add('hidden');
}

function showAlert(message, type) {
    const alertBox = document.getElementById('alert-box');
    alertBox.classList.remove('hidden', 'bg-emerald-50', 'text-emerald-800', 'border-emerald-200', 'bg-rose-50', 'text-rose-800', 'border-rose-200');

    if (type === 'success') {
        alertBox.classList.add('bg-emerald-50', 'text-emerald-800', 'border', 'border-emerald-200/80');
    } else {
        alertBox.classList.add('bg-rose-50', 'text-rose-800', 'border', 'border-rose-200/80');
    }

    alertBox.innerHTML = message;

    setTimeout(() => {
        alertBox.classList.add('hidden');
    }, 5000);
}

function escapeHtml(text) {
    if (!text) return '';
    return String(text).replace(/'/g, "\\'").replace(/"/g, "&quot;");
}