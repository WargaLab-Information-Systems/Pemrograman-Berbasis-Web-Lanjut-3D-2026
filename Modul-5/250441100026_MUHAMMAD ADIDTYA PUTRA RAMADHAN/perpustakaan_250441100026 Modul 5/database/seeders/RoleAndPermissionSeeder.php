<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 16 Permissions sesuai Modul V
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

        foreach ($permissions as $perm) {
            Permission::findOrCreate($perm, 'web');
        }

        // Roles
        $adminRole = Role::findOrCreate('admin', 'web');
        $petugasRole = Role::findOrCreate('petugas', 'web');
        $siswaRole = Role::findOrCreate('siswa', 'web');

        // Admin permissions: seluruhnya kecuali 'akses dashboard petugas'
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

        // Petugas permissions:
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

        // Siswa permissions:
        $siswaRole->syncPermissions([
            'lihat daftar buku',
            'mengajukan peminjaman buku',
            'melihat status peminjaman sendiri',
            'melihat riwayat peminjaman sendiri',
            'lihat profil sendiri',
            'ubah profil sendiri',
        ]);

        // Akun Pengguna Bawaan (Admin, Petugas, Siswa, Siswa 2)
        $admin = User::updateOrCreate(
            ['email' => 'admin@perpustakaan.com'],
            [
                'name' => 'Administrator Perpustakaan',
                'password' => Hash::make('password'),
            ]
        );
        $admin->syncRoles([$adminRole]);

        $petugas = User::updateOrCreate(
            ['email' => 'petugas@perpustakaan.com'],
            [
                'name' => 'Petugas Perpustakaan',
                'password' => Hash::make('password'),
            ]
        );
        $petugas->syncRoles([$petugasRole]);

        $siswa1 = User::updateOrCreate(
            ['email' => 'siswa@perpustakaan.com'],
            [
                'name' => 'Siswa Satu (Budi)',
                'password' => Hash::make('password'),
            ]
        );
        $siswa1->syncRoles([$siswaRole]);

        $siswa2 = User::updateOrCreate(
            ['email' => 'siswa2@perpustakaan.com'],
            [
                'name' => 'Siswa Dua (Siti)',
                'password' => Hash::make('password'),
            ]
        );
        $siswa2->syncRoles([$siswaRole]);

        // Buat sample peminjaman hanya jika belum ada peminjaman sebelumnya
        if (Peminjaman::count() === 0) {
            $buku1 = Buku::first();
            $buku2 = Buku::skip(1)->first() ?? $buku1;

            if ($buku1) {
                Peminjaman::create([
                    'user_id' => $siswa1->id,
                    'buku_id' => $buku1->id,
                    'tanggal_pinjam' => now()->subDays(3)->toDateString(),
                    'tanggal_kembali' => null,
                    'status' => 'dipinjam',
                ]);
            }

            if ($buku2) {
                Peminjaman::create([
                    'user_id' => $siswa2->id,
                    'buku_id' => $buku2->id,
                    'tanggal_pinjam' => now()->subDays(5)->toDateString(),
                    'tanggal_kembali' => null,
                    'status' => 'dipinjam',
                ]);
            }
        }
    }
}
