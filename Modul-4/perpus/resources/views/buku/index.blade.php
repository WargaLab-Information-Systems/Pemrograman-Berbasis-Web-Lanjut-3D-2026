@extends('layouts.app')

@section('title', 'Daftar Buku - Perpustakaan Digital')

@section('content')

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Buku</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-100 min-h-screen text-slate-800">

<div class="max-w-7xl mx-auto p-6">

    <header class="mb-8">
        <p class="text-sm text-indigo-600 font-semibold">
            SISTEM INFORMASI PERPUSTAKAAN
        </p>
        <h1 class="text-3xl font-bold mt-2">Manajemen Buku</h1>
        <p class="text-slate-500 mt-2">
            Kelola data buku melalui RESTful API.
        </p>
    </header>

    <div id="pesan"
         role="status"
         aria-live="polite"
         class="hidden mb-5 p-4 rounded-lg">
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Form buku -->
        <section class="bg-white rounded-xl shadow-sm p-6">
            <h2 id="judul-form" class="text-xl font-bold mb-5">
                Tambah Buku
            </h2>

            <form id="form-buku" class="space-y-4">

                <input type="hidden" id="buku-id">

                <div>
                    <label for="kategori_id"
                           class="block text-sm font-medium mb-2">
                        Kategori
                    </label>

                    <select id="kategori_id"
                            required
                            class="w-full border rounded-lg p-3">
                        <option value="">Memuat kategori...</option>
                    </select>
                </div>

                <div>
                    <label for="judul"
                           class="block text-sm font-medium mb-2">
                        Judul Buku
                    </label>

                    <input type="text"
                           id="judul"
                           required
                           maxlength="255"
                           class="w-full border rounded-lg p-3"
                           placeholder="Masukkan judul buku">
                </div>

                <div>
                    <label for="penulis"
                           class="block text-sm font-medium mb-2">
                        Penulis
                    </label>

                    <input type="text"
                           id="penulis"
                           required
                           maxlength="255"
                           class="w-full border rounded-lg p-3"
                           placeholder="Masukkan nama penulis">
                </div>

                <div>
                    <label for="tahun_terbit"
                           class="block text-sm font-medium mb-2">
                        Tahun Terbit
                    </label>

                    <input type="number"
                           id="tahun_terbit"
                           required
                           min="1000"
                           max="2100"
                           class="w-full border rounded-lg p-3"
                           placeholder="Contoh: 2025">
                </div>

                <button type="submit"
                        id="tombol-simpan"
                        class="w-full bg-indigo-600 text-white p-3
                               rounded-lg hover:bg-indigo-700">
                    Simpan Buku
                </button>

                <button type="button"
                        id="tombol-batal"
                        class="hidden w-full bg-slate-200 p-3
                               rounded-lg hover:bg-slate-300">
                    Batal Ubah
                </button>

            </form>
        </section>

        <!-- Tabel buku -->
        <section class="bg-white rounded-xl shadow-sm p-6
                        lg:col-span-2">

            <div class="flex flex-wrap justify-between
                        items-center gap-3 mb-5">
                <h2 class="text-xl font-bold">Daftar Buku</h2>

                <button id="tombol-muat"
                        class="bg-slate-800 text-white px-4 py-2
                               rounded-lg hover:bg-slate-700">
                    Muat Ulang Data
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-100">
                        <tr>
                            <th class="p-3">No.</th>
                            <th class="p-3">Kategori</th>
                            <th class="p-3">Judul</th>
                            <th class="p-3">Penulis</th>
                            <th class="p-3">Tahun</th>
                            <th class="p-3">Aksi</th>
                        </tr>
                    </thead>

                    <tbody id="tabel-buku">
                        <tr>
                            <td colspan="6"
                                class="p-5 text-center text-slate-500">
                                Memuat data buku...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </section>
    </div>
</div>

<script>
const API_BUKU = '/api/buku';
const API_KATEGORI = '/api/kategori';

