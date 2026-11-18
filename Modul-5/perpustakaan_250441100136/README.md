# 📚 Sistem Informasi Perpustakaan (NIM: 250441100136)

Aplikasi web Sistem Informasi Perpustakaan berbasis framework **Laravel 11** dan **PHP 8.2+**. Proyek ini dirancang untuk mengelola dan menyajikan informasi katalog buku perpustakaan secara interaktif, terstruktur, dan responsif.

---

## 📌 Gambaran Umum Proyek

Aplikasi ini menyajikan sistem manajemen dan katalogisasi koleksi buku perpustakaan. Pengguna dapat melihat daftar buku, mencari/memfilter berdasarkan kategori, serta melihat detail lengkap tiap buku seperti pengarang, penerbit, tahun terbit, stok ketersediaan, serta sinopsis.

### 🌟 Fitur Utama
1. **Beranda (Home):** Tampilan selamat datang dengan informasi profil perpustakaan dan navigasi utama.
2. **Katalog Buku (Daftar Buku):** Menampilkan seluruh koleksi buku yang tersedia dalam grid/kartu interaktif dengan informasi kategori.
3. **Detail Informasi Buku:** Menampilkan rincian buku lengkap berdasarkan ID (judul, penulis/pengarang, kategori, tahun terbit, stok, dan deskripsi/sinopsis).
4. **Relasi Data Lengkap:** Struktur database telah mendukung relasi Buku, Kategori, Anggota, Petugas, dan Peminjaman.

---

## 🏗️ Arsitektur & Struktur Proyek

```text
perpustakaan_250441100136/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── Controller.php
│   │       └── BukuController.php          # Controller logika katalog & detail buku
│   └── Models/
│       ├── Anggota.php                     # Model data anggota perpustakaan
│       ├── Buku.php                        # Model data buku & relasinya ke Kategori
│       ├── Kategori.php                    # Model kategori buku
│       ├── Peminjaman.php                  # Model transaksi peminjaman buku
│       ├── Petugas.php                     # Model data petugas perpustakaan
│       └── User.php                        # Model autentikasi bawaan Laravel
├── database/
│   ├── migrations/                         # Skema database & tabel relasional
│   └── seeders/                            # Data awal / dummy seeder
├── resources/
│   └── views/
│       ├── components/
│       │   └── buku-card.blade.php         # Komponen reusable kartu buku
│       ├── layouts/
│       │   └── main.blade.php              # Layout induk aplikasi (navbar, footer, yield content)
│       ├── buku/
│       │   ├── index.blade.php             # Tampilan katalog buku
│       │   └── show.blade.php              # Tampilan detail buku
│       └── home.blade.php                  # Tampilan beranda utama
├── routes/
│   └── web.php                             # Rute navigasi halaman web
├── artisan                                 # CLI tool Laravel
├── composer.json                           # Dependensi backend PHP
├── package.json                            # Dependensi frontend & Vite
└── README.md                               # Dokumentasi ringkas proyek
```

---

## 🗄️ Skema Database & Relasi

Aplikasi ini memiliki beberapa entitas utama:
- **`bukus`**: Menyimpan informasi buku (`judul`, `pengarang`, `penerbit`, `tahun_terbit`, `stok`, `deskripsi`, `kategori_id`).
- **`kategoris`**: Menyimpan kategori genre/klasifikasi buku (`nama_kategori`).
- **`anggotas`**: Data anggota perpustakaan (`nim`, `nama`, `jurusan`, `no_telp`).
- **`petugas`**: Data petugas/administrator perpustakaan.
- **`peminjamans`**: Mencatat riwayat peminjaman (`anggota_id`, `buku_id`, `tanggal_pinjam`, `tanggal_kembali`, `status`).

---

## 🛣️ Rute Aplikasi (Routing)

| Method | Endpoint / URL | Route Name | Controller & Method | Keterangan |
| :--- | :--- | :--- | :--- | :--- |
| `GET` | `/` | `home` | `Closure` | Menampilkan Halaman Utama (Home) |
| `GET` | `/buku` | `buku.index` | `BukuController@index` | Menampilkan Daftar Koleksi Buku |
| `GET` | `/buku/{id}` | `buku.show` | `BukuController@show` | Menampilkan Detail Informasi Buku berdasarkan ID |

---

## 💻 Panduan Instalasi & Menjalankan Proyek

### 1. Prasyarat Sistem
- **PHP** versi 8.2 atau lebih tinggi
- **Composer** (Package Manager PHP)
- **Node.js & NPM** (untuk build asset Vite / Tailwind)
- **Database Server** (MySQL/MariaDB via Laragon, XAMPP, dsb.)

### 2. Langkah Instalasi

1. **Pastikan berada di direktori proyek:**
   ```bash
   cd C:\laragon\www\perpustakaan_250441100136
   ```

2. **Instal dependensi Composer:**
   ```bash
   composer install
   ```

3. **Konfigurasi Environment (`.env`):**
   Salin `.env.example` ke `.env` (jika belum ada file `.env`):
   ```bash
   copy .env.example .env
   ```
   Generate application key:
   ```bash
   php artisan key:generate
   ```

4. **Konfigurasi Database:**
   Sesuaikan konfigurasi database di file `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=perpustakaan_250441100136
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Jalankan Migrasi Database:**
   ```bash
   php artisan migrate
   ```
   *(Opsional)* Jalankan seeder jika tersedia:
   ```bash
   php artisan db:seed
   ```

6. **Instal Dependensi Frontend & Jalankan Asset:**
   ```bash
   npm install
   npm run build
   # atau untuk development:
   # npm run dev
   ```

7. **Jalankan Web Server:**
   ```bash
   php artisan serve
   ```
   Buka peramban (browser) dan akses: [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## 🧪 Pengujian (Testing)

Jalankan test suite menggunakan PHPUnit:
```bash
php artisan test
```
atau
```bash
vendor/bin/phpunit
```

---

## 👨‍💻 Identitas Pembuat
- **NIM:** 250441100136  
- **Proyek:** Sistem Informasi Perpustakaan  
- **Framework:** Laravel 11 (PHP 8.2+)
