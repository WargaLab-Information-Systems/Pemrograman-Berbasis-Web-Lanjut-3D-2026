@extends('layouts.app')

@section('judul', 'Daftar Buku')

@section('konten')
    <div class="halaman-judul">
        <h2>Daftar Buku</h2>
        <p id="jumlah-buku">Memuat data...</p>
    </div>

    <div id="pesan-status" class="pesan-status" style="display: none;"></div>

    <section class="kotak-form">
        <h3 id="judul-form">Tambah Buku</h3>
        <form id="form-buku">
            <input type="hidden" id="buku_id">

            <div class="grup-input">
                <label for="kategori_id">Kategori</label>
                <select id="kategori_id" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($kategoris as $kategori)
                        <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grup-input">
                <label for="judul">Judul</label>
                <input type="text" id="judul" placeholder="Masukkan judul buku" required>
            </div>

            <div class="grup-input">
                <label for="penulis">Penulis</label>
                <input type="text" id="penulis" placeholder="Masukkan nama penulis" required>
            </div>

            <div class="grup-input">
                <label for="tahun_terbit">Tahun Terbit</label>
                <input type="number" id="tahun_terbit" placeholder="Contoh: 2024" required>
            </div>

            <div class="aksi-form">
                <button type="submit" id="tombol-simpan" class="tombol-utama">Simpan</button>
                <button type="button" id="tombol-batal" class="tombol-sekunder" style="display: none;">Batal</button>
            </div>
        </form>
    </section>

    <div class="pembungkus-tabel">
        <table class="tabel-buku">
            <thead>
                <tr>
                    <th>Kategori</th>
                    <th>Judul</th>
                    <th>Penulis</th>
                    <th>Tahun Terbit</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="isi-tabel-buku">
                <tr>
                    <td colspan="5">Memuat data buku...</td>
                </tr>
            </tbody>
        </table>
    </div>

    <script>
        const isiTabel = document.getElementById('isi-tabel-buku');
        const jumlahBuku = document.getElementById('jumlah-buku');
        const pesanStatus = document.getElementById('pesan-status');
        const formBuku = document.getElementById('form-buku');
        const judulForm = document.getElementById('judul-form');
        const tombolSimpan = document.getElementById('tombol-simpan');
        const tombolBatal = document.getElementById('tombol-batal');

        const inputId = document.getElementById('buku_id');
        const inputKategori = document.getElementById('kategori_id');
        const inputJudul = document.getElementById('judul');
        const inputPenulis = document.getElementById('penulis');
        const inputTahun = document.getElementById('tahun_terbit');

        let dataBuku = [];

        function tampilkanPesan(teks, tipe = 'sukses') {
            pesanStatus.textContent = teks;
            pesanStatus.className = 'pesan-status ' + (tipe === 'gagal' ? 'pesan-gagal' : 'pesan-sukses');
            pesanStatus.style.display = 'block';
        }

        function resetForm() {
            formBuku.reset();
            inputId.value = '';
            judulForm.textContent = 'Tambah Buku';
            tombolSimpan.textContent = 'Simpan';
            tombolBatal.style.display = 'none';
        }

        // 1. Mengambil data buku dari GET /api/buku
        async function ambilDataBuku() {
            try {
                const response = await fetch('/api/buku', {
                    headers: { 'Accept': 'application/json' }
                });

                if (!response.ok) {
                    throw new Error('Gagal mengambil data buku dari server.');
                }

                const hasil = await response.json();
                dataBuku = hasil.data;

                jumlahBuku.textContent = dataBuku.length + ' buku';
                isiTabel.innerHTML = '';

                if (dataBuku.length === 0) {
                    isiTabel.innerHTML = '<tr><td colspan="5">Belum ada data buku.</td></tr>';
                    return;
                }

                dataBuku.forEach((buku) => {
                    const baris = document.createElement('tr');
                    baris.innerHTML = `
                        <td>${buku.kategori}</td>
                        <td>${buku.judul}</td>
                        <td>${buku.penulis}</td>
                        <td>${buku.tahun}</td>
                        <td class="kolom-aksi">
                            <button type="button" class="tombol-ubah" onclick="pilihBuku(${buku.id})">Ubah</button>
                            <button type="button" class="tombol-hapus" onclick="hapusBuku(${buku.id})">Hapus</button>
                        </td>
                    `;
                    isiTabel.appendChild(baris);
                });
            } catch (error) {
                isiTabel.innerHTML = '<tr><td colspan="5">Gagal memuat data buku.</td></tr>';
                tampilkanPesan(error.message, 'gagal');
            }
        }

        // 2 & 3. Menambah (POST) atau mengubah (PUT) data buku
        formBuku.addEventListener('submit', async function (event) {
            event.preventDefault();

            const id = inputId.value;
            const url = id ? `/api/buku/${id}` : '/api/buku';
            const method = id ? 'PUT' : 'POST';

            const payload = {
                kategori_id: inputKategori.value,
                judul: inputJudul.value,
                penulis: inputPenulis.value,
                tahun_terbit: inputTahun.value
            };

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                const hasil = await response.json();

                if (!response.ok) {
                    let pesanError = hasil.message || 'Terjadi kesalahan saat menyimpan data.';
                    if (hasil.errors) {
                        pesanError = Object.values(hasil.errors).flat().join(' ');
                    }
                    throw new Error(pesanError);
                }

                tampilkanPesan(id ? 'Data buku berhasil diubah.' : 'Data buku berhasil ditambahkan.');
                resetForm();
                await ambilDataBuku();
            } catch (error) {
                tampilkanPesan(error.message, 'gagal');
            }
        });

        // 3. Menampilkan data buku yang dipilih ke formulir untuk diubah
        function pilihBuku(id) {
            const buku = dataBuku.find((item) => item.id === id);
            if (!buku) return;

            inputId.value = buku.id;
            inputKategori.value = buku.kategori_id;
            inputJudul.value = buku.judul;
            inputPenulis.value = buku.penulis;
            inputTahun.value = buku.tahun;

            judulForm.textContent = 'Ubah Buku';
            tombolSimpan.textContent = 'Perbarui';
            tombolBatal.style.display = 'inline-block';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        tombolBatal.addEventListener('click', resetForm);

        // 4. Menghapus data buku melalui DELETE /api/buku/{id}
        async function hapusBuku(id) {
            const yakin = confirm('Apakah Anda yakin ingin menghapus buku ini?');
            if (!yakin) return;

            try {
                const response = await fetch(`/api/buku/${id}`, {
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json' }
                });

                const hasil = await response.json();

                if (!response.ok) {
                    throw new Error(hasil.message || 'Gagal menghapus buku.');
                }

                tampilkanPesan('Data buku berhasil dihapus.');
                if (inputId.value == id) {
                    resetForm();
                }
                await ambilDataBuku();
            } catch (error) {
                tampilkanPesan(error.message, 'gagal');
            }
        }

        document.addEventListener('DOMContentLoaded', ambilDataBuku);
    </script>
@endsection