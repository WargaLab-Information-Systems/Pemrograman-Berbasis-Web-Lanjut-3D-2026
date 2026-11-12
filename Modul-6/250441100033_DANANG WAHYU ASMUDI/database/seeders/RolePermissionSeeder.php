<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache permission
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 16 Permission sesuai tabel Modul V
        $permissions = [
            'akses dashboard admin',
            'akses dashboard petugas',
            'lihat daftar buku',
            'kelola data buku',
            'kelola data pengguna',
            'kelola role pengguna',
            'kelola peminjaman',
            'kelola pengembalian',
            'mengajukan peminjaman buku',
            'memproses persetujuan peminjaman',
            'mencatat pengembalian buku',
            'melihat status peminjaman sendiri',
            'melihat riwayat peminjaman sendiri',
            'lihat laporan perpustakaan',
            'lihat profil sendiri',
            'ubah profil sendiri',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 3 Role
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $rolePetugas = Role::firstOrCreate(['name' => 'petugas']);
        $roleSiswa = Role::firstOrCreate(['name' => 'siswa']);

        // Admin: 15 permission sesuai tabel Modul V
        $roleAdmin->syncPermissions([
            'akses dashboard admin',
            'lihat daftar buku',
            'kelola data buku',
            'kelola data pengguna',
            'kelola role pengguna',
            'kelola peminjaman',
            'kelola pengembalian',
            'mengajukan peminjaman buku',
            'memproses persetujuan peminjaman',
            'mencatat pengembalian buku',
            'melihat status peminjaman sendiri',
            'melihat riwayat peminjaman sendiri',
            'lihat laporan perpustakaan',
            'lihat profil sendiri',
            'ubah profil sendiri',
        ]);

        // Petugas: 13 permission sesuai tabel Modul V
        $rolePetugas->syncPermissions([
            'akses dashboard petugas',
            'lihat daftar buku',
            'kelola data buku',
            'kelola peminjaman',
            'kelola pengembalian',
            'mengajukan peminjaman buku',
            'memproses persetujuan peminjaman',
            'mencatat pengembalian buku',
            'melihat status peminjaman sendiri',
            'melihat riwayat peminjaman sendiri',
            'lihat laporan perpustakaan',
            'lihat profil sendiri',
            'ubah profil sendiri',
        ]);

        // Siswa: 6 permission sesuai tabel Modul V
        $roleSiswa->syncPermissions([
            'lihat daftar buku',
            'mengajukan peminjaman buku',
            'melihat status peminjaman sendiri',
            'melihat riwayat peminjaman sendiri',
            'lihat profil sendiri',
            'ubah profil sendiri',
        ]);

        // Akun Admin (SOAL 5 (c): Ganti password admin bawaan, password tidak lagi 'password')
        $admin = User::updateOrCreate(
            ['email' => 'admin@perpustakaan.com'],
            ['name' => 'Admin Perpustakaan', 'password' => Hash::make('AdminPerpus_250441100033!')]
        );
        $admin->assignRole($roleAdmin);

        // Akun Petugas
        $petugas = User::firstOrCreate(
            ['email' => 'petugas@perpustakaan.com'],
            ['name' => 'Petugas Perpustakaan', 'password' => Hash::make('password')]
        );
        $petugas->assignRole($rolePetugas);

        // Akun Siswa 1
        $siswa = User::firstOrCreate(
            ['email' => 'siswa@perpustakaan.com'],
            ['name' => 'Siswa Teladan', 'password' => Hash::make('password')]
        );
        $siswa->assignRole($roleSiswa);

        // Akun Siswa 2 (Untuk pengujian IDOR Soal 4: Siswa Lain)
        $siswa2 = User::firstOrCreate(
            ['email' => 'siswa2@perpustakaan.com'],
            ['name' => 'Siswa Kedua', 'password' => Hash::make('password')]
        );
        $siswa2->assignRole($roleSiswa);

        // Anggota & Contoh Riwayat Peminjaman Siswa
        $anggotaSiswa = \App\Models\Anggota::firstOrCreate(
            ['email' => 'siswa@perpustakaan.com'],
            ['nama' => 'Siswa Teladan', 'no_telepon' => '08123456789']
        );

        $anggotaSiswa2 = \App\Models\Anggota::firstOrCreate(
            ['email' => 'siswa2@perpustakaan.com'],
            ['nama' => 'Siswa Kedua', 'no_telepon' => '08987654321']
        );

        $bukuContoh = \App\Models\Buku::first();
        if ($bukuContoh) {
            \App\Models\Peminjaman::firstOrCreate(
                ['anggota_id' => $anggotaSiswa->id, 'buku_id' => $bukuContoh->id],
                ['tanggal_pinjam' => date('Y-m-d', strtotime('-3 days')), 'tanggal_kembali' => null]
            );
        }
    }
}
