@extends('layouts.app')

@section('title', 'Manajemen Buku & Konsumsi RESTful API — Modul IV')

@section('content')

    <section style="padding: 2.5rem 0;" class="animate-fade-in-up">
        <div class="container-main">

            {{-- ========================================================
                 ALERT CONTAINER (Tugas 5: Penanganan Pesan Feedback)
                 ======================================================== --}}
            <div id="alert-container" style="display: none;"></div>


            {{-- ========================================================
                 MAIN CRUD SECTION (FORM & TABLE)
                 ======================================================== --}}
            <div class="crud-grid">

                {{-- ====================================================
                     KOLOM KIRI: FORMULIR TAMBAH / UBAH BUKU (Tugas 2 & 3)
                     ==================================================== --}}
                <div class="crud-card" id="card-form-buku">
                    <div class="crud-card__header">
                        <div style="display: flex; align-items: center; gap: 0.625rem;">
                            <div style="width: 2rem; height: 2rem; border-radius: 0.5rem; background: #e0e7ff; color: #4338ca; display: flex; align-items: center; justify-content: center; font-size: 0.875rem;">
                                <i id="form-header-icon" class="fa-solid fa-book-medical"></i>
                            </div>
                            <h3 id="form-heading-title" style="font-size: 1rem; font-weight: 700; color: #0f172a; margin: 0;">
                                Formulir Buku
                            </h3>
                        </div>
                        <span id="form-mode-badge" style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; padding: 0.25rem 0.5rem; border-radius: 0.375rem; background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0;">
                            Mode Tambah
                        </span>
                    </div>

                    <div class="crud-card__body">
                        <form id="form-buku" novalidate>
                            {{-- Hidden ID buku (kosong saat Tambah, terisi saat Ubah) --}}
                            <input type="hidden" id="buku-id" name="buku_id" value="">

                            {{-- Input 1: Kategori Buku --}}
                            <div class="form-group">
                                <label for="kategori_id" class="form-label">
                                    Kategori Buku <span class="required">*</span>
                                </label>
                                <select id="kategori_id" name="kategori_id" class="form-control" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach ($kategoris as $kategori)
                                        <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                                    @endforeach
                                </select>
                                <span id="error-kategori_id" class="invalid-feedback" style="display: none;"></span>
                            </div>

                            {{-- Input 2: Judul Buku --}}
                            <div class="form-group">
                                <label for="judul" class="form-label">
                                    Judul Buku <span class="required">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="judul"
                                    name="judul"
                                    class="form-control"
                                    placeholder="Contoh: Pemrograman Web Modern"
                                    required
                                >
                                <span id="error-judul" class="invalid-feedback" style="display: none;"></span>
                            </div>

                            {{-- Input 3: Penulis --}}
                            <div class="form-group">
                                <label for="penulis" class="form-label">
                                    Penulis <span class="required">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="penulis"
                                    name="penulis"
                                    class="form-control"
                                    placeholder="Contoh: Basyari Atho'illah"
                                    required
                                >
                                <span id="error-penulis" class="invalid-feedback" style="display: none;"></span>
                            </div>

                            {{-- Input 4: Tahun Terbit --}}
                            <div class="form-group">
                                <label for="tahun_terbit" class="form-label">
                                    Tahun Terbit <span class="required">*</span>
                                </label>
                                <input
                                    type="number"
                                    id="tahun_terbit"
                                    name="tahun_terbit"
                                    class="form-control"
                                    min="1900"
                                    max="{{ date('Y') }}"
                                    placeholder="Contoh: {{ date('Y') }}"
                                    required
                                >
                                <span id="error-tahun_terbit" class="invalid-feedback" style="display: none;"></span>
                            </div>

                            {{-- Tombol Aksi Formulir --}}
                            <div style="display: flex; flex-direction: column; gap: 0.5rem; margin-top: 1.5rem;">
                                <button
                                    type="submit"
                                    id="btn-submit-buku"
                                    class="btn-primary"
                                    style="width: 100%; justify-content: center; border: none; cursor: pointer;"
                                >
                                    <i id="btn-submit-icon" class="fa-solid fa-plus"></i>
                                    <span id="btn-submit-text">Simpan Buku</span>
                                </button>

                                <button
                                    type="button"
                                    id="btn-cancel-edit"
                                    class="btn-secondary"
                                    style="width: 100%; justify-content: center; display: none; cursor: pointer;"
                                >
                                    <i class="fa-solid fa-xmark"></i>
                                    <span>Batal Ubah</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>


                {{-- ====================================================
                     KOLOM KANAN: TABEL DATA BUKU VIA API (Tugas 1, 3, 4)
                     ==================================================== --}}
                <div class="crud-card">
                    <div class="crud-card__header">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <span id="badge-total-buku" style="font-size: 0.75rem; font-weight: 600; padding: 0.3rem 0.625rem; border-radius: 9999px; background: #f1f5f9; color: #475569;">
                                Memuat...
                            </span>

                            <button
                                type="button"
                                id="btn-refresh-buku"
                                title="Muat ulang data dari API"
                                class="btn-sm-action"
                                style="background: #f8fafc; border: 1px solid #cbd5e1; color: #475569; width: 2rem; height: 2rem; padding: 0;"
                            >
                                <i class="fa-solid fa-rotate"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Tabel Responsive --}}
                    <div class="table-responsive">
                        <table id="tabel-buku" class="crud-table">
                            <thead>
                                <tr>
                                    <th style="width: 50px; text-align: center;">No</th>
                                    <th>Kategori</th>
                                    <th>Judul Buku</th>
                                    <th>Penulis</th>
                                    <th style="width: 120px; text-align: center;">Tahun Terbit</th>
                                    <th style="width: 150px; text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tabel-buku-body">
                                {{-- Baris tabel akan di-render secara asinkron oleh JavaScript (Soal 1) --}}
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 3rem 1rem; color: #64748b;">
                                        <div style="display: flex; flex-direction: column; align-items: center; gap: 0.75rem;">
                                            <i class="fa-solid fa-circle-notch fa-spin" style="font-size: 1.75rem; color: #4f46e5;"></i>
                                            <p style="margin: 0; font-size: 0.875rem; font-weight: 500;">
                                                Mengambil data dari endpoint GET /api/buku...
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Footer Info --}}
                    <div style="padding: 0.875rem 1.5rem; background: #fafaf9; border-top: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; font-size: 0.75rem; color: #64748b;">
                        <span>
                            <i class="fa-solid fa-code-compare mr-1"></i> Data disinkronkan secara real-time via Fetch API
                        </span>
                        <span id="tabel-status-waktu"></span>
                    </div>
                </div>

            </div>


            {{-- ========================================================
                 DOKUMENTASI PEMETAAN API RESOURCE (Tugas 1 Ketentuan)
                 ======================================================== --}}
            <!-- <div style="margin-top: 2rem; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 1rem; padding: 1.5rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                    <div style="width: 2.25rem; height: 2.25rem; border-radius: 0.5rem; background: #ecfdf5; color: #059669; display: flex; align-items: center; justify-content: center;">
                        <i class="fa-solid fa-diagram-project"></i>
                    </div>
                    <div>
                        <h4 style="margin: 0; font-size: 0.9375rem; font-weight: 700; color: #0f172a;">
                            Penjelasan Pemetaan Atribut API Resource (Soal 1)
                        </h4>
                        <p style="margin: 0.125rem 0 0; font-size: 0.75rem; color: #64748b;">
                            Struktur respons API diatur menggunakan kelas <code>App\Http\Resources\BukuResource</code> untuk memisahkan representasi data dari skema fisik database.
                        </p>
                    </div>
                </div>

                <div style="overflow-x: auto;">
                    <table style="width: 100%; font-size: 0.8125rem; border-collapse: collapse; text-align: left;">
                        <thead>
                            <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                                <th style="padding: 0.5rem 0.75rem; font-weight: 600; color: #475569;">Atribut Respons API</th>
                                <th style="padding: 0.5rem 0.75rem; font-weight: 600; color: #475569;">Nama Kolom Database / Relasi</th>
                                <th style="padding: 0.5rem 0.75rem; font-weight: 600; color: #475569;">Tipe Data</th>
                                <th style="padding: 0.5rem 0.75rem; font-weight: 600; color: #475569;">Keterangan Pemetaan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 0.5rem 0.75rem;"><code style="color:#059669; font-weight:600;">kategori</code></td>
                                <td style="padding: 0.5rem 0.75rem;"><code>kategoris.nama</code> (via FK <code>kategori_id</code>)</td>
                                <td style="padding: 0.5rem 0.75rem;">String</td>
                                <td style="padding: 0.5rem 0.75rem; color: #475569;">Disajikan sebagai nama kategori langsung, bukan sekadar foreign key integer ID</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 0.5rem 0.75rem;"><code style="color:#059669; font-weight:600;">judul</code></td>
                                <td style="padding: 0.5rem 0.75rem;"><code>bukus.judul</code></td>
                                <td style="padding: 0.5rem 0.75rem;">String</td>
                                <td style="padding: 0.5rem 0.75rem; color: #475569;">Judul karya buku</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 0.5rem 0.75rem;"><code style="color:#059669; font-weight:600;">penulis</code></td>
                                <td style="padding: 0.5rem 0.75rem;"><code>bukus.penulis</code></td>
                                <td style="padding: 0.5rem 0.75rem;">String</td>
                                <td style="padding: 0.5rem 0.75rem; color: #475569;">Nama pengarang/penulis buku</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 0.5rem 0.75rem;"><code style="color:#059669; font-weight:600;">tahun_terbit</code> / <code style="color:#059669; font-weight:600;">tahun</code></td>
                                <td style="padding: 0.5rem 0.75rem;"><code>bukus.tahun_terbit</code></td>
                                <td style="padding: 0.5rem 0.75rem;">Integer (Casted)</td>
                                <td style="padding: 0.5rem 0.75rem; color: #475569;">Dipetakan ke integer tahun masehi</td>
                            </tr>
                            <tr>
                                <td style="padding: 0.5rem 0.75rem;"><code style="color:#059669; font-weight:600;">kategori_id</code></td>
                                <td style="padding: 0.5rem 0.75rem;"><code>bukus.kategori_id</code></td>
                                <td style="padding: 0.5rem 0.75rem;">Integer</td>
                                <td style="padding: 0.5rem 0.75rem; color: #475569;">Disertakan untuk pengisian dropdown saat aksi Ubah data buku</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div> -->

        </div>
    </section>


    {{-- ========================================================
         MODAL KONFIRMASI HAPUS BUKU (Tugas 4: Konfirmasi Hapus)
         ======================================================== --}}
    <div id="modal-delete" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="modal-delete-title">
        <div class="modal-container">
            <div style="display: flex; align-items: flex-start; gap: 1rem; margin-bottom: 1.25rem;">
                <div style="width: 2.75rem; height: 2.75rem; border-radius: 9999px; background: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center; font-size: 1.125rem; flex-shrink: 0;">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div>
                    <h3 id="modal-delete-title" style="margin: 0; font-size: 1.125rem; font-weight: 700; color: #0f172a;">
                        Konfirmasi Hapus Buku
                    </h3>
                    <p style="margin: 0.5rem 0 0; font-size: 0.875rem; color: #475569; line-height: 1.5;">
                        Apakah Anda yakin ingin menghapus buku berikut?
                    </p>
                    <p id="modal-delete-book-info" style="margin: 0.5rem 0 0; font-size: 0.875rem; font-weight: 600; color: #1e293b; background: #f8fafc; padding: 0.5rem 0.75rem; border-radius: 0.5rem; border-left: 3px solid #dc2626;">
                        {{-- Diisi secara dinamis dengan judul buku --}}
                    </p>
                    <p style="margin: 0.5rem 0 0; font-size: 0.75rem; color: #94a3b8;">
                        Data yang dihapus dari basis data tidak dapat dipulihkan kembali.
                    </p>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                <button
                    type="button"
                    id="btn-cancel-delete"
                    class="btn-secondary"
                    style="padding: 0.5rem 1rem; font-size: 0.8125rem; cursor: pointer;"
                >
                    <i class="fa-solid fa-xmark mr-1"></i> Batal
                </button>
                <button
                    type="button"
                    id="btn-confirm-delete"
                    class="btn-sm-action"
                    style="background: #dc2626; color: #ffffff; border: none; padding: 0.5rem 1rem; font-size: 0.8125rem; border-radius: 0.5rem; cursor: pointer;"
                >
                    <i id="btn-confirm-delete-icon" class="fa-solid fa-trash mr-1"></i>
                    <span id="btn-confirm-delete-text">Ya, Hapus Buku</span>
                </button>
            </div>
        </div>
    </div>

