
const API = '/api/buku';
const API_KATEGORI = '/api/rekap/buku-per-kategori';

const form = document.getElementById('formBuku');
const tabel = document.getElementById('daftarBuku');
const kategori = document.getElementById('kategori_id');
const pesan = document.getElementById('pesan');
const tombol = document.getElementById('tombolSimpan');
const batal = document.getElementById('tombolBatal');

let bukuList = [];

function notifikasi(teks, error = false) {
    if (!pesan) return;

    pesan.textContent = teks;
    pesan.className = error ? 'error' : 'success';
}

async function hasil(response) {
    const data = await response.json();

    if (!response.ok) {
        throw new Error(
            data.errors
                ? Object.values(data.errors).flat().join(' ')
                : data.message || 'Permintaan gagal'
        );
    }

    return data;
}

async function muatKategori() {
    try {
        const data = await hasil(await fetch(API_KATEGORI));

        kategori.replaceChildren(
            new Option('Pilih kategori', '')
        );

        (data.data || []).forEach(item => {
            kategori.add(new Option(item.nama, item.id));
        });
    } catch (e) {
        notifikasi('Gagal memuat kategori: ' + e.message, true);
    }
}

async function muatBuku() {
    try {
        const data = await hasil(await fetch(API));

        bukuList = Array.isArray(data.data)
            ? data.data
            : data.data?.data || [];

        renderTabel();
    } catch (e) {
        tabel.innerHTML =
            '<tr><td colspan="5">Gagal memuat data.</td></tr>';

        notifikasi('Gagal memuat buku: ' + e.message, true);
    }
}

function sel(teks) {
    const td = document.createElement('td');
    td.textContent = teks ?? '-';
    return td;
}

function renderTabel() {
    tabel.replaceChildren();

    if (!bukuList.length) {
        tabel.innerHTML =
            '<tr><td colspan="5">Belum ada buku.</td></tr>';
        return;
    }

    bukuList.forEach(buku => {
        const tr = document.createElement('tr');

        tr.append(
            sel(buku.kategori),
            sel(buku.judul),
            sel(buku.penulis),
            sel(buku.tahun)
        );

        const aksi = sel('');

        const ubah = document.createElement('button');
        ubah.textContent = 'Ubah';
        ubah.className = 'btn-edit';
        ubah.type = 'button';
        ubah.onclick = () => isiForm(buku);

        const hapus = document.createElement('button');
        hapus.textContent = 'Hapus';
        hapus.className = 'btn-delete';
        hapus.type = 'button';
        hapus.onclick = () => hapusBuku(buku);

        aksi.append(
            ubah,
            document.createTextNode(' '),
            hapus
        );

        tr.append(aksi);
        tabel.append(tr);
    });
}

function isiForm(buku) {
    document.getElementById('buku_id').value = buku.id;
    document.getElementById('judul').value = buku.judul;
    document.getElementById('penulis').value = buku.penulis;
    document.getElementById('tahun').value = buku.tahun;

    const option = [...kategori.options].find(
        item => item.textContent === buku.kategori
    );

    kategori.value = option?.value || '';

    document.getElementById('judulForm').textContent = 'Ubah Buku';
    tombol.textContent = 'Simpan Perubahan';
    batal.hidden = false;
}

function resetForm() {
    form.reset();

    document.getElementById('buku_id').value = '';
    document.getElementById('judulForm').textContent = 'Tambah Buku';

    tombol.textContent = 'Tambah Buku';
    batal.hidden = true;
}

batal.onclick = () => {
    resetForm();
    notifikasi('Perubahan dibatalkan.');
};

form.onsubmit = async event => {
    event.preventDefault();

    const id = document.getElementById('buku_id').value;

    const payload = {
        kategori_id: kategori.value,
        judul: document.getElementById('judul').value.trim(),
        penulis: document.getElementById('penulis').value.trim(),
        tahun_terbit: Number(
            document.getElementById('tahun').value
        )
    };

    try {
        tombol.disabled = true;

        await hasil(await fetch(id ? `${API}/${id}` : API, {
            method: id ? 'PUT' : 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        }));

        resetForm();
        await muatBuku();

        notifikasi(
            id
                ? 'Buku berhasil diperbarui.'
                : 'Buku berhasil ditambahkan.'
        );
    } catch (e) {
        notifikasi(e.message, true);
    } finally {
        tombol.disabled = false;
    }
};

async function hapusBuku(buku) {
    if (!confirm(`Hapus buku "${buku.judul}"?`)) return;

    try {
        await hasil(await fetch(`${API}/${buku.id}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json'
            }
        }));

        if (
            document.getElementById('buku_id').value == buku.id
        ) {
            resetForm();
        }

        await muatBuku();
        notifikasi('Buku berhasil dihapus.');
    } catch (e) {
        notifikasi(e.message, true);
    }
}

async function mulai() {
    if (!form || !tabel || !kategori || !pesan || !tombol || !batal) {
        console.error('Elemen HTML buku tidak ditemukan.');
        return;
    }

    await muatKategori();
    await muatBuku();
}

mulai();
