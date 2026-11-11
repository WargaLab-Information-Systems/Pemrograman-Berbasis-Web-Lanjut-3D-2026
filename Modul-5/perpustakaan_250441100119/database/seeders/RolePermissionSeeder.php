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
        // Bersihkan cache permission.
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Semua 16 permission sesuai ketentuan Modul 5.
        $permissions = [
            'akses dashboard admin',
            'akses dashboard petugas',
            'lihat daftar buku',
            'kelola data buku',
            'kelola data pengguna',
            'kelola role pengguna',
            'kelola peminjaman',
            'kelola pengembalian',
            'ajukan peminjaman buku',
            'proses persetujuan peminjaman',
            'catat pengembalian buku',
            'lihat status peminjaman sendiri',
            'lihat riwayat peminjaman sendiri',
            'lihat laporan perpustakaan',
            'lihat profil sendiri',
            'ubah profil sendiri',
        ];

        // Buat permission jika belum tersedia.
        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        // Siapkan ketiga role.
        $admin = Role::findOrCreate('admin', 'web');
        $petugas = Role::findOrCreate('petugas', 'web');
        $siswa = Role::findOrCreate('siswa', 'web');

        // Admin: seluruh fitur administrasi dan operasional.
        $admin->syncPermissions(
            array_values(array_diff($permissions, [
                'akses dashboard petugas',
            ]))
        );

        // Petugas: fitur operasional, tanpa fitur khusus Siswa
        // dan tanpa pengelolaan pengguna/role.
        $petugas->syncPermissions([
            'akses dashboard petugas',
            'lihat daftar buku',
            'kelola data buku',
            'kelola peminjaman',
            'kelola pengembalian',
            'proses persetujuan peminjaman',
            'catat pengembalian buku',
            'lihat laporan perpustakaan',
            'lihat profil sendiri',
            'ubah profil sendiri',
        ]);

        // Siswa: melihat buku, mengajukan peminjaman,
        // melihat riwayat sendiri, dan mengelola profil sendiri.
        $siswa->syncPermissions([
            'lihat daftar buku',
            'ajukan peminjaman buku',
            'lihat status peminjaman sendiri',
            'lihat riwayat peminjaman sendiri',
            'lihat profil sendiri',
            'ubah profil sendiri',
        ]);

        // Pastikan cache permission diperbarui.
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->command->info(
            'Berhasil menyiapkan 3 role dan 16 permission Modul 5!'
        );
    }
}