@endsection


@push('scripts')
<script>
/**
 * =========================================================================
 * JAVASCRIPT KONSUMSI RESTful API (MODUL IV)
 * =========================================================================
 * Mengimplementasikan:
 * 1. GET /api/buku menggunakan Fetch API & async/await + API Resource
 * 2. POST /api/buku untuk penambahan data buku baru
 * 3. PUT /api/buku/{id} untuk perubahan data buku terpilih
 * 4. DELETE /api/buku/{id} dengan modal konfirmasi pengguna
 * 5. Penanganan kesalahan (response.ok, try/catch, validasi 422, error banner)
 * =========================================================================
 */

document.addEventListener('DOMContentLoaded', () => {

    // -------------------------------------------------------------------------
    // 1. KONFIGURASI DAN STATE APLIKASI
    // -------------------------------------------------------------------------
    const API_BUKU_URL = '/api/buku';
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    let daftarBuku = [];           // Cache lokal data buku dari API
    let idBukuAkanDihapus = null;   // ID buku yang sedang menunggu konfirmasi hapus
    let alertTimeout = null;       // Penampung timeout auto-dismiss alert

    // Elemen DOM Form
    const formBuku = document.getElementById('form-buku');
    const inputBukuId = document.getElementById('buku-id');
    const inputKategoriId = document.getElementById('kategori_id');
    const inputJudul = document.getElementById('judul');
    const inputPenulis = document.getElementById('penulis');
    const inputTahunTerbit = document.getElementById('tahun_terbit');

    // Elemen Header & Tombol Form
    const formHeadingTitle = document.getElementById('form-heading-title');
    const formModeBadge = document.getElementById('form-mode-badge');
    const formHeaderIcon = document.getElementById('form-header-icon');
    const btnSubmitBuku = document.getElementById('btn-submit-buku');
    const btnSubmitText = document.getElementById('btn-submit-text');
    const btnSubmitIcon = document.getElementById('btn-submit-icon');
    const btnCancelEdit = document.getElementById('btn-cancel-edit');

    // Elemen Tabel & Toolbar
    const tabelBukuBody = document.getElementById('tabel-buku-body');
    const badgeTotalBuku = document.getElementById('badge-total-buku');
    const btnRefreshBuku = document.getElementById('btn-refresh-buku');
    const tabelStatusWaktu = document.getElementById('tabel-status-waktu');
    const alertContainer = document.getElementById('alert-container');

    // Elemen Modal Konfirmasi Hapus
    const modalDelete = document.getElementById('modal-delete');
    const modalDeleteBookInfo = document.getElementById('modal-delete-book-info');
    const btnCancelDelete = document.getElementById('btn-cancel-delete');
    const btnConfirmDelete = document.getElementById('btn-confirm-delete');
    const btnConfirmDeleteIcon = document.getElementById('btn-confirm-delete-icon');
    const btnConfirmDeleteText = document.getElementById('btn-confirm-delete-text');


    // -------------------------------------------------------------------------
    // 2. FUNGSI UTILITAS: ALERT NOTIFIKASI & ERROR HANDLING (Tugas 5)
    // -------------------------------------------------------------------------
    /**
     * Menampilkan pesan alert notifikasi pada halaman.
     * @param {'success'|'danger'|'info'} tipe
     * @param {string} pesan
     * @param {string|null} judul
     */
    function tampilkanPesan(tipe, pesan, judul = null) {
        if (alertTimeout) {
            clearTimeout(alertTimeout);
        }

        const icons = {
            success: 'fa-circle-check',
            danger: 'fa-circle-exclamation',
            info: 'fa-circle-info'
        };

        const alertClass = tipe === 'danger' ? 'alert-danger' : (tipe === 'success' ? 'alert-success' : 'alert-info');
        const iconClass = icons[tipe] || 'fa-bell';

        alertContainer.className = `alert-box ${alertClass}`;
        alertContainer.innerHTML = `
            <i class="fa-solid ${iconClass}" style="font-size: 1.125rem; margin-top: 0.125rem;"></i>
            <div style="flex: 1;">
                ${judul ? `<div style="font-weight: 700; margin-bottom: 0.25rem;">${escapeHtml(judul)}</div>` : ''}
                <div>${escapeHtml(pesan)}</div>
            </div>
            <button type="button" class="alert-close" aria-label="Tutup pesan">&times;</button>
        `;
        alertContainer.style.display = 'flex';

        // Event tombol tutup
        alertContainer.querySelector('.alert-close').addEventListener('click', () => {
            alertContainer.style.display = 'none';
        });

        // Auto dismiss setelah 6 detik
        alertTimeout = setTimeout(() => {
            alertContainer.style.display = 'none';
        }, 6000);

        // Scroll agar notifikasi terlihat jika berada di bawah
        alertContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    /**
     * Menghapus seluruh pesan validasi error pada formulir.
     */
    function bersihkanErrorForm() {
        ['kategori_id', 'judul', 'penulis', 'tahun_terbit'].forEach(field => {
            const inputEl = document.getElementById(field);
            const errorEl = document.getElementById(`error-${field}`);
            if (inputEl) inputEl.classList.remove('is-invalid');
            if (errorEl) {
                errorEl.textContent = '';
                errorEl.style.display = 'none';
            }
        });
    }

    /**
     * Menampilkan pesan validasi error per field dari respons API 422 Unprocessable Entity.
     * @param {Object} errors
     */
    function tampilkanErrorValidasi(errors) {
        bersihkanErrorForm();
        let firstField = null;

        for (const [field, messages] of Object.entries(errors)) {
            const inputEl = document.getElementById(field);
            const errorEl = document.getElementById(`error-${field}`);

            if (inputEl) {
                inputEl.classList.add('is-invalid');
                if (!firstField) firstField = inputEl;
            }

            if (errorEl && messages.length > 0) {
                errorEl.textContent = messages[0];
                errorEl.style.display = 'block';
            }
        }

        if (firstField) {
            firstField.focus();
        }
    }

    /**
     * Helper pencegah XSS pada perenderan HTML dinamis.
     */
    function escapeHtml(str) {
        if (str === null || str === undefined) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }


    // -------------------------------------------------------------------------
    // 3. TUGAS 1: PENGAMBILAN DATA (GET /api/buku DENGAN ASYNC/AWAIT & API RESOURCE)
    // -------------------------------------------------------------------------
    /**
     * Mengambil daftar buku dari API menggunakan Fetch API & async/await.
     * Menggunakan BukuResource untuk mapping atribut (kategori, judul, penulis, tahun_terbit).
     */
    async function muatDaftarBuku() {
        // Tampilkan indikator loading pada tabel
        tabelBukuBody.innerHTML = `
            <tr>
                <td colspan="6" style="text-align: center; padding: 2.5rem 1rem; color: #64748b;">
                    <div style="display: flex; flex-direction: column; align-items: center; gap: 0.75rem;">
                        <i class="fa-solid fa-circle-notch fa-spin" style="font-size: 1.75rem; color: #4f46e5;"></i>
                        <span style="font-size: 0.875rem; font-weight: 500;">
                            Mengambil data dari endpoint GET /api/buku...
                        </span>
                    </div>
                </td>
            </tr>
        `;
        badgeTotalBuku.textContent = 'Memuat...';

        try {
            // Permintaan GET ke API dengan header Accept: application/json
            const response = await fetch(`${API_BUKU_URL}?all=1`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            // Tugas 5: Periksa status respons menggunakan response.ok
            if (!response.ok) {
                throw new Error(`Server merespons dengan status HTTP ${response.status} (${response.statusText || 'Gagal'})`);
            }

            const result = await response.json();
            // Data diambil dari atribut 'data' yang dihasilkan oleh BukuResource::collection
            daftarBuku = Array.isArray(result.data) ? result.data : [];

            renderTabelBuku(daftarBuku);
            badgeTotalBuku.textContent = `${daftarBuku.length} Buku`;
            tabelStatusWaktu.textContent = `Pembaruan terakhir: ${new Date().toLocaleTimeString('id-ID')}`;

        } catch (error) {
            console.error('Kesalahan saat memuat data buku:', error);
            tabelBukuBody.innerHTML = `
                <tr>
                    <td colspan="6" style="text-align: center; padding: 2.5rem 1rem; color: #b91c1c;">
                        <div style="display: flex; flex-direction: column; align-items: center; gap: 0.75rem;">
                            <i class="fa-solid fa-triangle-exclamation" style="font-size: 2rem; color: #ef4444;"></i>
                            <div style="font-weight: 600;">Gagal mengambil data dari API</div>
                            <div style="font-size: 0.8125rem; color: #64748b; max-width: 24rem;">
                                ${escapeHtml(error.message)}
                            </div>
                            <button type="button" id="btn-coba-lagi" class="btn-sm-action" style="background:#4f46e5; color:#fff; padding:0.5rem 1rem; border-radius:0.5rem; margin-top:0.25rem;">
                                <i class="fa-solid fa-rotate-right mr-1"></i> Coba Lagi
                            </button>
                        </div>
                    </td>
                </tr>
            `;
            document.getElementById('btn-coba-lagi')?.addEventListener('click', muatDaftarBuku);
            tampilkanPesan('danger', `Gagal mengambil data dari endpoint GET /api/buku: ${error.message}`, 'Terjadi Kesalahan Jaringan');
        }
    }

    /**
     * Merender data array buku ke dalam elemen HTML <tbody> tabel.
     * Menggunakan atribut yang dihasilkan API Resource:
     * - item.kategori (pemetaan dari relasi tabel kategoris)
     * - item.judul
     * - item.penulis
     * - item.tahun_terbit
     * @param {Array} items
     */
    function renderTabelBuku(items) {
        if (!items || items.length === 0) {
            tabelBukuBody.innerHTML = `
                <tr>
                    <td colspan="6" style="text-align: center; padding: 3rem 1rem; color: #64748b;">
                        <div style="display: flex; flex-direction: column; align-items: center; gap: 0.75rem;">
                            <div style="width: 3.5rem; height: 3.5rem; border-radius: 1rem; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #94a3b8; font-size: 1.5rem;">
                                <i class="fa-solid fa-book-open"></i>
                            </div>
                            <div style="font-weight: 600; color: #1e293b;">Belum Ada Data Buku</div>
                            <p style="margin: 0; font-size: 0.8125rem; color: #64748b;">
                                Silakan gunakan formulir di samping untuk menambahkan buku baru ke basis data.
                            </p>
                        </div>
                    </td>
                </tr>
            `;
            return;
        }

        let html = '';
        items.forEach((buku, index) => {
            // Atribut sesuai dengan BukuResource: kategori, judul, penulis, tahun_terbit
            const nomor = index + 1;
            const kategoriNama = buku.kategori || 'Tanpa Kategori';
            const judulBuku = buku.judul || '-';
            const penulisBuku = buku.penulis || '-';
            const tahunBuku = buku.tahun_terbit || buku.tahun || '-';
            const bukuId = buku.id;

            html += `
                <tr id="row-buku-${bukuId}">
                    <td style="text-align: center; font-weight: 600; color: #64748b;">
                        ${nomor}
                    </td>
                    <td>
                        <span style="display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.25rem 0.625rem; border-radius: 0.375rem; background: #e0e7ff; color: #4338ca; font-size: 0.75rem; font-weight: 600;">
                            <i class="fa-solid fa-tag" style="font-size: 0.65rem;"></i>
                            ${escapeHtml(kategoriNama)}
                        </span>
                    </td>
                    <td>
                        <div style="font-weight: 600; color: #0f172a;">${escapeHtml(judulBuku)}</div>
                        <div style="font-size: 0.75rem; color: #94a3b8;">ID: #${bukuId}</div>
                    </td>
                    <td style="color: #334155;">
                        <i class="fa-solid fa-user-pen" style="color: #94a3b8; font-size: 0.75rem; margin-right: 0.25rem;"></i>
                        ${escapeHtml(penulisBuku)}
                    </td>
                    <td style="text-align: center;">
                        <span style="display: inline-block; padding: 0.25rem 0.5rem; border-radius: 0.375rem; background: #f8fafc; border: 1px solid #e2e8f0; font-weight: 600; font-size: 0.75rem; color: #334155;">
                            ${escapeHtml(tahunBuku)}
                        </span>
                    </td>
                    <td style="text-align: center;">
                        <div class="btn-action-group">
                            {{-- Tugas 3: Tombol Ubah --}}
                            <button
                                type="button"
                                class="btn-sm-action btn-action-edit btn-edit-buku"
                                data-id="${bukuId}"
                                title="Ubah data buku ini"
                            >
                                <i class="fa-solid fa-pen-to-square"></i>
                                <span>Ubah</span>
                            </button>

                            {{-- Tugas 4: Tombol Hapus --}}
                            <button
                                type="button"
                                class="btn-sm-action btn-action-delete btn-hapus-buku"
                                data-id="${bukuId}"
                                data-judul="${escapeHtml(judulBuku)}"
                                title="Hapus buku ini"
                            >
                                <i class="fa-solid fa-trash-can"></i>
                                <span>Hapus</span>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });

        tabelBukuBody.innerHTML = html;

        // Pasang event listener untuk tombol Ubah & Hapus yang baru di-render
        tabelBukuBody.querySelectorAll('.btn-edit-buku').forEach(button => {
            button.addEventListener('click', () => {
                const id = button.getAttribute('data-id');
                aktifkanModeUbah(id);
            });
        });

        tabelBukuBody.querySelectorAll('.btn-hapus-buku').forEach(button => {
            button.addEventListener('click', () => {
                const id = button.getAttribute('data-id');
                const judul = button.getAttribute('data-judul');
                bukaModalKonfirmasiHapus(id, judul);
            });
        });
    }


    // -------------------------------------------------------------------------
    // 4. TUGAS 2: PENAMBAHAN DATA BUKU (POST /api/buku DENGAN FETCH API)
    //    DAN TUGAS 3: PERUBAHAN DATA BUKU (PUT /api/buku/{id} DENGAN FETCH API)
    // -------------------------------------------------------------------------
    /**
     * Menangani pengiriman formulir, baik untuk Tambah (POST) maupun Ubah (PUT).
     */
    formBuku.addEventListener('submit', async (event) => {
        event.preventDefault();
        bersihkanErrorForm();

        const id = inputBukuId.value.trim();
        const isEditMode = id !== '';

        // Siapkan payload data dari formulir
        const payload = {
            kategori_id: inputKategoriId.value ? parseInt(inputKategoriId.value, 10) : null,
            judul: inputJudul.value.trim(),
            penulis: inputPenulis.value.trim(),
            tahun_terbit: inputTahunTerbit.value ? parseInt(inputTahunTerbit.value, 10) : null
        };

        // Tentukan URL dan HTTP Method
        const url = isEditMode ? `${API_BUKU_URL}/${id}` : API_BUKU_URL;
        const method = isEditMode ? 'PUT' : 'POST';

        // Indikator loading pada tombol simpan
        btnSubmitBuku.disabled = true;
        btnSubmitIcon.className = 'fa-solid fa-circle-notch fa-spin';
        btnSubmitText.textContent = isEditMode ? 'Memperbarui...' : 'Menyimpan...';

        try {
            // Permintaan HTTP ke API menggunakan Fetch API & async/await
            const response = await fetch(url, {
                method: method,
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(payload)
            });

            const result = await response.json();

            // Tugas 5: Periksa status respons menggunakan response.ok
            if (!response.ok) {
                // Kasus 422: Validasi input gagal dari $request->validate()
                if (response.status === 422 && result.errors) {
                    tampilkanErrorValidasi(result.errors);
                    tampilkanPesan('danger', 'Validasi gagal. Silakan periksa kembali kolom formulir yang bertanda merah.', 'Data Tidak Valid');
                    return;
                }

                // Kasus error HTTP lainnya
                throw new Error(result.message || `Terjadi kesalahan pada server (Status HTTP: ${response.status})`);
            }

            // Operasi Berhasil:
            const pesanSukses = isEditMode
                ? `Buku "${payload.judul}" berhasil diperbarui!`
                : `Buku baru "${payload.judul}" berhasil ditambahkan ke basis data!`;

            tampilkanPesan('success', pesanSukses, 'Operasi Berhasil');

            // Reset formulir kembali kosong dan ke Mode Tambah
            resetFormKeModeTambah();

            // Perbarui tabel tanpa reload halaman secara manual
            await muatDaftarBuku();

        } catch (error) {
            console.error('Kesalahan saat menyimpan buku:', error);
            tampilkanPesan('danger', error.message || 'Gagal menyimpan data buku ke server.', 'Gagal Menyimpan Data');
        } finally {
            btnSubmitBuku.disabled = false;
            perbaruiTombolSubmit();
        }
    });


    // -------------------------------------------------------------------------
    // 5. TUGAS 3: TOMBOL UBAH & MODE UBAH DATA BUKU (PUT)
    // -------------------------------------------------------------------------
    /**
     * Mengisi formulir dengan data buku yang dipilih dan mengubah antarmuka ke Mode Ubah.
     * @param {string|number} id
     */
    function aktifkanModeUbah(id) {
        bersihkanErrorForm();

        // Cari data buku dari cache lokal
        const buku = daftarBuku.find(b => String(b.id) === String(id));
        if (!buku) {
            tampilkanPesan('danger', `Buku dengan ID #${id} tidak ditemukan pada data saat ini.`, 'Data Tidak Ditemukan');
            return;
        }

        // Isi kolom-kolom formulir dengan data buku terpilih
        inputBukuId.value = buku.id;
        inputKategoriId.value = buku.kategori_id || '';
        inputJudul.value = buku.judul || '';
        inputPenulis.value = buku.penulis || '';
        inputTahunTerbit.value = buku.tahun_terbit || buku.tahun || '';

        // Ubah tampilan visual kartu formulir ke Mode Ubah
        formHeadingTitle.textContent = `Ubah Buku #${buku.id}`;
        formHeaderIcon.className = 'fa-solid fa-pen-to-square';
        formModeBadge.textContent = 'Mode Ubah';
        formModeBadge.style.background = '#fef3c7';
        formModeBadge.style.color = '#92400e';
        formModeBadge.style.borderColor = '#fde68a';

        // Tampilkan tombol Batal Ubah
        btnCancelEdit.style.display = 'inline-flex';

        // Ubah teks tombol Simpan
        perbaruiTombolSubmit();

        // Gulir halus ke formulir agar pengguna langsung fokus mengedit
        document.getElementById('card-form-buku').scrollIntoView({ behavior: 'smooth', block: 'start' });
        inputJudul.focus();
    }

    /**
     * Mengembalikan formulir ke Mode Tambah awal dan mengosongkan semua isian.
     */
    function resetFormKeModeTambah() {
        formBuku.reset();
        inputBukuId.value = '';
        bersihkanErrorForm();

        formHeadingTitle.textContent = 'Formulir Buku';
        formHeaderIcon.className = 'fa-solid fa-book-medical';
        formModeBadge.textContent = 'Mode Tambah';
        formModeBadge.style.background = '#dcfce7';
        formModeBadge.style.color = '#15803d';
        formModeBadge.style.borderColor = '#bbf7d0';

        btnCancelEdit.style.display = 'none';
        perbaruiTombolSubmit();
    }

    /**
     * Memperbarui teks dan ikon tombol submit berdasarkan mode aktif.
     */
    function perbaruiTombolSubmit() {
        const isEditMode = inputBukuId.value.trim() !== '';
        if (isEditMode) {
            btnSubmitIcon.className = 'fa-solid fa-floppy-disk';
            btnSubmitText.textContent = 'Perbarui Buku';
            btnSubmitBuku.style.background = '#d97706';
        } else {
            btnSubmitIcon.className = 'fa-solid fa-plus';
            btnSubmitText.textContent = 'Simpan Buku';
            btnSubmitBuku.style.background = '#4f46e5';
        }
    }

    // Event listener tombol Batal Ubah
    btnCancelEdit.addEventListener('click', () => {
        resetFormKeModeTambah();
        tampilkanPesan('info', 'Mode ubah dibatalkan. Formulir kembali ke mode tambah baru.');
    });


    // -------------------------------------------------------------------------
    // 6. TUGAS 4: PENGHAPUSAN DATA DENGAN KONFIRMASI (DELETE /api/buku/{id})
    // -------------------------------------------------------------------------
    /**
     * Membuka modal konfirmasi sebelum menghapus buku.
     * @param {string|number} id
     * @param {string} judul
     */
    function bukaModalKonfirmasiHapus(id, judul) {
        idBukuAkanDihapus = id;
        modalDeleteBookInfo.textContent = `"${judul}" (ID: #${id})`;
        modalDelete.classList.add('is-active');
    }

    /**
     * Menutup modal konfirmasi hapus tanpa melakukan aksi apapun.
     */
    function tutupModalKonfirmasiHapus() {
        modalDelete.classList.remove('is-active');
        idBukuAkanDihapus = null;
    }

    // Tombol Batal pada Modal Konfirmasi Hapus
    btnCancelDelete.addEventListener('click', () => {
        tutupModalKonfirmasiHapus();
        tampilkanPesan('info', 'Penghapusan buku dibatalkan.');
    });

    // Menutup modal jika area overlay luar di-klik
    modalDelete.addEventListener('click', (event) => {
        if (event.target === modalDelete) {
            tutupModalKonfirmasiHapus();
        }
    });

    // Menutup modal jika tombol Escape ditekan
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && modalDelete.classList.contains('is-active')) {
            tutupModalKonfirmasiHapus();
        }
    });

    /**
     * Mengirim permintaan HTTP DELETE ke endpoint /api/buku/{id} via Fetch API & async/await.
     */
    btnConfirmDelete.addEventListener('click', async () => {
        if (!idBukuAkanDihapus) return;

        const idHapus = idBukuAkanDihapus;

        // Indikator loading pada tombol konfirmasi modal
        btnConfirmDelete.disabled = true;
        btnConfirmDeleteIcon.className = 'fa-solid fa-circle-notch fa-spin';
        btnConfirmDeleteText.textContent = 'Menghapus...';

        try {
            // Permintaan DELETE ke API
            const response = await fetch(`${API_BUKU_URL}/${idHapus}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            const result = await response.json();

            // Tugas 5: Periksa status respons menggunakan response.ok
            if (!response.ok) {
                throw new Error(result.message || `Gagal menghapus buku (Status HTTP: ${response.status})`);
            }

            // Tutup modal konfirmasi
            tutupModalKonfirmasiHapus();

            tampilkanPesan('success', result.message || 'Buku berhasil dihapus dari basis data!', 'Berhasil Dihapus');

            // Jika buku yang sedang diedit adalah yang baru saja dihapus, reset formulirnya
            if (inputBukuId.value === String(idHapus)) {
                resetFormKeModeTambah();
            }

            // Perbarui data tabel tanpa reload manual
            await muatDaftarBuku();

        } catch (error) {
            console.error('Kesalahan saat menghapus buku:', error);
            tampilkanPesan('danger', error.message || 'Gagal menghapus buku dari server.', 'Gagal Menghapus');
            tutupModalKonfirmasiHapus();
        } finally {
            btnConfirmDelete.disabled = false;
            btnConfirmDeleteIcon.className = 'fa-solid fa-trash mr-1';
            btnConfirmDeleteText.textContent = 'Ya, Hapus Buku';
        }
    });


    // -------------------------------------------------------------------------
    // 7. EVENT TOOLBAR & INITIAL MOUNT
    // -------------------------------------------------------------------------
    // Tombol Refresh data tabel secara manual
    btnRefreshBuku.addEventListener('click', () => {
        const refreshIcon = btnRefreshBuku.querySelector('i');
        refreshIcon.classList.add('fa-spin');
        muatDaftarBuku().finally(() => {
            setTimeout(() => refreshIcon.classList.remove('fa-spin'), 600);
        });
    });

    // Inisialisasi awal saat halaman pertama kali dibuka (Soal 1)
    muatDaftarBuku();
});
</script>
@endpush