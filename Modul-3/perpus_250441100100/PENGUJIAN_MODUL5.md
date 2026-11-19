# Hasil Pengujian — Tugas Praktikum Modul 5

Pengujian dijalankan otomatis dengan `php artisan test` (file `tests/Feature/SkenarioModul5Test.php`,
satu metode per skenario). Hasil: **58 tes lolos, 0 gagal**.
Disarankan tetap mengulang pengujian manual di browser memakai akun uji (lihat README_MODUL5.md)
dan menambahkan tangkapan layar sebagai bukti.

| No | Role Pengguna | Skenario Pengujian | Hasil yang Diharapkan | Hasil Aktual |
|---|---|---|---|---|
| 1 | Belum login | Membuka halaman dashboard admin | Dialihkan ke halaman login | Sesuai: redirect ke `/login` |
| 2 | Admin | Membuka dashboard admin | Dashboard admin dapat diakses | Sesuai: HTTP 200 |
| 3 | Admin | Menambah, mengubah, dan menghapus data buku | Operasi berhasil | Sesuai: data tersimpan, berubah, dan terhapus di database |
| 4 | Admin | Mengelola akun dan role pengguna | Operasi berhasil | Sesuai: tambah akun (password ter-hash), ubah role, hapus akun berhasil |
| 5 | Admin | Mengakses data peminjaman, pengembalian, dan laporan | Data dapat diakses sesuai hak akses | Sesuai: `/peminjaman`, `/pengembalian`, `/laporan` HTTP 200 |
| 6 | Petugas | Membuka dashboard petugas | Dashboard petugas dapat diakses | Sesuai: HTTP 200 |
| 7 | Petugas | Mengelola data buku | Operasi berhasil | Sesuai: buku berhasil ditambahkan |
| 8 | Petugas | Memproses peminjaman dan pengembalian | Operasi berhasil | Sesuai: diajukan → dipinjam → dikembalikan, tanggal kembali terisi |
| 9 | Petugas | Membuka halaman pengelolaan akun atau role pengguna | Akses ditolak | Sesuai: HTTP 403, role pengguna tidak berubah |
| 10 | Siswa | Melihat daftar dan detail buku | Data buku dapat dilihat | Sesuai: HTTP 200, judul buku tampil |
| 11 | Siswa | Melihat dan mengubah profil sendiri | Data profil milik sendiri dapat diakses dan diperbarui | Sesuai: profil tampil, nama berhasil diperbarui |
| 12 | Siswa | Melihat riwayat peminjaman sendiri | Riwayat milik sendiri dapat dilihat | Sesuai: HTTP 200, riwayat milik sendiri tampil |
| 13 | Siswa | Membuka dashboard admin atau halaman pengelolaan buku | Akses ditolak | Sesuai: HTTP 403 (dashboard admin/petugas, edit/tambah/hapus buku) |
| 14 | Siswa | Mencoba melihat riwayat peminjaman siswa lain | Akses ditolak | Sesuai: `/riwayat-peminjaman/{id siswa lain}` HTTP 403; riwayat sendiri tidak memuat data siswa lain |
| 15 | Admin/Petugas/Siswa | Logout lalu membuka halaman yang dilindungi | Pengguna harus login kembali | Sesuai: setelah logout, `/buku` dialihkan ke `/login` (ketiga role) |

## Pengujian tambahan
- Registrasi publik selalu menghasilkan role `siswa`, walau form dimanipulasi dengan `role=admin`.
- Profil siswa lain (`/profil-saya/{id lain}`) ditolak 403; update profil tidak dapat mengubah akun lain.
- Login dengan kredensial salah ditolak; pengguna yang sudah login tidak dapat membuka `/login` dan `/register` (middleware `guest`).
- Pengajuan ganda untuk buku yang sama dan pengajuan buku yang sedang dipinjam ditolak.
- Siswa tidak dapat menyetujui, mencatat pengembalian, membuka `/peminjaman`, `/pengembalian`, atau `/laporan`.
- Admin tidak dapat menghapus atau mengubah role akunnya sendiri; role tidak valid ditolak.
- Menu navigasi (`@role` / `@can`) hanya menampilkan tautan sesuai hak akses.
- API `/api/buku`, `/api/kategori`, `/api/rekap/*` dilindungi `auth` dan permission.
