<?php

namespace Database\Seeders;

use App\Models\Anggota;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cache permission
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Daftar 16 Permission sesuai Modul 5
        $permissions = [
            'Akses dashboard admin',
            'Akses dashboard petugas',
            'Lihat daftar buku',
            'Kelola data buku',
            'Kelola data pengguna',
            'Kelola role pengguna',
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

        // 1. Role Admin
        $roleAdmin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $roleAdmin->syncPermissions([
            'Akses dashboard admin',
            'Lihat daftar buku',
            'Kelola data buku',
            'Kelola data pengguna',
            'Kelola role pengguna',
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

        // 2. Role Petugas
        $rolePetugas = Role::firstOrCreate(['name' => 'petugas', 'guard_name' => 'web']);
        $rolePetugas->syncPermissions([
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

        // 3. Role Siswa
        $roleSiswa = Role::firstOrCreate(['name' => 'siswa', 'guard_name' => 'web']);
        $roleSiswa->syncPermissions([
            'Lihat daftar buku',
            'Mengajukan peminjaman buku',
            'Melihat status peminjaman sendiri',
            'Melihat riwayat peminjaman sendiri',
            'Lihat profil sendiri',
            'Ubah profil sendiri',
        ]);

        // Pembuatan Akun Default untuk Pengujian Skenario
        // 1. Akun Admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );
        $admin->syncRoles(['admin']);

        // 2. Akun Petugas
        $petugas = User::updateOrCreate(
            ['email' => 'petugas@gmail.com'],
            [
                'name' => 'Petugas Perpustakaan',
                'password' => Hash::make('password'),
                'role' => 'petugas',
            ]
        );
        $petugas->syncRoles(['petugas']);

        // 3. Akun Siswa (Budi)
        $siswa = User::updateOrCreate(
            ['email' => 'siswa@gmail.com'],
            [
                'name' => 'Siswa Budi',
                'password' => Hash::make('password'),
                'role' => 'siswa',
            ]
        );
        $siswa->syncRoles(['siswa']);

        // Hubungkan Siswa Budi ke data Anggota
        Anggota::updateOrCreate(
            ['email' => $siswa->email],
            [
                'user_id' => $siswa->id,
                'nama' => $siswa->name,
                'no_telepon' => '081234567890',
            ]
        );

        // 4. Akun Siswa 2 (Siti) untuk menguji skenario 14 (siswa lain)
        $siswa2 = User::updateOrCreate(
            ['email' => 'siswa2@gmail.com'],
            [
                'name' => 'Siswa Siti',
                'password' => Hash::make('password'),
                'role' => 'siswa',
            ]
        );
        $siswa2->syncRoles(['siswa']);

        Anggota::updateOrCreate(
            ['email' => $siswa2->email],
            [
                'user_id' => $siswa2->id,
                'nama' => $siswa2->name,
                'no_telepon' => '089876543210',
            ]
        );
    }
}

