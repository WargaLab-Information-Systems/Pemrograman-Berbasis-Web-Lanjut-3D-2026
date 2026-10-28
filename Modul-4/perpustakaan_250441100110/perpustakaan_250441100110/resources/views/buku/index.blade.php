@extends('layouts.app')

@section('judul', 'Daftar Buku')

@section('konten')

    <h2>Daftar Buku</h2>

    <div id="pesan" class="pesan" hidden></div>

    <form id="form-buku" class="form-buku" novalidate>
        <h3 id="judul-form">Tambah Buku</h3>

       <div class="baris-form">
            <label for="kategori_id">Kategori</label>
            <input type="number" id="kategori_id" name="kategori_id" min="1" step="1">
            <small class="error-field" id="error-kategori_id"></small>
        </div>

        <div class="baris-form">
            <label for="judul">Judul</label>
            <input type="text" id="judul" name="judul">
            <small class="error-field" id="error-judul"></small>
        </div>

        <div class="baris-form">
            <label for="penulis">Penulis</label>
            <input type="text" id="penulis" name="penulis">
            <small class="error-field" id="error-penulis"></small>
        </div>

        <div class="baris-form">
            <label for="tahun_terbit">Tahun Terbit</label>
            <input type="number" id="tahun_terbit" name="tahun_terbit">
            <small class="error-field" id="error-tahun_terbit"></small>
        </div>

        <div class="aksi-form">
            <button type="submit" id="tombol-simpan" class="tombol">Simpan</button>
            <button type="button" id="tombol-batal" class="tombol tombol-abu" hidden>Batal</button>
        </div>
    </form>

    <table class="tabel-buku">
        <thead>
            <tr>
                <th>No</th>
                <th>Kategori</th>
                <th>Judul</th>
                <th>Penulis</th>
                <th>Tahun Terbit</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="isi-tabel">
            <tr><td colspan="6">Memuat data...</td></tr>
        </tbody>
    </table>

    <div class="paginasi">
        <button type="button" id="tombol-sebelumnya" class="tombol tombol-abu">Sebelumnya</button>
        <span id="info-halaman"></span>
        <button type="button" id="tombol-berikutnya" class="tombol tombol-abu">Berikutnya</button>
    </div>

    <script>
        const API = "{{ url('/api/buku') }}";
        const WEB = "{{ url('/buku') }}";   
        const HEADER = { 'Accept': 'application/json', 'Content-Type': 'application/json' };
        const FIELD = ['kategori_id', 'judul', 'penulis', 'tahun_terbit'];

        const form = document.getElementById('form-buku');
        const kotakPesan = document.getElementById('pesan');
        const isiTabel = document.getElementById('isi-tabel');
        const tombolSimpan = document.getElementById('tombol-simpan');
        const tombolBatal = document.getElementById('tombol-batal');
        const judulForm = document.getElementById('judul-form');
        const tombolSebelumnya = document.getElementById('tombol-sebelumnya');
        const tombolBerikutnya = document.getElementById('tombol-berikutnya');
        const infoHalaman = document.getElementById('info-halaman');

        let halaman = 1;       
        let daftarBuku = [];   
        let idEdit = null;     

        function tampilkanPesan(teks, jenis) {
            kotakPesan.textContent = teks;
            kotakPesan.className = 'pesan pesan-' + jenis;
            kotakPesan.hidden = false;
        }

        function bersihkanPesan() {
            kotakPesan.hidden = true;
            FIELD.forEach(f => document.getElementById('error-' + f).textContent = '');
        }

        async function panggilApi(url, opsi = {}) {
            let response;
            try {
                response = await fetch(url, { headers: HEADER, ...opsi });
            } catch (e) {
                throw new Error('API tidak dapat diakses. Periksa koneksi atau server.');
            }

            let data = null;
            try { data = await response.json(); } catch (e) { /* respons bukan JSON */ }

            if (!response.ok) {
                const error = new Error(data?.message || 'Permintaan gagal (status ' + response.status + ').');
                error.errors = data?.errors || null;
                throw error;
            }
            return data;
        }

        function buatSel(teks) {
            const td = document.createElement('td');
            td.textContent = teks;   
            return td;
        }

        function gambarTabel(daftar, meta) {
            isiTabel.innerHTML = '';

            if (daftar.length === 0) {
                const tr = document.createElement('tr');
                const td = buatSel('Belum ada data buku.');
                td.colSpan = 6;
                tr.appendChild(td);
                isiTabel.appendChild(tr);
            }

            daftar.forEach((buku, i) => {
                const tr = document.createElement('tr');
                tr.appendChild(buatSel((meta.from ?? 1) + i));
                tr.appendChild(buatSel(buku.kategori));
                tr.appendChild(buatSel(buku.judul));
                tr.appendChild(buatSel(buku.penulis));
                tr.appendChild(buatSel(buku.tahun));

                const tdAksi = document.createElement('td');

                const tautanDetail = document.createElement('a');
                tautanDetail.textContent = 'Detail';
                tautanDetail.href = WEB + '/' + buku.id;
                tautanDetail.className = 'tombol tombol-kecil tombol-biru';

                const tombolUbah = document.createElement('button');
                tombolUbah.textContent = 'Ubah';
                tombolUbah.className = 'tombol tombol-kecil';
                tombolUbah.addEventListener('click', () => mulaiUbah(buku));

                const tombolHapus = document.createElement('button');
                tombolHapus.textContent = 'Hapus';
                tombolHapus.className = 'tombol tombol-kecil tombol-merah';
                tombolHapus.addEventListener('click', () => hapusBuku(buku));

                tdAksi.append(tautanDetail, ' ', tombolUbah, ' ', tombolHapus);
                tr.appendChild(tdAksi);
                isiTabel.appendChild(tr);
            });

            infoHalaman.textContent = 'Halaman ' + meta.current_page + ' dari ' + meta.last_page;
            tombolSebelumnya.disabled = meta.current_page <= 1;
            tombolBerikutnya.disabled = meta.current_page >= meta.last_page;
        }

        async function muatBuku(page = 1) {
            try {
                const hasil = await panggilApi(API + '?page=' + page);

                if (hasil.data.length === 0 && page > 1) {
                    return muatBuku(page - 1);
                }

                halaman = hasil.meta.current_page;
                daftarBuku = hasil.data;
                gambarTabel(hasil.data, hasil.meta);
            } catch (e) {
                isiTabel.innerHTML = '';
                const tr = document.createElement('tr');
                const td = buatSel('Data tidak dapat ditampilkan.');
                td.colSpan = 6;
                tr.appendChild(td);
                isiTabel.appendChild(tr);
                tampilkanPesan('Gagal memuat data buku: ' + e.message, 'gagal');
            }
        }

        function aturModeTambah() {
            idEdit = null;
            form.reset();
            judulForm.textContent = 'Tambah Buku';
            tombolSimpan.textContent = 'Simpan';
            tombolBatal.hidden = true;
        }

        function mulaiUbah(buku) {
            bersihkanPesan();
            idEdit = buku.id;
            form.elements['kategori_id'].value = buku.kategori_id;
            form.elements['judul'].value = buku.judul;
            form.elements['penulis'].value = buku.penulis;
            form.elements['tahun_terbit'].value = buku.tahun;
            judulForm.textContent = 'Ubah Buku';
            tombolSimpan.textContent = 'Perbarui';
            tombolBatal.hidden = false;
            form.scrollIntoView({ behavior: 'smooth' });
        }

        async function simpanBuku(event) {
            event.preventDefault();
            bersihkanPesan();

            const payload = {};
            FIELD.forEach(f => payload[f] = form.elements[f].value);

            const modeUbah = idEdit !== null;
            tombolSimpan.disabled = true;   

            try {
                const hasil = await panggilApi(modeUbah ? API + '/' + idEdit : API, {
                    method: modeUbah ? 'PUT' : 'POST',
                    body: JSON.stringify(payload),
                });

                aturModeTambah();
                tampilkanPesan(hasil.message, 'sukses');
                await muatBuku(modeUbah ? halaman : 1);   
            } catch (e) {
                if (e.errors) {
                    Object.entries(e.errors).forEach(([field, pesan]) => {
                        const kotak = document.getElementById('error-' + field);
                        if (kotak) kotak.textContent = pesan[0];
                    });
                    tampilkanPesan('Data belum valid. Periksa isian yang ditandai.', 'gagal');
                } else {
                    tampilkanPesan('Gagal menyimpan: ' + e.message, 'gagal');
                }
            } finally {
                tombolSimpan.disabled = false;
            }
        }

        async function hapusBuku(buku) {
            if (!confirm('Hapus buku "' + buku.judul + '"?')) {
                return;   
            }

            bersihkanPesan();
            try {
                const hasil = await panggilApi(API + '/' + buku.id, { method: 'DELETE' });

                if (idEdit === buku.id) aturModeTambah();
                tampilkanPesan(hasil.message, 'sukses');
                await muatBuku(halaman);
            } catch (e) {
                tampilkanPesan('Gagal menghapus: ' + e.message, 'gagal');
            }
        }

        form.addEventListener('submit', simpanBuku);
        tombolBatal.addEventListener('click', () => { bersihkanPesan(); aturModeTambah(); });
        tombolSebelumnya.addEventListener('click', () => muatBuku(halaman - 1));
        tombolBerikutnya.addEventListener('click', () => muatBuku(halaman + 1));

        muatBuku(1);
    </script>

@endsection