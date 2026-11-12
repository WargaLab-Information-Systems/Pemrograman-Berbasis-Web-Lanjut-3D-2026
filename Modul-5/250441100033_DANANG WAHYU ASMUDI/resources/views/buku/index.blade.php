<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Buku - Perpustakaan Digital UTM</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col justify-between">

    <div>
        <header class="bg-[#111827] text-white shadow-md">
            <div class="max-w-6xl mx-auto px-6 py-4 flex flex-wrap justify-between items-center gap-4">
                <div>
                    <a href="/" class="text-2xl font-bold block hover:text-gray-200">Perpustakaan Digital UTM</a>
                    <nav class="flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-gray-300 mt-1">
                        <a href="/" class="hover:text-white">Beranda</a>
                        <a href="/buku" class="hover:text-white font-semibold text-white">Daftar Buku</a>
                        @auth
                            <a href="{{ route('dashboard') }}" class="hover:text-white">Dashboard</a>
                            @role('admin|petugas')
                                <a href="{{ route('peminjaman.index') }}" class="hover:text-white">Peminjaman</a>
                                <a href="{{ route('laporan.index') }}" class="hover:text-white">Laporan</a>
                            @endrole
                            @role('admin')
                                <a href="{{ route('admin.users.index') }}" class="hover:text-white">Kelola Pengguna</a>
                            @endrole
                            @role('siswa')
                                <a href="{{ route('peminjaman.riwayat') }}" class="hover:text-white">Riwayat Pinjam</a>
                            @endrole
                        @endauth
                    </nav>
                </div>

                <!-- Bagian Autentikasi (Modul 5) -->
                <div class="flex items-center space-x-3">
                    @auth
                        <span class="text-xs bg-gray-700 text-yellow-300 px-2.5 py-1 rounded font-medium">
                            Role: {{ Auth::user()->roles->pluck('name')->first() ?? 'User' }}
                        </span>
                        <a href="{{ route('profile.edit') }}" class="text-sm text-gray-200 hover:text-white underline">
                            Profil ({{ Auth::user()->name }})
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded text-sm transition font-medium">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm font-medium transition">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded text-sm font-medium transition">
                            Register
                        </a>
                    @endauth
                </div>
            </div>
        </header>

        <main class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow-md mt-8">
            <h1 class="text-2xl font-bold mb-6 text-gray-800">Manajemen Data Buku (Konsumsi API)</h1>

            <div id="alert-box" class="hidden mb-4 p-4 rounded text-white font-medium"></div>

            <!-- Form Tambah/Edit Buku: Hanya muncul untuk Admin & Petugas (Modul 5 RBAC) -->
            @role('admin|petugas')
            <form id="buku-form" class="mb-8 grid grid-cols-1 md:grid-cols-5 gap-4 bg-gray-50 p-4 rounded border">
                <input type="hidden" id="buku-id">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kategori</label>
                    <select id="kategori_id" required class="mt-1 block w-full rounded border-gray-300 shadow-sm p-2 border bg-white">
                        <option value="">Pilih Kategori</option>
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat->id }}">{{ $kat->nama ?? $kat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Judul Buku</label>
                    <input type="text" id="judul" required class="mt-1 block w-full rounded border-gray-300 shadow-sm p-2 border">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Penulis</label>
                    <input type="text" id="penulis" required class="mt-1 block w-full rounded border-gray-300 shadow-sm p-2 border">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tahun Terbit</label>
                    <input type="number" id="tahun_terbit" required class="mt-1 block w-full rounded border-gray-300 shadow-sm p-2 border">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" id="btn-simpan" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 w-full transition">Simpan</button>
                    <button type="button" id="btn-batal" onclick="resetForm()" class="hidden bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">Batal</button>
                </div>
            </form>
            @endrole

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Penulis</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahun</th>
                            
                            <!-- Kolom Aksi hanya muncul untuk Admin & Petugas -->
                            @role('admin|petugas')
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            @endrole
                        </tr>
                    </thead>
                    <tbody id="buku-table-body" class="bg-white divide-y divide-gray-200">
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <footer class="bg-[#111827] text-white text-center py-4 mt-8">
        <p class="text-sm">&copy; 2026 Perpustakaan Digital. All rights reserved.</p>
    </footer>

    <script>
        const API_URL = '/api/buku';
        let daftarBuku = [];
        
        // Cek apakah user memiliki hak kelola berdasarkan keberadaan form di DOM
        const canManage = document.getElementById('buku-form') !== null;

        async function loadBuku() {
            try {
                let response = await fetch(API_URL);
                if (!response.ok) throw new Error('Gagal mengambil data dari server.');
                
                let result = await response.json();
                daftarBuku = result.data || []; 
                let tbody = document.getElementById('buku-table-body');
                tbody.innerHTML = '';

                daftarBuku.forEach(buku => {
                    let namaKategori = buku.nama_kategori || buku.kategori?.nama || buku.kategori?.nama_kategori || 'Tanpa Kategori';

                    let actionColumn = '';
                    if (canManage) {
                        actionColumn = '<td style="padding:12px 24px;text-align:center;">'
                            + '<button onclick="editBuku(' + buku.id + ')" style="background:#ca8a04;color:white;padding:4px 10px;border-radius:4px;border:none;cursor:pointer;margin-right:4px;">Ubah</button>'
                            + '<button onclick="hapusBuku(' + buku.id + ')" style="background:#dc2626;color:white;padding:4px 10px;border-radius:4px;border:none;cursor:pointer;">Hapus</button>'
                            + '</td>';
                    }

                    tbody.innerHTML += '<tr>'
                        + '<td style="padding:12px 24px;">' + namaKategori + '</td>'
                        + '<td style="padding:12px 24px;"><a href="/buku/' + buku.id + '" style="color:#2563eb;text-decoration:underline;font-weight:500;">' + buku.judul + '</a></td>'
                        + '<td style="padding:12px 24px;">' + buku.penulis + '</td>'
                        + '<td style="padding:12px 24px;">' + buku.tahun_terbit + '</td>'
                        + actionColumn
                        + '</tr>';
                });
            } catch (error) {
                showAlert(error.message, 'error');
            }
        }

        const bukuForm = document.getElementById('buku-form');
        if (bukuForm) {
            bukuForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                let id = document.getElementById('buku-id').value;
                let payload = {
                    kategori_id: document.getElementById('kategori_id').value,
                    judul: document.getElementById('judul').value,
                    penulis: document.getElementById('penulis').value,
                    tahun_terbit: document.getElementById('tahun_terbit').value
                };

                let url = id ? `${API_URL}/${id}` : API_URL;
                let method = id ? 'PUT' : 'POST';

                try {
                    let response = await fetch(url, {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    });

                    let result = await response.json();

                    if (!response.ok) {
                        let errorMsg = result.message || 'Terjadi kesalahan validasi.';
                        if (result.errors) {
                            errorMsg = Object.values(result.errors).flat().join('<br>');
                        }
                        throw new Error(errorMsg);
                    }

                    showAlert(result.message || 'Data berhasil disimpan!', 'success');
                    resetForm();
                    loadBuku(); 
                } catch (error) {
                    showAlert(error.message, 'error');
                }
            });
        }

        function editBuku(id) {
            let buku = daftarBuku.find(item => item.id == id);
            if (!buku) return;

            document.getElementById('buku-id').value = buku.id;
            document.getElementById('kategori_id').value = buku.kategori_id;
            document.getElementById('judul').value = buku.judul;
            document.getElementById('penulis').value = buku.penulis;
            document.getElementById('tahun_terbit').value = buku.tahun_terbit;
            
            document.getElementById('btn-simpan').innerText = 'Perbarui';
            document.getElementById('btn-batal').classList.remove('hidden');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        async function hapusBuku(id) {
            if (!confirm('Apakah kamu yakin ingin menghapus buku ini?')) return;

            try {
                let response = await fetch(`${API_URL}/${id}`, {
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json' }
                });

                if (!response.ok) throw new Error('Gagal menghapus data.');

                let result = await response.json();
                showAlert(result.message || 'Data berhasil dihapus', 'success');
                loadBuku(); 
            } catch (error) {
                showAlert(error.message, 'error');
            }
        }

        function resetForm() {
            let form = document.getElementById('buku-form');
            if (form) {
                form.reset();
                document.getElementById('buku-id').value = '';
                document.getElementById('btn-simpan').innerText = 'Simpan';
                document.getElementById('btn-batal').classList.add('hidden');
            }
        }

        function showAlert(message, type) {
            let box = document.getElementById('alert-box');
            if (!box) return;
            box.classList.remove('hidden', 'bg-red-500', 'bg-green-500');
            box.classList.add(type === 'error' ? 'bg-red-500' : 'bg-green-500');
            box.innerHTML = message;

            setTimeout(() => {
                box.classList.add('hidden');
            }, 5000);
        }

        loadBuku();
    </script>
</body>
</html>
