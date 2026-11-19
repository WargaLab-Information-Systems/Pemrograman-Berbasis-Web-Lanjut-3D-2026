<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Bersihkan cache permission Spatie supaya data baru terbaca
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // 16 permission sesuai tabel pada soal
        $semua = [
            'akses dashboard admin',               // 1
            'akses dashboard petugas',             // 2
            'lihat daftar buku',                   // 3
            'kelola data buku',                    // 4
            'kelola data pengguna',                // 5
            'kelola role pengguna',                // 6
            'kelola peminjaman',                   // 7
            'kelola pengembalian',                 // 8
            'mengajukan peminjaman buku',          // 9
            'memproses persetujuan peminjaman',    // 10
            'mencatat pengembalian buku',          // 11
            'melihat status peminjaman sendiri',   // 12
            'melihat riwayat peminjaman sendiri',  // 13
            'lihat laporan perpustakaan',          // 14
            'lihat profil sendiri',                // 15
            'ubah profil sendiri',                 // 16
        ];

        foreach ($semua as $nama) {
            Permission::firstOrCreate(['name' => $nama, 'guard_name' => 'web']);
        }

        // Tiga role
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $petugas = Role::firstOrCreate(['name' => 'petugas', 'guard_name' => 'web']);
        $siswa = Role::firstOrCreate(['name' => 'siswa', 'guard_name' => 'web']);

        // Admin: semua kecuali dashboard petugas
        $admin->syncPermissions(array_values(array_diff($semua, [
            'akses dashboard petugas',
        ])));

        // Petugas: tanpa dashboard admin, data pengguna, dan role pengguna
        $petugas->syncPermissions(array_values(array_diff($semua, [
            'akses dashboard admin',
            'kelola data pengguna',
            'kelola role pengguna',
        ])));

        // Siswa: hanya fitur untuk dirinya sendiri
        $siswa->syncPermissions([
            'lihat daftar buku',
            'mengajukan peminjaman buku',
            'melihat status peminjaman sendiri',
            'melihat riwayat peminjaman sendiri',
            'lihat profil sendiri',
            'ubah profil sendiri',
        ]);
    }
}