const form = document.getElementById('form-buku');
const tabel = document.getElementById('tabel-buku');
const pesan = document.getElementById('pesan');

let daftarBuku = [];
let daftarKategori = [];

// Menampilkan pesan
function tampilkanPesan(teks, berhasil = true) {
    pesan.textContent = teks;

    pesan.className = berhasil
        ? 'mb-5 p-4 rounded-lg bg-green-100 text-green-800'
        : 'mb-5 p-4 rounded-lg bg-red-100 text-red-800';
}

// Mengubah respons API menjadi pesan yang mudah dipahami
async function ambilRespons(response) {
    const teks = await response.text();
    let data = {};

    if (teks) {
        try {
            data = JSON.parse(teks);
        } catch {
            throw new Error(
                'Respons server bukan JSON yang valid.'
            );
        }
    }

    if (!response.ok) {
        const kesalahan = data.errors
            ? Object.values(data.errors).flat().join(' ')
            : data.message;

        throw new Error(
            kesalahan || `Permintaan gagal (${response.status}).`
        );
    }

    return data;
}

// Mengambil data kategori
async function muatKategori() {
    const select = document.getElementById('kategori_id');

    try {
        const response = await fetch(API_KATEGORI, {
            headers: {
                'Accept': 'application/json'
            }
        });

        const hasil = await ambilRespons(response);

        daftarKategori = Array.isArray(hasil)
            ? hasil
            : (hasil.data || []);

        select.replaceChildren();

        const pilihanAwal = document.createElement('option');
        pilihanAwal.value = '';
        pilihanAwal.textContent = 'Pilih kategori';
        select.appendChild(pilihanAwal);

        daftarKategori.forEach(kategori => {
            const pilihan = document.createElement('option');

            pilihan.value = kategori.id;
            pilihan.textContent = kategori.nama_kategori;

            select.appendChild(pilihan);
        });

    } catch (error) {
        select.replaceChildren();

        const pilihan = document.createElement('option');
        pilihan.value = '';
        pilihan.textContent = 'Kategori gagal dimuat';

        select.appendChild(pilihan);

        tampilkanPesan(error.message, false);
    }
}

// Mengambil data buku melalui GET /api/buku
async function muatBuku() {
    tabel.innerHTML = `
        <tr>
            <td colspan="6" class="p-5 text-center">
                Memuat data buku...
            </td>
        </tr>
    `;

    try {
        const response = await fetch(API_BUKU, {
            headers: {
                'Accept': 'application/json'
            }
        });

        const hasil = await ambilRespons(response);

        daftarBuku = Array.isArray(hasil)
            ? hasil
            : (hasil.data || []);

        renderTabel();

    } catch (error) {
        tabel.innerHTML = `
            <tr>
                <td colspan="6"
                    class="p-5 text-center text-red-600">
                    Gagal mengambil data buku.
                </td>
            </tr>
        `;

        tampilkanPesan(error.message, false);
    }
}

// Menampilkan data pada tabel
function renderTabel() {
    tabel.replaceChildren();

    if (daftarBuku.length === 0) {
        const baris = document.createElement('tr');
        const kolom = document.createElement('td');

        kolom.colSpan = 6;
        kolom.className = 'p-5 text-center text-slate-500';
        kolom.textContent = 'Belum ada data buku.';

        baris.appendChild(kolom);
        tabel.appendChild(baris);
        return;
    }

    daftarBuku.forEach((buku, index) => {
        const baris = document.createElement('tr');
        baris.className = 'border-b hover:bg-slate-50';

        [
            index + 1,
            buku.kategori ?? '-',
            buku.judul ?? '-',
            buku.penulis ?? '-',
            buku.tahun_terbit ?? '-'
        ].forEach(nilai => {
            const kolom = document.createElement('td');

            kolom.className = 'p-3';
            kolom.textContent = nilai;

            baris.appendChild(kolom);
        });

        const aksi = document.createElement('td');
        aksi.className = 'p-3 whitespace-nowrap';

        const tombolUbah = document.createElement('button');
        tombolUbah.textContent = 'Ubah';
        tombolUbah.className =
            'bg-amber-500 text-white px-3 py-1 rounded mr-2';

        tombolUbah.addEventListener('click', () => {
            pilihBuku(buku.id);
        });

        const tombolHapus = document.createElement('button');
        tombolHapus.textContent = 'Hapus';
        tombolHapus.className =
            'bg-red-600 text-white px-3 py-1 rounded';

        tombolHapus.addEventListener('click', () => {
            hapusBuku(buku.id);
        });

        aksi.append(tombolUbah, tombolHapus);
        baris.appendChild(aksi);
        tabel.appendChild(baris);
    });
}

