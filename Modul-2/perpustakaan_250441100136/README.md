# Sistem Informasi Perpustakaan (NIM: 250441100136)

Aplikasi web sistem informasi perpustakaan sederhana yang dibangun menggunakan framework **Laravel 11** dan **PHP 8.x**.

---

## 📌 Deskripsi Proyek

Aplikasi Sistem Informasi Perpustakaan ini dibuat untuk menampilkan katalog koleksi buku perpustakaan. Proyek ini memuat fungsionalitas beranda utama, daftar katalog buku, serta tampilan detail informasi tiap buku.

---

## 🚀 Fitur Utama

- **Beranda (Home):** Halaman utama ucapan selamat datang dan informasi umum perpustakaan.
- **Daftar Buku (Katalog):** Menampilkan seluruh koleksi buku yang tersedia dalam bentuk kartu/daftar.
- **Detail Buku:** Menampilkan rincian data buku seperti judul, penulis, tahun terbit, kategori, dan deskripsi lengkap berdasarkan ID buku.

---

## 📁 Struktur Direktori Penting

```text
perpustakaan_250441100136/
├── app/
│   └── Http/
│       └── Controllers/
│           └── BukuController.php    # Pengendali logika data & tampilan buku
├── resources/
│   └── views/
│       ├── home.blade.php            # Tampilan Halaman Utama
│       └── buku/
│           ├── index.blade.php       # Tampilan Daftar Buku
│           └── show.blade.php        # Tampilan Detail Buku
├── routes/
│   └── web.php                       # Definisi Route Web
└── README.md                         # Dokumentasi Proyek
```

---

## 🛠️ Panduan Instalasi & Jalankan Proyek

### 1. Prasyarat
- PHP >= 8.2
- Composer
- Laragon / XAMPP (Web Server)

### 2. Langkah-Langkah

1. **Clone / Buka Repositori**
   Pastikan direktori proyek sudah berada di folder web server Anda (misal `C:\laragon\www\perpustakaan_250441100136`).

2. **Instal Depedensi Composer**
   ```bash
   composer install
   ```

3. **Pengaturan Environment**
   Salin file `.env.example` menjadi `.env` jika belum ada:
   ```bash
   cp .env.example .env
   ```
   Generate application key:
   ```bash
   php artisan key:generate
   ```

4. **Jalankan Server Lokal**
   Gunakan Artisan serve untuk menjalankan server pengembangan:
   ```bash
   php artisan serve
   ```
   Buka browser dan akses `http://127.0.0.1:8000`.

---

## 🛣️ Daftar Route API / Web

| Method | URI | Route Name | Deskripsi |
| --- | --- | --- | --- |
| `GET` | `/` | `home` | Halaman utama / beranda |
| `GET` | `/buku` | `buku.index` | Halaman daftar semua buku |
| `GET` | `/buku/{id}` | `buku.show` | Halaman detail buku berdasarkan ID |

---

## 📝 Lisensi

Proyek ini dibuat untuk keperluan akademik/tugas perkuliahan.
