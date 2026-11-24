<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Daftar permission sesuai tabel pada soal nomor 4 (urutan 1 - 16).
     */
    public const PERMISSIONS = [
        'akses dashboard admin',              // 1
        'akses dashboard petugas',            // 2
        'lihat daftar buku',                  // 3
        'kelola data buku',                   // 4
        'kelola data pengguna',               // 5
        'kelola role pengguna',               // 6
        'kelola peminjaman',                  // 7
        'kelola pengembalian',                // 8
        'mengajukan peminjaman buku',         // 9
        'memproses persetujuan peminjaman',   // 10
        'mencatat pengembalian buku',         // 11
        'melihat status peminjaman sendiri',  // 12
        'melihat riwayat peminjaman sendiri', // 13
        'lihat laporan perpustakaan',         // 14
        'lihat profil sendiri',               // 15
        'ubah profil sendiri',                // 16
    ];

    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        foreach (self::PERMISSIONS as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $petugas = Role::firstOrCreate(['name' => 'petugas', 'guard_name' => 'web']);
        $siswa = Role::firstOrCreate(['name' => 'siswa', 'guard_name' => 'web']);

        // ADMIN: semua permission, KECUALI "akses dashboard petugas"
        $admin->syncPermissions([
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

        // PETUGAS: tidak boleh kelola pengguna & role, tidak boleh dashboard admin
        $petugas->syncPermissions([
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

        // SISWA: hanya fitur untuk dirinya sendiri
        $siswa->syncPermissions([
            'lihat daftar buku',
            'mengajukan peminjaman buku',
            'melihat status peminjaman sendiri',
            'melihat riwayat peminjaman sendiri',
            'lihat profil sendiri',
            'ubah profil sendiri',
        ]);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