// Mengosongkan formulir
function resetForm() {
    form.reset();

    document.getElementById('buku-id').value = '';
    document.getElementById('judul-form').textContent =
        'Tambah Buku';

    document.getElementById('tombol-simpan').textContent =
        'Simpan Buku';

    document.getElementById('tombol-batal')
        .classList.add('hidden');
}

// Memilih buku untuk diubah
function pilihBuku(id) {
    const buku = daftarBuku.find(
        item => String(item.id) === String(id)
    );

    if (!buku) {
        tampilkanPesan('Data buku tidak ditemukan.', false);
        return;
    }

    document.getElementById('buku-id').value = buku.id;
    document.getElementById('kategori_id').value =
        buku.kategori_id ?? '';

    document.getElementById('judul').value = buku.judul ?? '';
    document.getElementById('penulis').value =
        buku.penulis ?? '';

    document.getElementById('tahun_terbit').value =
        buku.tahun_terbit ?? '';

    document.getElementById('judul-form').textContent =
        'Ubah Buku';

    document.getElementById('tombol-simpan').textContent =
        'Simpan Perubahan';

    document.getElementById('tombol-batal')
        .classList.remove('hidden');

    document.getElementById('judul-form')
        .scrollIntoView({ behavior: 'smooth' });
}

// Menambahkan atau mengubah buku
form.addEventListener('submit', async function(event) {
    event.preventDefault();

    const id = document.getElementById('buku-id').value;

    const dataBuku = {
        kategori_id: document.getElementById('kategori_id').value,
        judul: document.getElementById('judul').value.trim(),
        penulis: document.getElementById('penulis').value.trim(),
        tahun_terbit: Number(
            document.getElementById('tahun_terbit').value
        )
    };

    const tombol = document.getElementById('tombol-simpan');
    tombol.disabled = true;

    try {
        const response = await fetch(
            id ? `${API_BUKU}/${id}` : API_BUKU,
            {
                method: id ? 'PUT' : 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(dataBuku)
            }
        );

        await ambilRespons(response);

        resetForm();
        await muatBuku();

        tampilkanPesan(
            id
                ? 'Data buku berhasil diubah.'
                : 'Buku berhasil ditambahkan.'
        );

    } catch (error) {
        tampilkanPesan(error.message, false);

    } finally {
        tombol.disabled = false;
    }
});

// Menghapus buku
async function hapusBuku(id) {
    const setuju = confirm(
        'Apakah kamu yakin ingin menghapus buku ini?'
    );

    if (!setuju) {
        return;
    }

    try {
        const response = await fetch(`${API_BUKU}/${id}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json'
            }
        });

        await ambilRespons(response);

        await muatBuku();
        tampilkanPesan('Buku berhasil dihapus.');

    } catch (error) {
        tampilkanPesan(error.message, false);
    }
}

document.getElementById('tombol-batal')
    .addEventListener('click', resetForm);

document.getElementById('tombol-muat')
    .addEventListener('click', muatBuku);

async function mulai() {
    await muatKategori();
    await muatBuku();
}

mulai();
</script>

</body>
</html>

@endsection