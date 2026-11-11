<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Bersihkan cache Spatie agar perubahan langsung terbaca
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // 16 permission sesuai tabel pada soal no. 4
        $permissions = [
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

        foreach ($permissions as $nama) {
            Permission::firstOrCreate(['name' => $nama, 'guard_name' => 'web']);
        }

        // Permission yang dimiliki semua role (no. 3, 9, 12, 13, 15, 16)
        $umum = [
            'lihat daftar buku',
            'mengajukan peminjaman buku',
            'melihat status peminjaman sendiri',
            'melihat riwayat peminjaman sendiri',
            'lihat profil sendiri',
            'ubah profil sendiri',
        ];

        // Permission operasional (no. 4, 7, 8, 10, 11, 14) milik admin & petugas
        $operasional = [
            'kelola data buku',
            'kelola peminjaman',
            'kelola pengembalian',
            'memproses persetujuan peminjaman',
            'mencatat pengembalian buku',
            'lihat laporan perpustakaan',
        ];

        // ADMIN: semua kecuali "akses dashboard petugas"
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web'])->syncPermissions(array_merge(
            ['akses dashboard admin', 'kelola data pengguna', 'kelola role pengguna'],
            $umum,
            $operasional,
        ));

        // PETUGAS: tanpa dashboard admin, kelola pengguna, dan kelola role
        Role::firstOrCreate(['name' => 'petugas', 'guard_name' => 'web'])->syncPermissions(array_merge(
            ['akses dashboard petugas'],
            $umum,
            $operasional,
        ));

        // SISWA: hanya fitur untuk dirinya sendiri
        $roleSiswa = Role::firstOrCreate(['name' => 'siswa', 'guard_name' => 'web']);
        $roleSiswa->syncPermissions($umum);

        User::doesntHave('roles')->get()->each(
            fn (User $user) => $user->assignRole($roleSiswa)
        );

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
