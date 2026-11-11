const formTambahBuku = document.getElementById('form-tambah-buku');
const pesanTambahBuku = document.getElementById('pesan-tambah-buku');
const judulForm = document.getElementById('judul-form');
const tombolSimpan = document.getElementById('tombol-simpan');
const tombolBatal = document.getElementById('tombol-batal');

let bukuSedangDiedit = null;

formTambahBuku.addEventListener('submit', async function(event) {
    event.preventDefault();

    const dataBuku = {
        judul: document.getElementById('judul').value,
        penulis: document.getElementById('penulis').value,
        tahun_terbit: Number(document.getElementById('tahun_terbit').value),
        kategori_id: Number(document.getElementById('kategori_id').value)
    };

    const sedangMengubah = bukuSedangDiedit !== null;
    const url = sedangMengubah
        ? `/api/buku/${bukuSedangDiedit}`
        : '/api/buku';

        try {
            const response = await fetch(url, {
                method: sedangMengubah ? 'PUT' : 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                
                body: JSON.stringify(dataBuku)
                });

                const hasil = await response.json();

                if (!response.ok) {
                    const detailError = hasil.errors
                        ? Object.values(hasil.errors).flat().join(' ')
                        : hasil.message;

                    throw new Error(detailError || 'Gagal menyimpan buku.');
                }

                pesanTambahBuku.textContent = sedangMengubah
                    ? 'Buku berhasil diubah!'
                    : 'Buku berhasil ditambahkan!';

                formTambahBuku.reset();
                bukuSedangDiedit = null;
                judulForm.textContent = 'Tambah Buku Baru';
                tombolSimpan.textContent = 'Tambah Buku';
                tombolBatal.hidden = true;

                await ambilDataBuku();

            } catch (error) {
                pesanTambahBuku.textContent = 'Gagal: ' + error.message;
            }
        });

        tombolBatal.addEventListener('click', function() {
            formTambahBuku.reset();
            bukuSedangDiedit = null;
            judulForm.textContent = 'Tambah Buku Baru';
            tombolSimpan.textContent = 'Tambah Buku';
            tombolBatal.hidden = true;
            pesanTambahBuku.textContent = '';
        });

        async function ambilDataBuku() {
            const status = document.getElementById('status-buku');
            const daftarBuku = document.getElementById('daftar-buku');

            try {
                const response = await fetch('/api/buku', {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error('Gagal mengambil data buku.');
                }

                const hasil = await response.json();
                
                console.log('Respons API:', hasil);
                console.log('Data buku pertama:', hasil.data?.[0]);

                const buku = hasil.data;

                daftarBuku.innerHTML = '';

                buku.forEach(item => {
                    const baris = document.createElement('tr');

                    [
                        item.id,
                        item.kategori,
                        item.judul,
                        item.penulis,
                        item.tahun
                    ].forEach(nilai => {        
                        const kolom = document.createElement('td');
                        kolom.textContent = nilai ?? '-';
                        baris.appendChild(kolom);
                    });

                    const kolomAksi = document.createElement('td');
                    const tombolUbah = document.createElement('button');

                    tombolUbah.type = 'button';
                    tombolUbah.textContent = 'Ubah';
                    tombolUbah.className = 'btn-ubah';

                    tombolUbah.addEventListener('click', function() {
                        bukuSedangDiedit = item.id;

                        // Isi form dengan data buku yang dipilih
                        document.getElementById('judul').value = item.judul;
                        document.getElementById('penulis').value = item.penulis;
                        document.getElementById('tahun_terbit').value = item.tahun;

                        ambilDetailBukuUntukEdit(item.id);
                    });

                    kolomAksi.appendChild(tombolUbah);

                    const tombolHapus = document.createElement('button');
                    tombolHapus.type = 'button';
                    tombolHapus.textContent = 'Hapus';
                    tombolHapus.className = 'btn-hapus';

                    tombolHapus.addEventListener('click', async function() {

                        const yakin = confirm(
                            `Apakah kamu yakin ingin menghapus buku "${item.judul}"?`
                        );

                        if (!yakin) {
                            return;
                        }

                        try {
                            const response = await fetch(`/api/buku/${item.id}`, {
                                method: 'DELETE',
                                headers: {
                                    'Accept': 'application/json'
                                }
                            });

                            if (!response.ok) {
                                const hasil = await response.json();
                                throw new Error(
                                    hasil.message || 'Gagal menghapus buku.'
                                );
                            }

                            pesanTambahBuku.textContent = 'Buku berhasil dihapus!';

                            // Jika buku yang dihapus sedang diedit, reset formulir
                            if (bukuSedangDiedit === item.id) {
                                formTambahBuku.reset();
                                bukuSedangDiedit = null;
                                judulForm.textContent = 'Tambah Buku Baru';
                                tombolSimpan.textContent = 'Tambah Buku';
                                tombolBatal.hidden = true;
                            }

                            // Perbarui tabel
                            await ambilDataBuku();

                        } catch (error) {
                            pesanTambahBuku.textContent = 'Gagal: ' + error.message;
                        }
                    });

                    kolomAksi.appendChild(tombolHapus);
                    baris.appendChild(kolomAksi);
                    daftarBuku.appendChild(baris);
                });

                status.textContent =
                    `Berhasil memuat ${buku.length} buku pada halaman ini.`;

            } catch (error) {
                status.textContent = error.message;
            }
        }

        // Mengambil detail buku agar kategori_id yang benar terisi
        async function ambilDetailBukuUntukEdit(id) {
            try {
                const response = await fetch(`/api/buku/${id}`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error('Gagal mengambil detail buku.');
                }

                const hasil = await response.json();
                const data = hasil.data ?? hasil;

                document.getElementById('judul').value =
                    data.judul ?? data.judul_buku ?? '';
                document.getElementById('penulis').value =
                    data.penulis ?? data.nama_penulis ?? '';
                document.getElementById('tahun_terbit').value =
                    data.tahun_terbit ?? data.tahun ?? '';
                document.getElementById('kategori_id').value =
                    data.kategori_id ?? '';

                judulForm.textContent = 'Ubah Data Buku';
                tombolSimpan.textContent = 'Simpan Perubahan';
                tombolBatal.hidden = false;
                pesanTambahBuku.textContent = '';

                // Arahkan tampilan ke form
                formTambahBuku.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });

            } catch (error) {
                pesanTambahBuku.textContent = 'Gagal: ' + error.message;
            }
        }

        ambilDataBuku();