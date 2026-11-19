<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Menghapus cache permission
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Membuat permission
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
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        // 2. Membuat role
        $admin = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $petugas = Role::firstOrCreate([
            'name' => 'petugas',
            'guard_name' => 'web',
        ]);

        $siswa = Role::firstOrCreate([
            'name' => 'siswa',
            'guard_name' => 'web',
        ]);

        // 3. Memberikan permission kepada Admin
        $admin->syncPermissions($permissions);

        // 4. Memberikan permission kepada Petugas
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

        // 5. Memberikan permission kepada Siswa
        $siswa->syncPermissions([
            'lihat daftar buku',
            'mengajukan peminjaman buku',
            'melihat status peminjaman sendiri',
            'melihat riwayat peminjaman sendiri',
            'lihat profil sendiri',
            'ubah profil sendiri',
        ]);

        // 6. Membuat akun Admin
        $user = User::firstOrCreate(
            ['email' => 'admin@perpus.com'],
            [
                'name' => 'Administrator',
                'password' => 'admin12345',
            ]
        );

        // 7. Memberikan role Admin
        $user->syncRoles(['admin']);

        $petugas = User::firstOrCreate(
            ['email' => 'petugas@perpus.com'],
            [
                'name' => 'Petugas Perpustakaan',
                'password' => 'petugas123'
            ]
        );

        $petugas->syncRoles(['petugas']);

        // Akun Siswa untuk pengujian hak akses.
        $siswaUser = User::firstOrCreate(
            ['email' => 'siswa@perpus.com'],
            [
                'name' => 'Siswa Perpustakaan',
                'password' => 'siswa12345',
            ]
        );
        $siswaUser->syncRoles(['siswa']);

        // Siswa kedua, dipakai untuk menguji bahwa data siswa lain tidak dapat diakses.
        $siswaDua = User::firstOrCreate(
            ['email' => 'siswa2@perpus.com'],
            [
                'name' => 'Siswa Kedua',
                'password' => 'siswa12345',
            ]
        );
        $siswaDua->syncRoles(['siswa']);

        // Menghapus cache permission
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}