<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache permission
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Buat 16 Permission sesuai tabel Soal No. 4
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
            Permission::findOrCreate($permission, 'web');
        }

        // 2. Assign Permission ke Role Admin
        $adminRole = Role::findOrCreate('admin', 'web');
        $adminRole->syncPermissions([
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

        // 3. Assign Permission ke Role Petugas
        $petugasRole = Role::findOrCreate('petugas', 'web');
        $petugasRole->syncPermissions([
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

        // 4. Assign Permission ke Role Siswa
        $siswaRole = Role::findOrCreate('siswa', 'web');
        $siswaRole->syncPermissions([
            'lihat daftar buku',
            'mengajukan peminjaman buku',
            'melihat status peminjaman sendiri',
            'melihat riwayat peminjaman sendiri',
            'lihat profil sendiri',
            'ubah profil sendiri',
        ]);

        // 5. Buat Pengguna Default untuk Skenario Pengujian
        $admin = User::firstOrCreate(
            ['email' => 'admin@perpustakaan.com'],
            ['name' => 'Admin Perpustakaan', 'password' => Hash::make('admin123')]
        );
        $admin->assignRole($adminRole);

        $petugas = User::firstOrCreate(
            ['email' => 'petugas@perpustakaan.com'],
            ['name' => 'Petugas Perpustakaan', 'password' => Hash::make('petugas123')]
        );
        $petugas->assignRole($petugasRole);

        $siswa = User::firstOrCreate(
            ['email' => 'siswa@perpustakaan.com'],
            ['name' => 'Siswa Example', 'password' => Hash::make('siswa123')]
        );
        $siswa->assignRole($siswaRole);
    }
}
