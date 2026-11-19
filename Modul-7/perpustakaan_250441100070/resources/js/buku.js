/**
 * Halaman /buku mengonsumsi API memakai Fetch API dan async/await.
 *
 *   GET    /api/buku        -> mengisi tabel (semua yang boleh melihat buku)
 *   POST   /api/buku        -> tambah buku   (permission: kelola data buku)
 *   PUT    /api/buku/{id}   -> ubah buku     (permission: kelola data buku)
 *   DELETE /api/buku/{id}   -> hapus buku    (permission: kelola data buku)
 *
 * Modul 5: formulir tambah/ubah dan tombol Ubah/Hapus hanya untuk admin/petugas.
 * Tombol "Ajukan" tampil untuk yang punya permission mengajukan peminjaman (termasuk siswa).
 * Pembatasan yang sebenarnya tetap dijaga di server (middleware permission pada API).
 */

class ApiError extends Error {
    constructor(message, status = 0, errors = null) {
        super(message);
        this.name = 'ApiError';
        this.status = status;
        this.errors = errors;
    }
}

const tabel = document.getElementById('tabel-buku');

// Skrip dimuat lewat app.js di semua halaman, jadi hanya jalan di halaman /buku.
if (tabel) {
    jalankanHalamanBuku();
}

function jalankanHalamanBuku() {
    const API_URL = tabel.dataset.apiUrl;
    const DETAIL_URL = tabel.dataset.detailUrl;
    const BISA_KELOLA = tabel.dataset.kelola === '1';
    const BISA_AJUKAN = tabel.dataset.ajukan === '1';
    const AJUKAN_URL = tabel.dataset.ajukanUrl;
    const ADA_KOLOM_AKSI = BISA_KELOLA || BISA_AJUKAN;
    const JUMLAH_KOLOM = ADA_KOLOM_AKSI ? 6 : 5;
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.content;

    // Elemen formulir (null untuk siswa)
    const form = document.getElementById('form-buku');
    const judulForm = document.getElementById('judul-form');
    const inputId = document.getElementById('buku-id');
    const inputKategori = document.getElementById('kategori_id');
    const inputJudul = document.getElementById('judul');
    const inputPenulis = document.getElementById('penulis');
    const inputTahun = document.getElementById('tahun_terbit');
    const tombolSimpan = document.getElementById('btn-simpan');
    const tombolBatal = document.getElementById('btn-batal');

    const kotakPesan = document.getElementById('pesan');
    const tbody = document.getElementById('tbody-buku');
    const tombolSebelumnya = document.getElementById('btn-sebelumnya');
    const tombolBerikutnya = document.getElementById('btn-berikutnya');
    const infoHalaman = document.getElementById('info-halaman');

    let daftarBuku = [];
    let halamanAktif = 1;
    let halamanTerakhir = 1;
    let perHalaman = 10;

    /* ---------------------------------------------------------------
     * Fungsi dasar: satu-satunya tempat fetch() dipanggil
     * ------------------------------------------------------------- */
    async function panggilApi(url, { method = 'GET', body = null } = {}) {
        const headers = { Accept: 'application/json' };
        if (body) headers['Content-Type'] = 'application/json';
        if (CSRF_TOKEN) headers['X-CSRF-TOKEN'] = CSRF_TOKEN;

        let response;
        try {
            response = await fetch(url, {
                method,
                headers,
                body: body ? JSON.stringify(body) : undefined,
            });
        } catch (error) {
            throw new ApiError('API tidak dapat diakses. Pastikan server Laravel sedang berjalan.');
        }

        let data = null;
        try {
            data = await response.json();
        } catch (error) {
            data = null;
        }

        if (!response.ok) {
            throw new ApiError(pesanKesalahan(response.status, data), response.status, data?.errors ?? null);
        }

        return data;
    }

    function pesanKesalahan(status, data) {
        if (status === 401) return 'Sesi login berakhir. Silakan login kembali.';
        if (status === 403) return 'Anda tidak memiliki izin untuk melakukan aksi ini.';
        if (status === 422) return 'Data tidak valid. Periksa kembali isian formulir.';
        if (status === 404) return 'Data buku tidak ditemukan. Mungkin sudah dihapus.';
        if (status === 419) return 'Sesi kedaluwarsa. Muat ulang halaman lalu coba lagi.';
        if (status >= 500) return 'Terjadi kesalahan pada server. Coba lagi nanti.';
        return data?.message ?? `Permintaan gagal (status ${status}).`;
    }

    /* ---------------------------------------------------------------
     * Pesan dan error per kolom
     * ------------------------------------------------------------- */
    function tampilkanPesan(teks, jenis = 'sukses') {
        kotakPesan.textContent = teks;
        kotakPesan.className = `alert ${jenis === 'sukses' ? 'alert-success' : 'alert-danger'}`;
        kotakPesan.hidden = false;
    }

    function sembunyikanPesan() {
        kotakPesan.hidden = true;
    }

    function bersihkanErrorField() {
        if (!form) return;
        form.querySelectorAll('[data-error-for]').forEach((el) => {
            el.textContent = '';
        });
    }

    function tampilkanErrorField(errors) {
        if (!form) return;
        Object.entries(errors).forEach(([field, pesan]) => {
            const target = form.querySelector(`[data-error-for="${field}"]`);
            if (target) target.textContent = Array.isArray(pesan) ? pesan[0] : pesan;
        });
    }

    /* ---------------------------------------------------------------
     * Tabel (teks diisi lewat textContent supaya aman dari XSS)
     * ------------------------------------------------------------- */
    function buatSel(teks) {
        const td = document.createElement('td');
        td.textContent = teks;
        return td;
    }

    function buatTombol(teks, aksi, id, kelas) {
        const tombol = document.createElement('button');
        tombol.type = 'button';
        tombol.textContent = teks;
        tombol.className = kelas;
        tombol.dataset.aksi = aksi;
        tombol.dataset.id = id;
        return tombol;
    }

    // Tombol "Ajukan": form POST biasa ke route peminjaman.ajukan (server yang memproses & mengalihkan)
    function buatFormAjukan(id) {
        const formAjukan = document.createElement('form');
        formAjukan.method = 'POST';
        formAjukan.action = AJUKAN_URL.replace(/:id|%3Aid/i, id);
        formAjukan.className = 'inline-form';

        const token = document.createElement('input');
        token.type = 'hidden';
        token.name = '_token';
        token.value = CSRF_TOKEN ?? '';

        const tombol = document.createElement('button');
        tombol.type = 'submit';
        tombol.textContent = 'Ajukan';
        tombol.className = 'btn btn-small btn-primary';

        formAjukan.append(token, tombol);
        return formAjukan;
    }

    function tampilkanBarisPesan(teks) {
        const tr = document.createElement('tr');
        const td = buatSel(teks);
        td.colSpan = JUMLAH_KOLOM;
        td.className = 'kosong';
        tr.append(td);
        tbody.replaceChildren(tr);
    }

    function renderTabel() {
        if (daftarBuku.length === 0) {
            tampilkanBarisPesan('Belum ada data buku.');
            return;
        }

        const baris = daftarBuku.map((buku, indeks) => {
            const tr = document.createElement('tr');

            tr.append(buatSel((halamanAktif - 1) * perHalaman + indeks + 1));
            tr.append(buatSel(buku.kategori ?? '-'));

            const tdJudul = document.createElement('td');
            const tautan = document.createElement('a');
            tautan.href = DETAIL_URL.replace(/:id|%3Aid/i, buku.id);
            tautan.textContent = buku.judul;
            tdJudul.append(tautan);
            tr.append(tdJudul);

            tr.append(buatSel(buku.penulis));
            tr.append(buatSel(buku.tahun));

            if (ADA_KOLOM_AKSI) {
                const tdAksi = document.createElement('td');
                tdAksi.className = 'aksi';
                if (BISA_AJUKAN) {
                    tdAksi.append(buatFormAjukan(buku.id));
                }
                if (BISA_KELOLA) {
                    tdAksi.append(buatTombol('Ubah', 'ubah', buku.id, 'btn btn-small btn-warning'));
                    tdAksi.append(buatTombol('Hapus', 'hapus', buku.id, 'btn btn-small btn-danger'));
                }
                tr.append(tdAksi);
            }

            return tr;
        });

        tbody.replaceChildren(...baris);
    }

    function perbaruiPaginasi() {
        infoHalaman.textContent = `Halaman ${halamanAktif} dari ${halamanTerakhir}`;
        tombolSebelumnya.disabled = halamanAktif <= 1;
        tombolBerikutnya.disabled = halamanAktif >= halamanTerakhir;
    }

    /* ---------------------------------------------------------------
     * Ambil data (GET)
     * ------------------------------------------------------------- */
    async function muatData(halaman = halamanAktif) {
        try {
            const hasil = await panggilApi(`${API_URL}?page=${halaman}`);

            // Halaman yang diminta sudah kosong (misalnya setelah menghapus
            // data terakhir di halaman itu): pindah ke halaman terakhir.
            if (hasil.data.length === 0 && halaman > 1) {
                return muatData(Math.max(1, hasil.meta.last_page));
            }

            daftarBuku = hasil.data;
            halamanAktif = hasil.meta.current_page;
            halamanTerakhir = hasil.meta.last_page;
            perHalaman = hasil.meta.per_page;

            renderTabel();
            perbaruiPaginasi();
        } catch (error) {
            tampilkanBarisPesan('Data gagal dimuat.');
            tampilkanPesan(`Gagal memuat data buku. ${error.message}`, 'galat');
        }
    }

    tombolSebelumnya.addEventListener('click', () => muatData(halamanAktif - 1));
    tombolBerikutnya.addEventListener('click', () => muatData(halamanAktif + 1));

    /* ---------------------------------------------------------------
     * Tambah (POST), ubah (PUT), hapus (DELETE): hanya admin & petugas
     * ------------------------------------------------------------- */
    if (BISA_KELOLA && form) {
        function resetForm() {
            form.reset();
            inputId.value = '';
            judulForm.textContent = 'Tambah Buku';
            tombolSimpan.textContent = 'Simpan';
            tombolBatal.hidden = true;
        }

        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            bersihkanErrorField();
            sembunyikanPesan();

            const id = inputId.value;
            const sedangUbah = id !== '';

            // Nama field mengikuti nama kolom database
            const data = {
                kategori_id: inputKategori.value === '' ? '' : Number(inputKategori.value),
                judul: inputJudul.value,
                penulis: inputPenulis.value,
                tahun_terbit: inputTahun.value === '' ? '' : Number(inputTahun.value),
            };

            tombolSimpan.disabled = true;
            try {
                const hasil = await panggilApi(sedangUbah ? `${API_URL}/${id}` : API_URL, {
                    method: sedangUbah ? 'PUT' : 'POST',
                    body: data,
                });

                tampilkanPesan(hasil.message ?? 'Data berhasil disimpan.', 'sukses');
                resetForm();

                await muatData(sedangUbah ? halamanAktif : 1);
            } catch (error) {
                if (error.errors) tampilkanErrorField(error.errors);
                tampilkanPesan(error.message, 'galat');
            } finally {
                tombolSimpan.disabled = false;
            }
        });

        function mulaiUbah(id) {
            const buku = daftarBuku.find((item) => item.id === id);
            if (!buku) {
                tampilkanPesan('Data buku tidak ditemukan pada tabel.', 'galat');
                return;
            }

            bersihkanErrorField();
            sembunyikanPesan();

            inputId.value = buku.id;
            inputKategori.value = buku.kategori_id;
            inputJudul.value = buku.judul;
            inputPenulis.value = buku.penulis;
            inputTahun.value = buku.tahun;

            judulForm.textContent = 'Ubah Buku';
            tombolSimpan.textContent = 'Simpan Perubahan';
            tombolBatal.hidden = false;

            form.scrollIntoView({ behavior: 'smooth', block: 'center' });
            inputJudul.focus();
        }

        tombolBatal.addEventListener('click', () => {
            resetForm();
            bersihkanErrorField();
            sembunyikanPesan();
        });

        async function hapusBuku(id) {
            const buku = daftarBuku.find((item) => item.id === id);
            const nama = buku ? `"${buku.judul}"` : 'buku ini';

            if (!window.confirm(`Yakin ingin menghapus ${nama}?`)) {
                return;
            }

            sembunyikanPesan();
            try {
                const hasil = await panggilApi(`${API_URL}/${id}`, { method: 'DELETE' });

                tampilkanPesan(hasil?.message ?? 'Buku berhasil dihapus.', 'sukses');
                if (inputId.value === String(id)) resetForm();

                await muatData(halamanAktif);
            } catch (error) {
                tampilkanPesan(`Gagal menghapus buku. ${error.message}`, 'galat');
            }
        }

        tbody.addEventListener('click', (event) => {
            const tombol = event.target.closest('button[data-aksi]');
            if (!tombol) return;

            const id = Number(tombol.dataset.id);
            if (tombol.dataset.aksi === 'ubah') mulaiUbah(id);
            if (tombol.dataset.aksi === 'hapus') hapusBuku(id);
        });
    }

    // Mulai: ambil data halaman pertama
    muatData(1);
}
