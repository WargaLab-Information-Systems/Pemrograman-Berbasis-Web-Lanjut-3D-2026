# Tugas Praktikum Modul 5 — Autentikasi & RBAC (Sistem Perpustakaan Digital)

Proyek ini melanjutkan proyek Modul sebelumnya (struktur dan fitur lama dipertahankan),
lalu menambahkan autentikasi Laravel Breeze, RBAC dengan Spatie Laravel Permission,
dan alur peminjaman yang dibatasi sesuai role.

## Cara menjalankan
1. Salin `.env.example` menjadi `.env` (atau pakai `.env` yang ada), sesuaikan database.
2. `composer install` lalu `php artisan key:generate`
3. **Database baru:** `php artisan migrate --seed`
   **Database lama (sudah ada data modul sebelumnya):** `php artisan migrate`
   lalu `php artisan db:seed --class=RolePermissionSeeder`
   (migrasi baru otomatis mengisi `status` dan menghubungkan peminjaman lama ke akun user
   lewat kesamaan email anggota).
4. `npm install && npm run build` (folder `public/build` sudah disertakan)
5. `php artisan serve`

## Akun uji (RolePermissionSeeder)
| Role | Email | Password |
|---|---|---|
| Admin | admin@perpus.com | admin12345 |
| Petugas | petugas@perpus.com | petugas123 |
| Siswa | siswa@perpus.com | siswa12345 |
| Siswa (kedua, untuk uji akses data siswa lain) | siswa2@perpus.com | siswa12345 |

Ganti password akun contoh sebelum dipakai di lingkungan nyata.

## Ringkasan fitur per soal
| Soal | Implementasi |
|---|---|
| 1. Breeze | Registrasi, login, logout; password di-hash (`Hash::make` + cast `hashed`) |
| 2. Session & middleware | `auth` melindungi seluruh halaman internal, `guest` membatasi login/registrasi |
| 3. RBAC 3 role | Admin, Petugas, Siswa. Registrasi publik selalu menjadi `siswa`; role hanya diubah Admin |
| 4. Spatie | 3 role + 16 permission di `RolePermissionSeeder`, dipakai di route dan Blade |
| 5. Proteksi route/Blade | `permission:` / `role:` pada route, `@role` / `@can` pada menu dan tombol |

## Route penting
| URL | Permission |
|---|---|
| `/admin/dashboard` | akses dashboard admin |
| `/petugas/dashboard` | akses dashboard petugas |
| `/siswa/dashboard` | role siswa |
| `/buku`, `/buku/{id}` | lihat daftar buku |
| `/buku` (POST/PUT/DELETE), `/buku/{id}/edit` | kelola data buku |
| `/admin/users` | kelola data pengguna (role: kelola role pengguna) |
| `/peminjaman` | kelola peminjaman |
| `/peminjaman/{id}/setujui`, `/tolak` | memproses persetujuan peminjaman |
| `/pengembalian` | kelola pengembalian |
| `/peminjaman/{id}/kembalikan` | mencatat pengembalian buku |
| `/laporan` | lihat laporan perpustakaan |
| `/buku/{id}/ajukan` (POST) | mengajukan peminjaman buku |
| `/status-peminjaman` | melihat status peminjaman sendiri |
| `/riwayat-peminjaman` | melihat riwayat peminjaman sendiri |
| `/profil-saya` | lihat / ubah profil sendiri |
| `/api/buku`, `/api/kategori`, `/api/rekap/*` | sesuai permission yang sama |

## Alur peminjaman
`diajukan` (Siswa/Petugas/Admin mengajukan) → `dipinjam` (Petugas/Admin menyetujui) →
`dikembalikan` (Petugas/Admin mencatat pengembalian). Pengajuan dapat `ditolak`.
Buku yang sedang dipinjam tidak dapat diajukan atau disetujui ganda.

## Pembatasan data pribadi
Selain permission, identitas pengguna yang login selalu diperiksa:
- Riwayat & status peminjaman selalu di-query dengan `user_id = auth()->id()`.
- `/riwayat-peminjaman/{userId}` dan `/profil-saya/{userId}` hanya diterima jika sama dengan
  id pengguna yang login; selain itu **403**.
- Update/hapus profil selalu pada akun yang sedang login (bukan dari input form).
- Admin tidak dapat menghapus / mengubah role akunnya sendiri; admin terakhir tidak dapat menghapus akun.

## Perubahan dibanding revisi sebelumnya
- **Keamanan:** menutup akses riwayat dan profil milik pengguna lain.
- **Fitur baru:** pengajuan, persetujuan/penolakan, pengembalian, status peminjaman sendiri,
  laporan lengkap (ringkasan status, buku terpopuler, daftar peminjaman).
- **Database:** migrasi `2026_11_04_100000_add_status_and_user_to_peminjamans_table`
  menambah `user_id` dan `status`, dan membuat `anggota_id` nullable (data lama aman).
- **Perbaikan bug:** route `/api/*` yang memanggil method tidak ada, view `buku.create` yang tidak ada,
  kolom kategori (`nama`, bukan `nama_kategori`), `Buku::factory()` (trait `HasFactory` hilang
  sehingga `migrate --seed` gagal), validasi `tahun_terbit`, dashboard siswa tidak lagi
  menerima statistik pengguna lain, halaman peminjaman yang tadinya salinan halaman buku.
- **Tes:** `tests/Feature/SkenarioModul5Test.php` (15 skenario PDF + pengujian tambahan);
  tes Breeze bawaan disesuaikan dengan URL proyek (`/profil-saya`, registrasi → siswa).

## Menjalankan tes
`php artisan test` → 58 tes lolos (200 assertion), memakai SQLite in-memory.
Hasil per skenario ada di `PENGUJIAN_MODUL5.md`.
