@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')

<style>
    .modul4-container {
        width: 100%;
        max-width: 1000px;
        margin: 0 auto;
    }

    .modul4-title {
        text-align: center;
        margin-bottom: 28px;
    }

    .modul4-title h2 {
        color: #800000;
        font-size: 28px;
        margin-bottom: 8px;
    }

    .modul4-title p {
        color: #777;
        font-size: 14px;
    }

    .modul4-form-box {
        width: 100%;
        max-width: 600px;
        margin: 0 auto 30px;
        padding: 24px;
        background: white;
        border: 1px solid #e5dada;
        border-radius: 10px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
    }

    .modul4-form-box h3 {
        color: #800000;
        font-size: 20px;
        margin-bottom: 20px;
    }

    .modul4-field {
        margin-bottom: 16px;
    }

    .modul4-field label {
        display: block;
        font-size: 14px;
        font-weight: bold;
        margin-bottom: 7px;
        color: #444;
    }

    .modul4-field input,
    .modul4-field select {
        display: block;
        width: 100%;
        padding: 11px 12px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 6px;
        background: white;
        color: #333;
    }

    .modul4-actions,
    .modul4-table-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .modul4-table-actions {
        justify-content: center;
    }

    .modul4-btn {
        border: none;
        border-radius: 6px;
        padding: 11px 18px;
        font-size: 13px;
        font-weight: bold;
        cursor: pointer;
    }

    .modul4-btn-primary {
        background: #800000;
        color: white;
    }

    .modul4-btn-warning {
        background: #d97706;
        color: white;
    }

    .modul4-btn-danger {
        background: #dc2626;
        color: white;
    }

    .modul4-btn-secondary {
        background: #6b7280;
        color: white;
    }

    .modul4-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .modul4-table-wrapper {
        width: 100%;
        overflow-x: auto;
        background: white;
        border: 1px solid #ddd;
        border-radius: 8px;
    }

    .modul4-table {
        width: 100%;
        border-collapse: collapse;
        color: #333;
        font-size: 14px;
    }

    .modul4-table th {
        background: #e8eef8;
        padding: 13px 10px;
        border: 1px solid #ddd;
        text-align: center;
    }

    .modul4-table td {
        padding: 12px 10px;
        border: 1px solid #ddd;
        vertical-align: middle;
    }

    .modul4-table tbody tr:hover {
        background: #faf5f5;
    }

    .modul4-message {
        margin-top: 14px;
        font-size: 14px;
        line-height: 1.6;
    }

    .modul4-pagination {
        display: flex;
        justify-content: center;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 20px;
    }

    @media (max-width: 600px) {
        .modul4-form-box {
            padding: 18px;
        }

        .modul4-table {
            min-width: 650px;
            font-size: 12px;
        }
    }
</style>

<div class="modul4-container">

    <div class="modul4-title">
        <h2>Daftar Buku</h2>
        <p>Berikut adalah koleksi buku yang tersedia di perpustakaan.</p>
    </div>

    <!-- FORM TAMBAH DAN UBAH -->
    <div class="modul4-form-box">
        <h3 id="judul-form">Tambah Buku Baru</h3>

        <form id="form-buku">
            <input type="hidden" id="buku_id">

            <div class="modul4-field">
                <label for="kategori_id">Kategori Buku</label>
                <select id="kategori_id" required>
                    <option value="">-- Pilih Kategori --</option>
                </select>
            </div>

            <div class="modul4-field">
                <label for="judul">Judul Buku</label>
                <input type="text" id="judul"
                       minlength="3" maxlength="150" required>
            </div>

            <div class="modul4-field">
                <label for="penulis">Penulis</label>
                <input type="text" id="penulis"
                       minlength="3" maxlength="100" required>
            </div>

            <div class="modul4-field">
                <label for="tahun_terbit">Tahun Terbit</label>
                <input type="number" id="tahun_terbit"
                       min="1900" max="2026" required>
            </div>

            <div class="modul4-actions">
                <button type="submit"
                        id="tombol-simpan"
                        class="modul4-btn modul4-btn-primary">
                    Simpan Buku
                </button>

                <button type="button"
                        id="tombol-batal"
                        class="modul4-btn modul4-btn-secondary"
                        style="display:none;">
                    Batal Ubah
                </button>
            </div>
        </form>

        <p id="pesan-form"
           class="modul4-message"
           role="status"></p>
    </div>

    <!-- TABEL DAFTAR BUKU -->
    <div class="modul4-table-wrapper">
        <table class="modul4-table">
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
                <tr>
                    <td colspan="6" style="text-align:center;">
                        Memuat data buku...
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <p id="pesan-error"
       class="modul4-message"
       style="color:#b91c1c;"
       role="alert"></p>

    <div id="pagination-buku" class="modul4-pagination"></div>

