@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')
<style>
    .modul4-container {
        width: 100%;
        max-width: 1000px;
        margin: 0 auto;
        padding: 36px 16px 32px;
    }

    .modul4-title {
        text-align: center;
        margin-bottom: 32px;
    }

    .modul4-title h2 {
        color: #800000;
        font-size: 28px;
        margin-bottom: 12px;
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
        align-items: center;
    }

    .modul4-btn {
        border: none;
        border-radius: 6px;
        padding: 11px 18px;
        font-size: 13px;
        font-weight: bold;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
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

    .modul4-alert {
        padding: 14px 18px;
        margin-bottom: 20px;
        border-radius: 8px;
        font-size: 14px;
    }

    .modul4-alert-success {
        background: #dcfce7;
        color: #166534;
    }

    .modul4-alert-error {
        background: #fee2e2;
        color: #991b1b;
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

    @if (session('success'))
        <div class="modul4-alert modul4-alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="modul4-alert modul4-alert-error">
            {{ session('error') }}
        </div>
    @endif

    @can('kelola-buku')
        @if (!request()->routeIs('buku.create'))
            <div style="margin-bottom: 20px;">
                <a
                    href="{{ route('buku.create') }}"
                    class="modul4-btn modul4-btn-primary"
                >
                    Tambah Buku
                </a>
            </div>
        @endif

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
                    <input
                        type="text"
                        id="judul"
                        minlength="3"
                        maxlength="150"
                        required
                    >
                </div>

                <div class="modul4-field">
                    <label for="penulis">Penulis</label>
                    <input
                        type="text"
                        id="penulis"
                        minlength="3"
                        maxlength="100"
                        required
                    >
                </div>

                <div class="modul4-field">
                    <label for="tahun_terbit">Tahun Terbit</label>
                    <input
                        type="number"
                        id="tahun_terbit"
                        min="1900"
                        max="2026"
                        required
                    >
                </div>

                <div class="modul4-actions">
                    <button
                        type="submit"
                        id="tombol-simpan"
                        class="modul4-btn modul4-btn-primary"
                    >
                        Simpan Buku
                    </button>

                    <button
                        type="button"
                        id="tombol-batal"
                        class="modul4-btn modul4-btn-secondary"
                        style="display:none;"
                    >
                        Batal Ubah
                    </button>
                </div>
            </form>

            <p
                id="pesan-form"
                class="modul4-message"
                role="status"
            ></p>
        </div>

        <template id="template-form-hapus">
            <form method="POST">
                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="modul4-btn modul4-btn-danger"
                >
                    Hapus
                </button>
            </form>
        </template>
    @endcan

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
                @forelse (
                    \App\Models\Buku::with('kategori')
                        ->orderByDesc('id')
                        ->get() as $buku
                )
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $buku->kategori?->nama ?? '-' }}</td>
                        <td>{{ $buku->judul }}</td>
                        <td>{{ $buku->penulis }}</td>
                        <td>{{ $buku->tahun_terbit }}</td>
                        <td>-</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center;">
                            Belum ada buku yang tersedia.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <p
        id="pesan-error"
        class="modul4-message"
        style="color:#b91c1c;"
        role="alert"
    ></p>

    <div id="pagination-buku" class="modul4-pagination"></div>
</div>

