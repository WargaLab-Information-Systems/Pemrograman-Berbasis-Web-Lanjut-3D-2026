@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')
<div class="page-header">
    <h2>Daftar Buku</h2>
    <p>Kelola koleksi buku perpustakaan.</p>
</div>

<div id="pesan"></div>

<form id="form-buku">
    <input type="hidden" id="buku-id">

    <div>
        <label for="kategori_id">Kategori</label>
        <select id="kategori_id" required>
            <option value="">Pilih kategori</option>
            @foreach ($kategori as $item)
                <option value="{{ $item->id }}">
                    {{ $item->nama }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="judul">Judul Buku</label>
        <input type="text" id="judul" required>
    </div>

    <div>
        <label for="penulis">Penulis</label>
        <input type="text" id="penulis" required>
    </div>

    <div>
        <label for="tahun_terbit">Tahun Terbit</label>
        <input type="number" id="tahun_terbit"
               min="1901" max="2155" required>
    </div>

    <div>
        <button type="submit" id="tombol-simpan">
            Tambah Buku
        </button>
        <button type="button" id="tombol-batal" hidden>
            Batal Ubah
        </button>
    </div>
</form>

<div class="table-responsive">
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Kategori</th>
                <th>Judul</th>
                <th>Penulis</th>
                <th>Tahun Terbit</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="tabel-buku">
            <tr><td colspan="6">Memuat data buku...</td></tr>
        </tbody>
    </table>
</div>

<script>
const form = document.getElementById('form-buku');
const tabel = document.getElementById('tabel-buku');
const pesan = document.getElementById('pesan');
const inputId = document.getElementById('buku-id');
const tombolSimpan = document.getElementById('tombol-simpan');
const tombolBatal = document.getElementById('tombol-batal');

function tampilkanPesan(teks, error = false) {
    pesan.textContent = teks;
    pesan.style.color = error ? '#a6372b' : '#267348';
    pesan.style.background = error ? '#fbe7e5' : '#e4f5e9';
}

async function tampilkanBuku() {
    try {
        const response = await fetch('/api/buku', {
            headers: { 'Accept': 'application/json' }
        });

        if (!response.ok) {
            throw new Error('Gagal mengambil data buku.');
        }

        const hasil = await response.json();
        tabel.innerHTML = '';

        if (!hasil.data || hasil.data.length === 0) {
            tabel.innerHTML =
                '<tr><td colspan="6">Belum ada data buku.</td></tr>';
            return;
        }

        hasil.data.forEach((item, index) => {
            const baris = document.createElement('tr');

            [
                index + 1,
                item.kategori ?? '-',
                item.judul ?? '-',
                item.penulis ?? '-',
                item.tahun_terbit ?? '-'
            ].forEach(nilai => {
                const td = document.createElement('td');
                td.textContent = nilai;
                baris.appendChild(td);
            });

            const aksi = document.createElement('td');

            const detail = document.createElement('button');
            detail.type = 'button';
            detail.textContent = 'Lihat Detail';
            detail.onclick = () => lihatDetail(item);

            const ubah = document.createElement('button');
            ubah.type = 'button';
            ubah.textContent = 'Ubah';
            ubah.onclick = () => isiForm(item);

            const hapus = document.createElement('button');
            hapus.type = 'button';
            hapus.textContent = 'Hapus';
            hapus.onclick = () => hapusBuku(item.id);

            aksi.append(detail,ubah, hapus);
            baris.appendChild(aksi);
            tabel.appendChild(baris);
        });
    } catch (error) {
        tabel.innerHTML =
            '<tr><td colspan="6">Data gagal dimuat.</td></tr>';
        tampilkanPesan(error.message, true);
    }
}

function lihatDetail(item) {
    window.location.href = `/buku/${item.id}`;
}

function isiForm(item) {
    inputId.value = item.id;
    document.getElementById('kategori_id').value = item.kategori_id;
    document.getElementById('judul').value = item.judul;
    document.getElementById('penulis').value = item.penulis;
    document.getElementById('tahun_terbit').value = item.tahun_terbit;

    tombolSimpan.textContent = 'Simpan Perubahan';
    tombolBatal.hidden = false;
    tampilkanPesan('Mode ubah buku.');
    form.scrollIntoView({ behavior: 'smooth' });
}

function resetForm() {
    form.reset();
    inputId.value = '';
    tombolSimpan.textContent = 'Tambah Buku';
    tombolBatal.hidden = true;
}

tombolBatal.addEventListener('click', () => {
    resetForm();
    tampilkanPesan('Perubahan dibatalkan.');
});

form.addEventListener('submit', async event => {
    event.preventDefault();

    const id = inputId.value;
    const data = {
        kategori_id: document.getElementById('kategori_id').value,
        judul: document.getElementById('judul').value,
        penulis: document.getElementById('penulis').value,
        tahun_terbit: document.getElementById('tahun_terbit').value
    };

    try {
        const response = await fetch(
            id ? `/api/buku/${id}` : '/api/buku',
            {
                method: id ? 'PUT' : 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            }
        );

        const hasil = await response.json();

        if (!response.ok) {
            const detail = hasil.errors
                ? Object.values(hasil.errors).flat().join(' ')
                : hasil.message;

            throw new Error(detail || 'Gagal menyimpan buku.');
        }

        resetForm();
        tampilkanPesan(hasil.message || 'Data berhasil disimpan.');
        await tampilkanBuku();
    } catch (error) {
        tampilkanPesan(error.message, true);
    }
});

async function hapusBuku(id) {
    if (!confirm('Yakin ingin menghapus buku ini?')) return;

    try {
        const response = await fetch(`/api/buku/${id}`, {
            method: 'DELETE',
            headers: { 'Accept': 'application/json' }
        });

        const hasil = await response.json();

        if (!response.ok) {
            throw new Error(hasil.message || 'Gagal menghapus buku.');
        }

        tampilkanPesan(hasil.message || 'Buku berhasil dihapus.');
        await tampilkanBuku();
    } catch (error) {
        tampilkanPesan(error.message, true);
    }
}

tampilkanBuku();
</script>
@endsection