</div>

<script>
    let halamanAktif = '/api/buku';
    let pilihanKategori = new Map();
    let kategoriSiap = false;

    // no 5: fungsi buat perikssa respon api 
    async function permintaanAPI(url, options = {}) {
        let response;

        try {
            response = await fetch(url, {
                ...options,
                headers: {
                    'Accept': 'application/json',
                    ...(options.headers || {})
                }
            });
        } catch (error) {
            throw new Error(
                'Tidak dapat terhubung ke API. ' +
                'Periksa koneksi atau server Laravel.'
            );
        }

        let hasil;

        try {
            hasil = await response.json();
        } catch (error) {
            if (!response.ok) {
                throw new Error(
                    'Permintaan gagal. Status HTTP: ' +
                    response.status
                );
            }

            throw new Error(
                'Respons API tidak memiliki format JSON yang benar.'
            );
        }

        // no 5: cek respinse oke
        if (!response.ok) {
            const pesanValidasi = hasil.errors
                ? Object.values(hasil.errors).flat().join(' ')
                : '';

            throw new Error(
                pesanValidasi ||
                hasil.message ||
                'Permintaan gagal. Status HTTP: ' +
                response.status
            );
        }

        return hasil;
    }

    function tampilkanPesan(idElemen, pesan, berhasil = false) {
        const elemen = document.getElementById(idElemen);

        elemen.textContent = pesan;
        elemen.style.color = berhasil ? '#166534' : '#b91c1c';
    }

    function buatBarisPesan(teks) {
        const baris = document.createElement('tr');
        const kolom = document.createElement('td');

        kolom.colSpan = 6;
        kolom.textContent = teks;
        kolom.style.textAlign = 'center';

        baris.appendChild(kolom);

        return baris;
    }

    // no 1 dan 5: ambil data dan tangani errornya
    async function ambilBuku(url = '/api/buku') {
        const tabel = document.getElementById('tabel-buku');
        const pagination = document.getElementById('pagination-buku');

        halamanAktif = url;
        tabel.replaceChildren(buatBarisPesan('Memuat data buku...'));
        pagination.replaceChildren();

        tampilkanPesan('pesan-error', '');

        try {
            const hasil = await permintaanAPI(url);
            const daftarBuku = hasil.data ?? [];

            tabel.replaceChildren();

            if (daftarBuku.length === 0) {
                tabel.appendChild(
                    buatBarisPesan('Belum ada buku yang tersedia.')
                );
                return true;
            }

            daftarBuku.forEach((buku, index) => {
                const baris = document.createElement('tr');

                const halaman = hasil.meta?.current_page ?? 1;
                const perHalaman = hasil.meta?.per_page ?? 10;
                const nomor = (halaman - 1) * perHalaman + index + 1;

                const nilai = [
                    nomor,
                    buku.kategori ?? '-',
                    buku.judul ?? '-',
                    buku.penulis ?? '-',
                    buku.tahun_terbit ?? '-'
                ];

                nilai.forEach((isi) => {
                    const kolom = document.createElement('td');
                    kolom.textContent = isi;
                    baris.appendChild(kolom);
                });

                const kolomAksi = document.createElement('td');
                const wadah = document.createElement('div');

                wadah.className = 'modul4-table-actions';

                // NO 3: ubah
                const tombolUbah = document.createElement('button');

                tombolUbah.type = 'button';
                tombolUbah.textContent = 'Ubah';
                tombolUbah.className =
                    'modul4-btn modul4-btn-warning';

                tombolUbah.addEventListener('click', () => {
                    pilihBukuUntukDiubah(buku);
                });

                // NO 4: hapus
                const tombolHapus = document.createElement('button');

                tombolHapus.type = 'button';
                tombolHapus.textContent = 'Hapus';
                tombolHapus.className =
                    'modul4-btn modul4-btn-danger';

                tombolHapus.addEventListener('click', () => {
                    hapusBuku(buku.id, buku.judul, tombolHapus);
                });

                wadah.appendChild(tombolUbah);
                wadah.appendChild(tombolHapus);

                kolomAksi.appendChild(wadah);
                baris.appendChild(kolomAksi);

                tabel.appendChild(baris);
            });

            // PAGINATION
            if (hasil.links?.prev) {
                const tombol = document.createElement('button');

                tombol.type = 'button';
                tombol.textContent = 'Sebelumnya';
                tombol.className =
                    'modul4-btn modul4-btn-primary';

                tombol.addEventListener('click', () => {
                    ambilBuku(hasil.links.prev);
                });

                pagination.appendChild(tombol);
            }

            if (hasil.links?.next) {
                const tombol = document.createElement('button');

                tombol.type = 'button';
                tombol.textContent = 'Selanjutnya';
                tombol.className =
                    'modul4-btn modul4-btn-primary';

                tombol.addEventListener('click', () => {
                    ambilBuku(hasil.links.next);
                });

                pagination.appendChild(tombol);
            }

            return true;

        } catch (error) {
            tabel.replaceChildren(
                buatBarisPesan('Data buku gagal dimuat.')
            );

            tampilkanPesan('pesan-error', error.message);

            return false;
        }
    }

    // menyiapkan pilihan kategori
    async function ambilKategori() {
        const pilihan = document.getElementById('kategori_id');

        try {
            let url = '/api/buku';
            const kategoriUnik = new Map();

            while (url) {
                const hasil = await permintaanAPI(url);

                (hasil.data ?? []).forEach((buku) => {
                    if (buku.kategori_id && buku.kategori) {
                        kategoriUnik.set(
                            String(buku.kategori_id),
                            buku.kategori
                        );
                    }
                });

                url = hasil.links?.next ?? null;
            }

            pilihanKategori = kategoriUnik;
            kategoriSiap = true;

            const nilaiSebelumnya = pilihan.value;

            pilihan.replaceChildren();

            const opsiAwal = document.createElement('option');
            opsiAwal.value = '';
            opsiAwal.textContent = '-- Pilih Kategori --';

            pilihan.appendChild(opsiAwal);

            kategoriUnik.forEach((nama, id) => {
                const opsi = document.createElement('option');

                opsi.value = id;
                opsi.textContent = nama;

                pilihan.appendChild(opsi);
            });

            // mempertahankan kategori saat pilihan diperbarui
            if (nilaiSebelumnya) {
                pilihan.value = nilaiSebelumnya;
            }

            if (kategoriUnik.size === 0) {
                tampilkanPesan(
                    'pesan-form',
                    'Belum ada pilihan kategori dari data buku.'
                );
            }

            return true;

        } catch (error) {
            kategoriSiap = false;

            tampilkanPesan(
                'pesan-form',
                'Gagal memuat kategori: ' + error.message
            );

            return false;
        }
    }

    // no 3: mengisi form ketika klik ubah
    function pilihBukuUntukDiubah(buku) {
        if (!kategoriSiap) {
            tampilkanPesan(
                'pesan-form',
                'Pilihan kategori belum siap. Coba muat ulang halaman.'
            );
            return;
        }

        const pilihan = document.getElementById('kategori_id');
        const idKategori = String(buku.kategori_id);

        // Pastikan kategori buku tersedia di dropdown
        if (!pilihanKategori.has(idKategori)) {
            const opsi = document.createElement('option');

            opsi.value = idKategori;
            opsi.textContent = buku.kategori ?? 'Kategori Buku';

            pilihan.appendChild(opsi);
        }

        document.getElementById('buku_id').value = buku.id;
        pilihan.value = idKategori;

        document.getElementById('judul').value = buku.judul;
        document.getElementById('penulis').value = buku.penulis;
        document.getElementById('tahun_terbit').value =
            buku.tahun_terbit;

        document.getElementById('judul-form').textContent =
            'Ubah Buku';

        document.getElementById('tombol-simpan').textContent =
            'Simpan Perubahan';

        document.getElementById('tombol-batal').style.display =
            'inline-block';

        tampilkanPesan(
            'pesan-form',
            'Sedang mengubah: ' + buku.judul
        );

        document.getElementById('form-buku').scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });
    }

    function resetForm() {
        document.getElementById('form-buku').reset();
        document.getElementById('buku_id').value = '';

        document.getElementById('judul-form').textContent =
            'Tambah Buku Baru';

        document.getElementById('tombol-simpan').textContent =
            'Simpan Buku';

        document.getElementById('tombol-batal').style.display =
            'none';

        tampilkanPesan('pesan-form', '');
    }

    // no 2, 3, dan 5: tambah / ubah + error
    async function simpanBuku(event) {
        event.preventDefault();

        const id = document.getElementById('buku_id').value;
        const sedangUbah = id !== '';

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
        tombol.textContent = 'Menyimpan...';

        tampilkanPesan('pesan-form', '');

        let berhasilSimpan = false;

        try {
            const url = sedangUbah
                ? '/api/buku/' + encodeURIComponent(id)
                : '/api/buku';

            // otomatis menghasilkan error bila status bukan 2xx
            await permintaanAPI(url, {
                method: sedangUbah ? 'PUT' : 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(dataBuku)
            });

            berhasilSimpan = true;
            resetForm();

            tampilkanPesan(
                'pesan-form',
                sedangUbah
                    ? 'Buku berhasil diubah!'
                    : 'Buku berhasil ditambahkan!',
                true
            );

        } catch (error) {
            tampilkanPesan('pesan-form', error.message);

        } finally {
            tombol.disabled = false;
            tombol.textContent =
                document.getElementById('buku_id').value
                    ? 'Simpan Perubahan'
                    : 'Simpan Buku';
        }

        if (berhasilSimpan) {
            const tabelBerhasil = await ambilBuku('/api/buku');

            if (!tabelBerhasil) {
                tampilkanPesan(
                    'pesan-form',
                    'Data tersimpan, tetapi tabel gagal diperbarui.'
                );
            } else {
                await ambilKategori();
            }
        }
    }

    // no 4 dan 5: hapus + error 
    async function hapusBuku(id, judul, tombol) {
        const yakin = confirm(
            'Yakin ingin menghapus buku "' + judul + '"?'
        );

        if (!yakin) {
            return;
        }

        tombol.disabled = true;
        tombol.textContent = 'Menghapus...';

        tampilkanPesan('pesan-error', '');

        let berhasilHapus = false;

        try {
            await permintaanAPI(
                '/api/buku/' + encodeURIComponent(id),
                {
                    method: 'DELETE'
                }
            );

            berhasilHapus = true;

            if (document.getElementById('buku_id').value ==
                String(id)) {
                resetForm();
            }

            tampilkanPesan(
                'pesan-form',
                'Buku berhasil dihapus!',
                true
            );

        } catch (error) {
            // status 409 jika buku punya peminjaman
            tampilkanPesan('pesan-error', error.message);

        } finally {
            tombol.disabled = false;
            tombol.textContent = 'Hapus';
        }

        if (berhasilHapus) {
            let tabelBerhasil = await ambilBuku(halamanAktif);

            // jika halaman pagination kosong setelah penghapusan
            // dan bukan halaman pertama, kembali ke halaman awal
            if (!tabelBerhasil || !document.querySelector(
                '#tabel-buku tr td:nth-child(2)'
            )) {
                tabelBerhasil = await ambilBuku('/api/buku');
            }

            if (!tabelBerhasil) {
                tampilkanPesan(
                    'pesan-form',
                    'Buku terhapus, tetapi tabel gagal diperbarui.'
                );
            } else {
                await ambilKategori();
            }
        }
    }

    // jalanan saat halaman dimuat
    document.addEventListener('DOMContentLoaded', async function () {
        // tunggu kategori selesai agar tombol Ubah
        // dapat memilih kategori yang benar
        await ambilKategori();

        await ambilBuku();

        document.getElementById('form-buku')
            .addEventListener('submit', simpanBuku);

        document.getElementById('tombol-batal')
            .addEventListener('click', resetForm);
    });
</script>

@endsection