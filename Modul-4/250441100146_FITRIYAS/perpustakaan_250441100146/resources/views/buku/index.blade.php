
@extends('layouts.app')

@section('title', 'Daftar Buku - Perpustakaan')

@section('content')
<div class="page-header">
    <p class="hero-label">KOLEKSI PERPUSTAKAAN</p>
    <h1>Daftar Buku</h1>
    <p>Kelola koleksi buku perpustakaan.</p>
</div>

<div style="background:white; padding:24px; border-radius:16px; margin-bottom:24px;">
    <h2 id="formTitle">Tambah Buku</h2>

    <div id="pesan" role="alert" style="margin-bottom:16px;"></div>

    <form id="formBuku">
        <input type="hidden" id="buku_id">

        <div style="margin-bottom:12px;">
            <label for="kategori_id">Kategori (ID kategori)</label>
            <input type="number" id="kategori_id" min="1" required
                style="display:block;width:100%;padding:10px;margin-top:6px;box-sizing:border-box;">
            <small>Masukkan ID kategori yang sudah tersedia di database.</small>
        </div>

        <div style="margin-bottom:12px;">
            <label for="judul">Judul Buku</label>
            <input type="text" id="judul" minlength="3" maxlength="150" required
                style="display:block;width:100%;padding:10px;margin-top:6px;box-sizing:border-box;">
        </div>

        <div style="margin-bottom:12px;">
            <label for="penulis">Penulis</label>
            <input type="text" id="penulis" minlength="3" maxlength="100" required
                style="display:block;width:100%;padding:10px;margin-top:6px;box-sizing:border-box;">
        </div>

        <div style="margin-bottom:16px;">
            <label for="tahun">Tahun Terbit</label>
            <input type="number" id="tahun" min="1900"
                max="{{ date('Y') }}" required
                style="display:block;width:100%;padding:10px;margin-top:6px;box-sizing:border-box;">
        </div>

        <button type="submit" id="tombolSimpan">Simpan Buku</button>
        <button type="button" id="tombolBatal" hidden>Batal Ubah</button>
    </form>
</div>

