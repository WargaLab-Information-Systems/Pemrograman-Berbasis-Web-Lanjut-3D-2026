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
        @can('manage books')
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
        @endcan
        @if (session('success'))
            <p class="alert-success" style="color: green; font-weight: bold; margin-bottom: 15px;" role="status">
                {{ session('success') }}
            </p>
        @endif

        @if (session('error'))
            <p class="alert-danger" style="color: red; font-weight: bold; margin-bottom: 15px;" role="status">
                {{ session('error') }}
            </p>
        @endif
        <p id="status-buku" role="status" aria-live="polite">Memuat data buku...</p>
        {{-- modul 6 --}}
        {{-- <div id="book-grid" class="book-grid"></div> --}}
        @can('kelola-buku')
            <div style="margin-bottom: 20px;">
                <a href="{{ route('buku.create') }}"
                    style="background-color: #4f46e5; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-weight: bold; display: inline-block;">
                    + Tambah Buku Baru
                </a>
            </div>
        @endcan
        <div id="book-grid" class="book-grid">
            @foreach ($books as $book)
                <div class="book-card"
                    style="border: 1px solid #ccc; padding: 15px; margin-bottom: 10px; border-radius: 8px;">
                    <span class="book-category">{{ $book->kategori->nama ?? 'Umum' }}</span>
                    <!-- Celah A1 -->
                    {{-- <h2>{!! $book->judul !!}</h2> --}}
                    {{-- soal nomor 2 perbaikan celah A1 --}}
                    <h2>{{ $book->judul }}</h2>
                    {{-- end soal nomor 2 perbaikan celah A1 --}}
                    <p>Penulis: {{ $book->penulis }}</p>
                    <p>Tahun: {{ $book->tahun_terbit }}</p>
                    <a href="{{ route('buku.show', $book->id) }}">Lihat Detail</a>
                    {{-- form delete soal nomor 2 --}}
                    @can('kelola-buku')
                        <form action="{{ route('buku.destroy', $book->id) }}" method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus buku ini?');" style="margin-top: 10px;">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                style="background-color: #ef4444; color: white; padding: 6px 12px; border-radius: 4px; border: none; cursor: pointer;">
                                Hapus Buku
                            </button>
                        </form>
                    @endcan
                    {{-- end form delete soal nomor 2 --}}
                </div>
            @endforeach
        </div>
        {{-- end modul 6 --}}
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const bolehKelolaBuku = @json(auth()->user()->can('manage books'));
            const bolehAjukanPeminjaman = @json(auth()->user()->can('request loan'));

            const bookGrid = document.getElementById('book-grid');
            const statusBuku = document.getElementById('status-buku');

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            async function tampilkanBuku() {
                statusBuku.textContent = 'Memuat data buku...';
                bookGrid.replaceChildren();

                try {
                    const response = await fetch('/api/buku', {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                        },
                        credentials: 'same-origin',
                    });

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
                        judul.innerHTML = book.judul;

                        const penulis = document.createElement('p');
                        penulis.className = 'book-author';
                        penulis.textContent = book.penulis;

                        const tahun = document.createElement('p');
                        tahun.className = 'book-year';
                        tahun.textContent = `Tahun Terbit: ${book.tahun_terbit}`;

                        content.append(kategori, judul, penulis, tahun);

                        const footer = document.createElement('div');
                        footer.className = 'book-card-footer';

                        const idBuku = document.createElement('small');
                        idBuku.textContent = `ID Buku: ${book.id}`;

                        footer.append(idBuku);
                        if (bolehAjukanPeminjaman) {
                            const formPinjam = document.createElement('form');
                            formPinjam.method = 'POST';
                            formPinjam.action = `/peminjaman/${book.id}`;

                            const inputCsrf = document.createElement('input');
                            inputCsrf.type = 'hidden';
                            inputCsrf.name = '_token';
                            inputCsrf.value = csrfToken;

                            const btnPinjam = document.createElement('button');
                            btnPinjam.type = 'submit';
                            btnPinjam.textContent = 'Ajukan Peminjaman';
                            btnPinjam.className = 'btn-pinjam';

                            formPinjam.append(inputCsrf, btnPinjam);
                            footer.append(formPinjam);
                        }

                        if (bolehKelolaBuku) {
                            const btnEdit = document.createElement('button');
                            btnEdit.type = 'button';
                            btnEdit.textContent = 'Edit';
                            btnEdit.className = 'btn-edit';

                            btnEdit.addEventListener('click', () => {
                                const formBuku = document.getElementById('form-buku');

                                document.getElementById('judul-form').textContent =
                                    'Edit Buku';

                                formBuku.elements['kategori_id'].value =
                                    book.kategori_id;

                                formBuku.elements['judul'].value = book.judul;
                                formBuku.elements['penulis'].value = book.penulis;

                                formBuku.elements['tahun_terbit'].value =
                                    book.tahun_terbit;

                                formBuku.dataset.editId = book.id;

                                document.getElementById('btn-simpan').textContent =
                                    'Simpan Perubahan';

                                document.getElementById('btn-batal').hidden = false;

                                document.getElementById('pesan-form').textContent =
                                    'Silakan ubah data buku.';

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

                                const pesanForm =
                                    document.getElementById('pesan-form');

                                pesanForm.textContent = 'Menghapus buku...';
                                btnHapus.disabled = true;

                                try {
                                    const response = await fetch(
                                        `/api/buku/${book.id}`, {
                                            method: 'DELETE',
                                            credentials: 'same-origin',
                                            headers: {
                                                'Accept': 'application/json',
                                                'X-CSRF-TOKEN': csrfToken,
                                            },
                                        }
                                    );

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
                                    console.error(
                                        'Kesalahan menghapus buku:',
                                        error
                                    );

                                    pesanForm.textContent = error.message;

                                } finally {
                                    btnHapus.disabled = false;
                                }
                            });

                            footer.append(btnEdit, btnHapus);
                        }

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

            if (bolehKelolaBuku) {
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
                        kategori_id: Number(
                            formBuku.elements['kategori_id'].value
                        ),
                        judul: formBuku.elements['judul'].value.trim(),
                        penulis: formBuku.elements['penulis'].value.trim(),
                        tahun_terbit: Number(
                            formBuku.elements['tahun_terbit'].value
                        ),
                    };

                    try {
                        const url = sedangEdit ?
                            `/api/buku/${editId}` :
                            '/api/buku';

                        const response = await fetch(url, {
                            method: sedangEdit ? 'PUT' : 'POST',
                            credentials: 'same-origin',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
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
            }
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
