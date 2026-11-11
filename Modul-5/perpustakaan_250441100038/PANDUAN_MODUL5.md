# Panduan Modul V — Autentikasi & RBAC (Sistem Perpustakaan Digital)

## Cara menjalankan (setelah file ini ditimpa ke proyek)
```bash
php artisan migrate            # menjalankan migration baru: user_id + status pada peminjamans
php artisan db:seed            # role, 16 permission, akun contoh, kategori, buku, contoh peminjaman
php artisan optimize:clear
php artisan permission:cache-reset
php artisan serve
php artisan test               # menjalankan semua test termasuk tests/Feature/RbacTest.php
```
Opsi reset total: `php artisan migrate:fresh --seed`

## Akun seeder (password semua: `password`)
| Role | Email |
|---|---|
| Admin | admin@test.com |
| Petugas | petugas@test.com |
| Siswa | siswa@test.com |
| Siswa 2 | siswa2@test.com |

## Pemetaan permission
| Permission | Admin | Petugas | Siswa |
|---|:-:|:-:|:-:|
| akses dashboard admin | ✓ | — | — |
| akses dashboard petugas | — | ✓ | — |
| lihat daftar buku | ✓ | ✓ | ✓ |
| kelola data buku | ✓ | ✓ | — |
| kelola data pengguna | ✓ | — | — |
| kelola role pengguna | ✓ | — | — |
| kelola peminjaman | ✓ | ✓ | — |
| kelola pengembalian | ✓ | ✓ | — |
| mengajukan peminjaman buku | ✓ | ✓ | ✓ |
| memproses persetujuan peminjaman | ✓ | ✓ | — |
| mencatat pengembalian buku | ✓ | ✓ | — |
| melihat status peminjaman sendiri | ✓ | ✓ | ✓ |
| melihat riwayat peminjaman sendiri | ✓ | ✓ | ✓ |
| lihat laporan perpustakaan | ✓ | ✓ | — |
| lihat profil sendiri | ✓ | ✓ | ✓ |
| ubah profil sendiri | ✓ | ✓ | ✓ |

## Peta route
| URL | Middleware | Keterangan |
|---|---|---|
| `/login`, `/register` | guest | user login diarahkan ke `/dashboard` |
| `/dashboard` | auth | redirect sesuai role |
| `/admin/dashboard` | auth + permission:akses dashboard admin | |
| `/petugas/dashboard` | auth + permission:akses dashboard petugas | |
| `/buku`, `/buku/{id}` | auth + permission:lihat daftar buku | semua role |
| `/api/buku` (POST/PUT/DELETE) | auth:sanctum + permission:kelola data buku | |
| `/admin/pengguna` (resource) | auth + permission:kelola data pengguna | ubah role butuh kelola role pengguna |
| `/peminjaman` | auth + permission:kelola peminjaman | setujui/tolak: memproses persetujuan peminjaman |
| `/pengembalian` | auth + permission:kelola pengembalian | catat: mencatat pengembalian buku |
| `/laporan` | auth + permission:lihat laporan perpustakaan | |
| `/riwayat` | auth + permission:melihat riwayat peminjaman sendiri | milik sendiri |
| `/riwayat/{user}` | auth + Gate `lihat-riwayat` | siswa hanya id sendiri, selain itu 403 |
| `/profile` | auth + permission lihat/ubah profil sendiri | tanpa ID di URL |

## Perubahan yang dilakukan pada proyek
- Perbaikan bug: `Models/Anggota.php` (nama kelas salah), `ProfileController` (nama kelas salah), seeder tidak dipanggil, pemetaan permission salah.
- Registrasi publik selalu memberi role `siswa`; input `role` diabaikan; `role` tidak ada di `$fillable`.
- Migration baru: `peminjamans.user_id` (nullable) dan `status`; `anggota_id` dibuat nullable.
- API buku memakai `permission:` (bukan `role:`).

## Tabel pengujian (isi kolom Hasil Aktual setelah uji manual / `php artisan test`)
| No | Role | Skenario | Diharapkan | Test otomatis | Hasil Aktual |
|---|---|---|---|---|---|
| 1 | Belum login | Buka dashboard admin | Ke halaman login | test_01 | |
| 2 | Admin | Buka dashboard admin | Dapat diakses | test_02 | |
| 3 | Admin | Tambah/ubah/hapus buku | Berhasil | test_03 | |
| 4 | Admin | Kelola akun & role | Berhasil | test_04 | |
| 5 | Admin | Peminjaman, pengembalian, laporan | Dapat diakses | test_05 | |
| 6 | Petugas | Dashboard petugas | Dapat diakses | test_06 | |
| 7 | Petugas | Kelola buku | Berhasil | test_07 | |
| 8 | Petugas | Proses peminjaman & pengembalian | Berhasil | test_08 | |
| 9 | Petugas | Kelola akun/role | Ditolak (403) | test_09 | |
| 10 | Siswa | Daftar & detail buku | Dapat dilihat | test_10 | |
| 11 | Siswa | Lihat & ubah profil sendiri | Berhasil | test_11 | |
| 12 | Siswa | Riwayat sendiri | Dapat dilihat | test_12 | |
| 13 | Siswa | Dashboard admin / kelola buku | Ditolak (403) | test_13 | |
| 14 | Siswa | Riwayat siswa lain | Ditolak (403) | test_14 | |
| 15 | Semua | Logout lalu buka halaman terlindungi | Harus login | test_15 | |

## Screenshot yang perlu diambil
Register, login (termasuk gagal login), dashboard admin, dashboard petugas, daftar buku siswa (tanpa tombol kelola), halaman 403, halaman kelola pengguna & ubah role, tabel `roles`/`permissions`/`role_has_permissions` di database, hasil `php artisan test`.