<div style="background:white;padding:24px;border-radius:16px;">
    <h2>Data Buku</h2>

    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr>
                    <th style="padding:12px;text-align:left;">Kategori</th>
                    <th style="padding:12px;text-align:left;">Judul</th>
                    <th style="padding:12px;text-align:left;">Penulis</th>
                    <th style="padding:12px;text-align:left;">Tahun Terbit</th>
                    <th style="padding:12px;text-align:left;">Aksi</th>
                </tr>
            </thead>
            <tbody id="daftarBuku">
                <tr>
                    <td colspan="5" style="padding:12px;">Memuat data buku...</td>
                </tr>
            </tbody>
        </table>
    </div>

    <p id="infoHalaman"></p>
    <button type="button" id="tombolSebelumnya">Sebelumnya</button>
    <button type="button" id="tombolBerikutnya">Berikutnya</button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const apiUrl = '/api/buku';

    const form = document.getElementById('formBuku');
    const daftarBuku = document.getElementById('daftarBuku');
    const pesan = document.getElementById('pesan');

    let halaman = 1;
    let halamanTerakhir = 1;
    let dataBuku = [];

    function tampilkanPesan(teks, berhasil = false) {
        pesan.textContent = teks;
        pesan.style.color = berhasil ? 'green' : 'red';
    }

    async function bacaRespons(response) {
        const hasil = await response.json();

        if (!response.ok) {
            const detail = hasil.errors
                ? Object.values(hasil.errors).flat().join(' ')
                : hasil.message;

            throw new Error(detail || 'Permintaan gagal.');
        }

        return hasil;
    }

    async function muatBuku() {
        daftarBuku.innerHTML =
            '<tr><td colspan="5" style="padding:12px;">Memuat data...</td></tr>';

        try {
            const response = await fetch(`${apiUrl}?page=${halaman}`, {
                headers: { 'Accept': 'application/json' }
            });

            const hasil = await bacaRespons(response);

            dataBuku = hasil.data || [];
            halamanTerakhir = hasil.meta?.last_page || 1;

            daftarBuku.innerHTML = '';

            if (dataBuku.length === 0) {
                daftarBuku.innerHTML =
                    '<tr><td colspan="5" style="padding:12px;">Belum ada data buku.</td></tr>';
            }

            dataBuku.forEach(function (buku) {
                const baris = document.createElement('tr');

                const kategori = buku.kategori;
                const namaKategori = typeof kategori === 'object'
                    ? (kategori?.nama || '-')
                    : (kategori || '-');

                [
                    namaKategori,
                    buku.judul,
                    buku.penulis,
                    buku.tahun
                ].forEach(function (nilai) {
                    const sel = document.createElement('td');
                    sel.style.padding = '12px';
                    sel.style.borderTop = '1px solid #ddd';
                    sel.textContent = nilai ?? '-';
                    baris.appendChild(sel);
                });

                const aksi = document.createElement('td');
                aksi.style.padding = '12px';
                aksi.style.borderTop = '1px solid #ddd';

                const tombolUbah = document.createElement('button');
                tombolUbah.textContent = 'Ubah';
                tombolUbah.type = 'button';
                tombolUbah.addEventListener('click', function () {
                    ubahBuku(buku.id);
                });

                const tombolHapus = document.createElement('button');
                tombolHapus.textContent = 'Hapus';
                tombolHapus.type = 'button';
                tombolHapus.style.marginLeft = '6px';
                tombolHapus.addEventListener('click', function () {
                    hapusBuku(buku.id);
                });

                aksi.append(tombolUbah, tombolHapus);
                baris.appendChild(aksi);
                daftarBuku.appendChild(baris);
            });

            document.getElementById('infoHalaman').textContent =
                `Halaman ${halaman} dari ${halamanTerakhir}`;

            document.getElementById('tombolSebelumnya').disabled =
                halaman <= 1;

            document.getElementById('tombolBerikutnya').disabled =
                halaman >= halamanTerakhir;

        } catch (error) {
            daftarBuku.innerHTML =
                '<tr><td colspan="5" style="padding:12px;">Data gagal dimuat.</td></tr>';

            tampilkanPesan('Gagal mengambil data buku: ' + error.message);
        }
    }

    form.addEventListener('submit', async function (event) {
        event.preventDefault();

        const id = document.getElementById('buku_id').value;

        const data = {
            kategori_id: Number(document.getElementById('kategori_id').value),
            judul: document.getElementById('judul').value.trim(),
            penulis: document.getElementById('penulis').value.trim(),
            tahun_terbit: Number(document.getElementById('tahun').value)
        };

        try {
            const response = await fetch(
                id ? `${apiUrl}/${id}` : apiUrl,
                {
                    method: id ? 'PUT' : 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                }
            );

            const hasil = await bacaRespons(response);

            tampilkanPesan(
                hasil.message || 'Data buku berhasil disimpan.',
                true
            );

            form.reset();
            document.getElementById('buku_id').value = '';
            document.getElementById('formTitle').textContent = 'Tambah Buku';
            document.getElementById('tombolSimpan').textContent = 'Simpan Buku';
            document.getElementById('tombolBatal').hidden = true;

            halaman = 1;
            await muatBuku();

        } catch (error) {
            tampilkanPesan('Gagal menyimpan buku: ' + error.message);
        }
    });

    function ubahBuku(id) {
        const buku = dataBuku.find(function (item) {
            return String(item.id) === String(id);
        });

        if (!buku) {
            tampilkanPesan('Data buku tidak ditemukan.');
            return;
        }

        document.getElementById('buku_id').value = buku.id;
        document.getElementById('kategori_id').value = buku.kategori_id ?? '';
        document.getElementById('judul').value = buku.judul ?? '';
        document.getElementById('penulis').value = buku.penulis ?? '';
        document.getElementById('tahun').value =
            buku.tahun_terbit ?? buku.tahun ?? '';

        document.getElementById('formTitle').textContent = 'Ubah Buku';
        document.getElementById('tombolSimpan').textContent = 'Simpan Perubahan';
        document.getElementById('tombolBatal').hidden = false;

        tampilkanPesan('Silakan ubah data buku yang dipilih.', true);
        form.scrollIntoView({ behavior: 'smooth' });
    }

    async function hapusBuku(id) {
        const yakin = confirm('Apakah kamu yakin ingin menghapus buku ini?');

        if (!yakin) {
            return;
        }

        try {
            const response = await fetch(`${apiUrl}/${id}`, {
                method: 'DELETE',
                headers: { 'Accept': 'application/json' }
            });

            const hasil = await bacaRespons(response);

            tampilkanPesan(
                hasil.message || 'Buku berhasil dihapus.',
                true
            );

            await muatBuku();

            if (dataBuku.length === 0 && halaman > 1) {
                halaman--;
                await muatBuku();
            }

        } catch (error) {
            tampilkanPesan('Gagal menghapus buku: ' + error.message);
        }
    }

    document.getElementById('tombolBatal').addEventListener('click', function () {
        form.reset();
        document.getElementById('buku_id').value = '';
        document.getElementById('formTitle').textContent = 'Tambah Buku';
        document.getElementById('tombolSimpan').textContent = 'Simpan Buku';
        this.hidden = true;
        tampilkanPesan('');
    });

    document.getElementById('tombolSebelumnya').addEventListener('click', async function () {
        if (halaman > 1) {
            halaman--;
            await muatBuku();
        }
    });

    document.getElementById('tombolBerikutnya').addEventListener('click', async function () {
        if (halaman < halamanTerakhir) {
            halaman++;
            await muatBuku();
        }
    });

    muatBuku();
});
</script>
@endsection
