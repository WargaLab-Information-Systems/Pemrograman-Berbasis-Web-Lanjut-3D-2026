const API = '/api/buku';

function tampilkanPesan(pesan) {
    document.getElementById('alert-area').textContent = pesan;
}

function bersihkanForm() {
    document.getElementById('form-buku').reset();
    document.getElementById('buku_id').value = '';
    document.getElementById('tombol-simpan').textContent = 'Simpan Buku';
    document.getElementById('tombol-batal').hidden = true;
}

async function apiRequest(url, pilihan = {}) {
    let response;

    try {
        response = await fetch(url, {
            ...pilihan,
            headers: {
                Accept: 'application/json',
                ...(pilihan.body
                    ? { 'Content-Type': 'application/json' }
                    : {})
            }
        });
    } catch (err) {
        throw new Error('Tidak dapat terhubung ke server.');
    }

    const data = await response.json().catch(() => null);

    if (!response.ok) {
        if (response.status === 422 && data?.errors) {
            const pesanValidasi = Object.values(data.errors)
                .flat()
                .join(', ');

            throw new Error(pesanValidasi);
        }

        throw new Error(
            data?.message || `Terjadi kesalahan (${response.status}).`
        );
    }

    return data;
}

async function muatBuku() {
    const daftar = document.getElementById('daftar-buku');

    try {
        const hasil = await apiRequest(API);
        daftar.innerHTML = '';

        hasil.data.forEach(function (buku) {
            const baris = document.createElement('tr');

            [
                buku.kategori ?? '-',
                buku.judul,
                buku.penulis,
                buku.tahun_terbit
            ].forEach(function (nilai) {
                const kolom = document.createElement('td');
                kolom.textContent = nilai ?? '-';
                baris.appendChild(kolom);
            });

            const kolomAksi = document.createElement('td');

            const tombolUbah = document.createElement('button');
            tombolUbah.type = 'button';
            tombolUbah.textContent = 'Ubah';
            tombolUbah.addEventListener('click', function () {
                bukaFormUbah(buku.id);
            });

            const tombolHapus = document.createElement('button');
            tombolHapus.type = 'button';
            tombolHapus.textContent = 'Hapus';
            tombolHapus.addEventListener('click', function () {
                hapusBuku(buku.id);
            });

            kolomAksi.append(tombolUbah, tombolHapus);
            baris.appendChild(kolomAksi);
            daftar.appendChild(baris);
        });

        if (hasil.data.length === 0) {
            daftar.innerHTML =
                '<tr><td colspan="5">Belum ada data buku.</td></tr>';
        }

        tampilkanPesan('');
    } catch (err) {
        tampilkanPesan('Gagal memuat data: ' + err.message);
        daftar.innerHTML =
            '<tr><td colspan="5">Data buku gagal dimuat.</td></tr>';
    }
}

async function simpanBuku(event) {
    event.preventDefault();

    const id = document.getElementById('buku_id').value;

    const dataBuku = {
        kategori_id: Number(document.getElementById('kategori_id').value),
        judul: document.getElementById('judul').value,
        penulis: document.getElementById('penulis').value,
        tahun_terbit: Number(
            document.getElementById('tahun_terbit').value
        )
    };

    const url = id ? `${API}/${id}` : API;
    const method = id ? 'PUT' : 'POST';

    try {
        const hasil = await apiRequest(url, {
            method: method,
            body: JSON.stringify(dataBuku)
        });

        bersihkanForm();
        await muatBuku();

        tampilkanPesan(
            hasil.message ||
            (id ? 'Buku berhasil diubah.' : 'Buku berhasil ditambahkan.')
        );
    } catch (err) {
        tampilkanPesan('Gagal menyimpan buku: ' + err.message);
    }
}

async function bukaFormUbah(id) {
    try {
        const hasil = await apiRequest(`${API}/${id}`);
        const buku = hasil.data;

        document.getElementById('buku_id').value = buku.id;
        document.getElementById('kategori_id').value = buku.kategori_id;
        document.getElementById('judul').value = buku.judul;
        document.getElementById('penulis').value = buku.penulis;
        document.getElementById('tahun_terbit').value = buku.tahun_terbit;

        document.getElementById('tombol-simpan').textContent =
            'Simpan Perubahan';
        document.getElementById('tombol-batal').hidden = false;

        tampilkanPesan('Silakan ubah data buku.');
    } catch (err) {
        tampilkanPesan('Gagal mengambil data buku: ' + err.message);
    }
}

async function hapusBuku(id) {
    const yakin = confirm('Apakah kamu yakin ingin menghapus buku ini?');

    if (!yakin) {
        return;
    }

    try {
        const hasil = await apiRequest(`${API}/${id}`, {
            method: 'DELETE'
        });

        await muatBuku();
        tampilkanPesan(hasil.message || 'Buku berhasil dihapus.');
    } catch (err) {
        tampilkanPesan('Gagal menghapus buku: ' + err.message);
    }
}

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('form-buku')
        .addEventListener('submit', simpanBuku);

    document.getElementById('tombol-batal')
        .addEventListener('click', function () {
            bersihkanForm();
            tampilkanPesan('');
        });

    muatBuku();
});
