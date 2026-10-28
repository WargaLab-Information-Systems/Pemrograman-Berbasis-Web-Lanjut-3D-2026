@extends('layouts.app')
@section('title', 'Daftar Buku')
@section('content')
    <div class="book-page">
        <div class="page-header" data-aos="fade-down" data-aos-duration="800" data-aos-delay="100">
            <span class="page-label">KOLEKSI PERPUSTAKAAN</span>
            <h1>Daftar Buku</h1>
            <p>Jelajahi koleksi buku yang tersedia di perpustakaan.</p>
        </div>
        {{-- modul 4 --}}
        <div class="book-form-wrapper" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
            <h2 id="judul-form">Tambah Buku</h2>
            <form id="form-buku">
                <div>
                    <label for="kategori_id">ID Kategori</label>
                    <input type="number" id="kategori_id" name="kategori_id" min="1" required>
                </div>
                <div>
                    <label for="judul">Judul Buku</label>
                    <input type="text" id="judul" name="judul" required>
                </div>
                <div>
                    <label for="penulis">Penulis</label>
                    <input type="text" id="penulis" name="penulis" required>
                </div>
                <div>
                    <label for="tahun_terbit">Tahun Terbit</label>
                    <input type="number" id="tahun_terbit" name="tahun_terbit" min="1" required>
                </div>
                <button type="submit" id="btn-simpan">Simpan Buku</button>
                <button type="button" id="btn-batal" hidden>Batal Edit</button>
            </form>
            <p id="pesan-form" role="status" aria-live="polite"></p>
        </div>
        <p id="status-buku" role="status" aria-live="polite">Memuat data buku...</p>
        <div id="book-grid" class="book-grid"></div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const bookGrid = document.getElementById('book-grid');
            const statusBuku = document.getElementById('status-buku');
            async function tampilkanBuku() {
                statusBuku.textContent = 'Memuat data buku...';
                bookGrid.replaceChildren();
                try {
                    const response = await fetch('/api/buku');
                    if (!response.ok) {
                        throw new Error('Gagal mengambil data buku.');
                    }
                    const hasil = await response.json();
                    const books = hasil.data;
                    if (!Array.isArray(books) || books.length === 0) {
                        statusBuku.textContent = 'Belum ada data buku.';
                        return;
                    }
                    books.forEach((book, index) => {
                        const card = document.createElement('div');
                        card.className = 'book-card';
                        card.setAttribute('data-aos', 'fade-up');
                        card.setAttribute('data-aos-duration', '800');
                        card.setAttribute(
                            'data-aos-delay',
                            String(Math.min(index * 150, 600))
                        );
                        const content = document.createElement('div');
                        content.className = 'book-card-content';
                        const kategori = document.createElement('span');
                        kategori.className = 'book-category';
                        kategori.textContent =
                            book.kategori?.nama ?? 'Tanpa kategori';
                        const judul = document.createElement('h2');
                        judul.textContent = book.judul;
                        const penulis = document.createElement('p');
                        penulis.className = 'book-author';
                        penulis.textContent = book.penulis;
                        const tahun = document.createElement('p');
                        tahun.className = 'book-year';
                        tahun.textContent =
                            `Tahun Terbit: ${book.tahun_terbit}`;
                        content.append(kategori, judul, penulis, tahun);
                        const footer = document.createElement('div');
                        footer.className = 'book-card-footer';
                        const idBuku = document.createElement('small');
                        idBuku.textContent = `ID Buku: ${book.id}`;
                        const btnEdit = document.createElement('button');
                        btnEdit.type = 'button';
                        btnEdit.textContent = 'Edit';
                        btnEdit.className = 'btn-edit';
                        btnEdit.addEventListener('click', () => {
                            document.getElementById('judul-form').textContent = 'Edit Buku';
                            formBuku.elements['kategori_id'].value = book.kategori_id;
                            formBuku.elements['judul'].value = book.judul;
                            formBuku.elements['penulis'].value = book.penulis;
                            formBuku.elements['tahun_terbit'].value = book.tahun_terbit;
                            formBuku.dataset.editId = book.id;
                            btnSimpan.textContent = 'Simpan Perubahan';
                            document.getElementById('btn-batal').hidden = false;
                            pesanForm.textContent = 'Silakan ubah data buku.';
                            formBuku.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
                        });

                        const btnHapus = document.createElement('button');
                        btnHapus.type = 'button';
                        btnHapus.textContent = 'Hapus';
                        btnHapus.className = 'btn-hapus';

                        btnHapus.addEventListener('click', async () => {
                            const yakin = confirm(
                                `Apakah kamu yakin ingin menghapus buku "${book.judul}"?`
                            );

                            if (!yakin) {
                                return;
                            }

                            pesanForm.textContent = 'Menghapus buku...';
                            btnHapus.disabled = true;

                            try {
                                const response = await fetch(`/api/buku/${book.id}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'Accept': 'application/json',
                                    },
                                });

                                const hasil = await response.json();

                                if (!response.ok) {
                                    throw new Error(
                                        hasil.message || 'Gagal menghapus buku.'
                                    );
                                }

                                pesanForm.textContent =
                                    hasil.message || 'Buku berhasil dihapus.';

                                await tampilkanBuku();
                            } catch (error) {
                                console.error('Kesalahan menghapus buku:', error);
                                pesanForm.textContent = error.message;
                            } finally {
                                btnHapus.disabled = false;
                            }
                        });

                        footer.append(idBuku, btnEdit, btnHapus);

                        card.append(content, footer);
                        bookGrid.appendChild(card);

                    });
                    statusBuku.textContent =
                        `Berhasil menampilkan ${books.length} buku pada halaman ini.`;
                    if (window.AOS) {
                        window.AOS.refresh();
                    }
                } catch (error) {
                    console.error('Kesalahan mengambil data buku:', error);
                    statusBuku.textContent =
                        'Gagal memuat data buku. Silakan coba lagi.';
                }
            }
            const formBuku = document.getElementById('form-buku');
            const pesanForm = document.getElementById('pesan-form');
            const btnSimpan = document.getElementById('btn-simpan');
            const btnBatal = document.getElementById('btn-batal');
            const judulForm = document.getElementById('judul-form');
            btnBatal.addEventListener('click', () => {
                formBuku.reset();
                delete formBuku.dataset.editId;
                judulForm.textContent = 'Tambah Buku';
                btnSimpan.textContent = 'Simpan Buku';
                btnBatal.hidden = true;
                pesanForm.textContent = '';
            });

            formBuku.addEventListener('submit', async (event) => {
                event.preventDefault();
                const editId = formBuku.dataset.editId;
                const sedangEdit = Boolean(editId);
                pesanForm.textContent = sedangEdit ?
                    'Menyimpan perubahan...' :
                    'Menyimpan buku...';
                btnSimpan.disabled = true;
                btnBatal.disabled = true;
                const dataBuku = {
                    kategori_id: Number(formBuku.elements['kategori_id'].value),
                    judul: formBuku.elements['judul'].value.trim(),
                    penulis: formBuku.elements['penulis'].value.trim(),
                    tahun_terbit: Number(formBuku.elements['tahun_terbit'].value),
                };
                try {
                    const url = sedangEdit ?
                        `/api/buku/${editId}` :
                        '/api/buku';
                    const response = await fetch(url, {
                        method: sedangEdit ? 'PUT' : 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify(dataBuku),
                    });
                    const hasil = await response.json();
                    if (!response.ok) {
                        const pesanError = hasil.errors ?
                            Object.values(hasil.errors).flat().join(' ') :
                            hasil.message || 'Gagal menyimpan data buku.';
                        throw new Error(pesanError);
                    }
                    pesanForm.textContent = sedangEdit ?
                        'Buku berhasil diperbarui.' :
                        'Buku berhasil ditambahkan.';
                    formBuku.reset();
                    delete formBuku.dataset.editId;
                    judulForm.textContent = 'Tambah Buku';
                    btnSimpan.textContent = 'Simpan Buku';
                    btnBatal.hidden = true;
                    await tampilkanBuku();
                } catch (error) {
                    console.error('Kesalahan menyimpan buku:', error);
                    pesanForm.textContent = error.message;
                } finally {
                    btnSimpan.disabled = false;
                    btnBatal.disabled = false;
                }
            });

            tampilkanBuku();
        });
    </script>
    {{-- end modul 4 --}}
    {{-- nampilin data buku modul 1-3 --}}
    {{-- <div class="book-grid">
        @foreach ($books as $book)
            <x-buku-card :book="$book" data-aos="fade-up" data-aos-duration="800" data-aos-delay="{{ min($loop->index * 150, 600) }}">
                <x-slot:footer>
                    <small>ID Buku: {{ $book['id'] }}</small>
                </x-slot:footer>
            </x-buku-card>
        @endforeach
    </div> --}}
    {{-- end nampilin data buku modul 1-3 --}}
@endsection
