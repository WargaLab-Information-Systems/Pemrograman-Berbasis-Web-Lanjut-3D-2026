<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'Akses dashboard admin',
            'Akses dashboard petugas',
            'Lihat daftar buku',
            'Kelola data buku',
            'Kelola data pengguna',
            'Kelola peminjaman',
            'Kelola pengembalian',
            'Mengajukan peminjaman buku',
            'Memproses persetujuan peminjaman',
            'Mencatat pengembalian buku',
            'Melihat status peminjaman sendiri',
            'Melihat riwayat peminjaman sendiri',
            'Lihat laporan perpustakaan',
            'Lihat profil sendiri',
            'Ubah profil sendiri',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions([
            'Akses dashboard admin',
            'Lihat daftar buku',
            'Kelola data buku',
            'Kelola data pengguna',
            'Kelola peminjaman',
            'Kelola pengembalian',
            'Memproses persetujuan peminjaman',
            'Mencatat pengembalian buku',
            'Melihat status peminjaman sendiri',
            'Melihat riwayat peminjaman sendiri',
            'Lihat laporan perpustakaan',
            'Lihat profil sendiri',
            'Ubah profil sendiri',
        ]);

        $petugas = Role::firstOrCreate(['name' => 'petugas', 'guard_name' => 'web']);
        $petugas->syncPermissions([
            'Akses dashboard petugas',
            'Lihat daftar buku',
            'Kelola data buku',
            'Kelola peminjaman',
            'Kelola pengembalian',
            'Mengajukan peminjaman buku',
            'Memproses persetujuan peminjaman',
            'Mencatat pengembalian buku',
            'Melihat status peminjaman sendiri',
            'Melihat riwayat peminjaman sendiri',
            'Lihat laporan perpustakaan',
            'Lihat profil sendiri',
            'Ubah profil sendiri',
        ]);

        $siswa = Role::firstOrCreate(['name' => 'siswa', 'guard_name' => 'web']);
        $siswa->syncPermissions([
            'Lihat daftar buku',
            'Melihat status peminjaman sendiri',
            'Melihat riwayat peminjaman sendiri',
            'Lihat profil sendiri',
            'Ubah profil sendiri',
        ]);

        $userAdmin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            ['name' => 'Admin Judol', 'password' => Hash::make('password123')]
        );
        $userAdmin->syncRoles(['admin']);

        $userPetugas = User::updateOrCreate(
            ['email' => 'petugas@gmail.com'],
            ['name' => 'Petugas Perpustakaan', 'password' => Hash::make('password123')]
        );
        $userPetugas->syncRoles(['petugas']);

        $userSiswa = User::updateOrCreate(
            ['email' => 'siswa@gmail.com'],
            ['name' => 'Siswa', 'password' => Hash::make('password123')]
        );
        $userSiswa->syncRoles(['siswa']);
    }
}