<script>
    const bolehKelolaBuku = @json(
        auth()->user()->can('kelola-buku')
    );

    let pilihanKategori = new Map();
    let kategoriSiap = false;

    async function permintaanAPI(url, options = {}) {
        let response;

        try {
            const csrfToken = document.querySelector(
                'meta[name="csrf-token"]'
            )?.content;

            const method = (options.method || 'GET').toUpperCase();

            response = await fetch(url, {
                ...options,
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json',
                    ...(
                        method !== 'GET' &&
                        method !== 'HEAD' &&
                        csrfToken
                            ? {'X-CSRF-TOKEN': csrfToken}
                            : {}
                    ),
                    ...(options.headers || {})
                }
            });
        } catch (error) {
            throw new Error(
                'Tidak dapat terhubung ke API. ' +
                'Periksa server Laravel.'
            );
        }

        let hasil;

        try {
            hasil = await response.json();
        } catch (error) {
            throw new Error(
                'Respons server tidak valid. Status: ' +
                response.status
            );
        }

        if (!response.ok) {
            const pesanValidasi = hasil.errors
                ? Object.values(hasil.errors).flat().join(' ')
                : '';

            throw new Error(
                pesanValidasi ||
                hasil.message ||
                'Permintaan gagal. Status: ' +
                response.status
            );
        }

        return hasil;
    }

    function tampilkanPesan(idElemen, pesan, berhasil = false) {
        const elemen = document.getElementById(idElemen);

        if (!elemen) {
            return;
        }

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

    async function ambilBuku(url = '/api/buku') {
        const tabel = document.getElementById('tabel-buku');
        const pagination = document.getElementById('pagination-buku');

        tabel.replaceChildren(
            buatBarisPesan('Memuat data buku...')
        );

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

                return;
            }

            daftarBuku.forEach((buku, index) => {
                const baris = document.createElement('tr');

                const halaman = hasil.meta?.current_page ?? 1;
                const perHalaman = hasil.meta?.per_page ?? 10;

                const nomor =
                    (halaman - 1) * perHalaman + index + 1;

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

                if (bolehKelolaBuku) {
                    const wadah = document.createElement('div');
                    wadah.className = 'modul4-table-actions';

                    const tombolUbah = document.createElement('button');
                    tombolUbah.type = 'button';
                    tombolUbah.textContent = 'Ubah';
                    tombolUbah.className =
                        'modul4-btn modul4-btn-warning';

                    tombolUbah.addEventListener('click', () => {
                        pilihBukuUntukDiubah(buku);
                    });

                    const template = document.getElementById(
                        'template-form-hapus'
                    );

                    const formHapus = template.content
                        .firstElementChild.cloneNode(true);

                    formHapus.action =
                        '/buku/' + encodeURIComponent(buku.id);

                    formHapus.addEventListener('submit', function (event) {
                        const yakin = confirm(
                            'Yakin ingin menghapus buku "' +
                            buku.judul +
                            '"?'
                        );

                        if (!yakin) {
                            event.preventDefault();
                        }
                    });

                    wadah.appendChild(tombolUbah);
                    wadah.appendChild(formHapus);
                    kolomAksi.appendChild(wadah);
                } else {
                    kolomAksi.textContent = '-';
                }

                baris.appendChild(kolomAksi);
                tabel.appendChild(baris);
            });

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
        } catch (error) {
            tabel.replaceChildren(
                buatBarisPesan('Data buku gagal dimuat.')
            );

            tampilkanPesan('pesan-error', error.message);
        }
    }

    async function ambilKategori() {
        const pilihan = document.getElementById('kategori_id');

        if (!pilihan) {
            return;
        }

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

        } catch (error) {
            kategoriSiap = false;
            tampilkanPesan(
                'pesan-form',
                'Gagal memuat kategori: ' + error.message
            );
        }
    }

    function pilihBukuUntukDiubah(buku) {
        if (!kategoriSiap) {
            tampilkanPesan(
                'pesan-form',
                'Pilihan kategori belum siap.'
            );
            return;
        }

        const pilihan = document.getElementById('kategori_id');
        const idKategori = String(buku.kategori_id);

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
    }

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

        try {
            const url = sedangUbah
                ? '/api/buku/' + encodeURIComponent(id)
                : '/api/buku';

            await permintaanAPI(url, {
                method: sedangUbah ? 'PUT' : 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(dataBuku)
            });

            resetForm();

            tampilkanPesan(
                'pesan-form',
                sedangUbah
                    ? 'Buku berhasil diubah!'
                    : 'Buku berhasil ditambahkan!',
                true
            );

            await ambilBuku('/api/buku');
            await ambilKategori();

        } catch (error) {
            tampilkanPesan('pesan-form', error.message);
        } finally {
            tombol.disabled = false;
            tombol.textContent =
                document.getElementById('buku_id').value
                    ? 'Simpan Perubahan'
                    : 'Simpan Buku';
        }
    }

    document.addEventListener(
        'DOMContentLoaded',
        async function () {
            if (bolehKelolaBuku) {
                await ambilKategori();
            }

            await ambilBuku();

            if (bolehKelolaBuku) {
                document.getElementById('form-buku')
                    .addEventListener('submit', simpanBuku);

                document.getElementById('tombol-batal')
                    .addEventListener('click', resetForm);
            }
        }
    );
</script>

@endsection