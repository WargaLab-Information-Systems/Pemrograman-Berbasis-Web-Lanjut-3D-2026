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
        // Reset cache permission Spatie
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Daftar 16 Permission sesuai tabel Modul 5
        $permissions = [
            'akses dashboard admin',            // 1
            'akses dashboard petugas',          // 2
            'lihat daftar buku',                // 3
            'kelola data buku',                 // 4
            'kelola data pengguna',             // 5
            'kelola role pengguna',             // 6
            'kelola peminjaman',                // 7
            'kelola pengembalian',              // 8
            'mengajukan peminjaman buku',       // 9
            'memproses persetujuan peminjaman', // 10
            'mencatat pengembalian buku',       // 11
            'melihat status peminjaman sendiri',// 12
            'melihat riwayat peminjaman sendiri',// 13
            'lihat laporan perpustakaan',       // 14
            'lihat profil sendiri',             // 15
            'ubah profil sendiri',              // 16
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 1. Role Admin
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $roleAdmin->syncPermissions([
            'akses dashboard admin',
            'lihat daftar buku',
            'kelola data buku',
            'kelola data pengguna',
            'kelola role pengguna',
            'kelola peminjaman',
            'kelola pengembalian',
            'memproses persetujuan peminjaman',
            'mencatat pengembalian buku',
            'melihat status peminjaman sendiri',
            'melihat riwayat peminjaman sendiri',
            'lihat laporan perpustakaan',
            'lihat profil sendiri',
            'ubah profil sendiri',
        ]);

        // 2. Role Petugas
        $rolePetugas = Role::firstOrCreate(['name' => 'petugas']);
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

        // 3. Role Siswa
        $roleSiswa = Role::firstOrCreate(['name' => 'siswa']);
        $roleSiswa->syncPermissions([
            'lihat daftar buku',
            'mengajukan peminjaman buku',
            'melihat status peminjaman sendiri',
            'melihat riwayat peminjaman sendiri',
            'lihat profil sendiri',
            'ubah profil sendiri',
        ]);

        // Buat Akun Uji Coba untuk masing-masing Role
        $admin = User::firstOrCreate(
            ['email' => 'admin@perpustakaan.test'],
            ['name' => 'Admin Perpustakaan', 'password' => Hash::make('password123')]
        );
        $admin->syncRoles(['admin']);

        $petugas = User::firstOrCreate(
            ['email' => 'petugas@perpustakaan.test'],
            ['name' => 'Petugas Perpustakaan', 'password' => Hash::make('password123')]
        );
        $petugas->syncRoles(['petugas']);

        $siswa1 = User::firstOrCreate(
            ['email' => 'siswa@perpustakaan.test'],
            ['name' => 'Hawwin Siswa', 'password' => Hash::make('password123')]
        );
        $siswa1->syncRoles(['siswa']);

        $siswa2 = User::firstOrCreate(
            ['email' => 'siswa2@perpustakaan.test'],
            ['name' => 'Siswa Lain', 'password' => Hash::make('password123')]
        );
        $siswa2->syncRoles(['siswa']);
    }
}