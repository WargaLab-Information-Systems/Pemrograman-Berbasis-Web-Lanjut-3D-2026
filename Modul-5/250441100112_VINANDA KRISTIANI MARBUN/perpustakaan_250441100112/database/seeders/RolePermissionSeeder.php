<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Membersihkan cache permission
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Daftar 16 permission
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

        // Membuat permission
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        // Membuat role
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

        // Permission untuk Admin (Semua kecuali akses dashboard petugas sesuai tabel praktikum)
        $adminPermissions = array_values(array_filter($permissions, fn($p) => $p !== 'akses dashboard petugas'));
        $admin->syncPermissions($adminPermissions);

        // Permission untuk Petugas
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

        // Permission untuk Siswa
        $siswa->syncPermissions([
            'lihat daftar buku',
            'mengajukan peminjaman buku',
            'melihat status peminjaman sendiri',
            'melihat riwayat peminjaman sendiri',
            'lihat profil sendiri',
            'ubah profil sendiri',
        ]);

        // Membersihkan cache kembali
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Akun default untuk pengujian RBAC
        $daftarAkun = [
            [
                'name' => 'Admin Perpustakaan',
                'email' => 'admin@perpustakaan.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                'role' => 'admin',
                'telepon' => '081234567890',
            ],
            [
                'name' => 'Petugas Perpustakaan',
                'email' => 'petugas@perpustakaan.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                'role' => 'petugas',
                'telepon' => '081234567891',
            ],
            [
                'name' => 'Siswa Perpustakaan',
                'email' => 'siswa@perpustakaan.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                'role' => 'siswa',
                'telepon' => '081234567892',
            ],
            [
                'name' => 'Siswa Lain (Budi)',
                'email' => 'siswa2@perpustakaan.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                'role' => 'siswa',
                'telepon' => '081234567893',
            ],
        ];

        foreach ($daftarAkun as $item) {
            $user = \App\Models\User::firstOrCreate(
                ['email' => $item['email']],
                [
                    'name' => $item['name'],
                    'password' => $item['password'],
                ]
            );

            // Sinkronkan role
            $user->syncRoles([$item['role']]);

            // Sinkronkan ke tabel anggotas
            \App\Models\Anggota::firstOrCreate(
                ['email' => $item['email']],
                [
                    'nama' => $item['name'],
                    'no_telepon' => $item['telepon'],
                ]
            );
        }

        // Buat contoh peminjaman untuk pengujian Siswa (Skenario 12 & 14)
        $bukuPertama = \App\Models\Buku::first();
        $bukuKedua = \App\Models\Buku::skip(1)->first() ?? $bukuPertama;

        $anggotaSiswa1 = \App\Models\Anggota::where('email', 'siswa@perpustakaan.com')->first();
        $anggotaSiswa2 = \App\Models\Anggota::where('email', 'siswa2@perpustakaan.com')->first();

        if ($bukuPertama && $anggotaSiswa1) {
            \App\Models\Peminjaman::firstOrCreate(
                [
                    'anggota_id' => $anggotaSiswa1->id,
                    'buku_id' => $bukuPertama->id,
                ],
                [
                    'tanggal_pinjam' => now()->subDays(3)->toDateString(),
                    'tanggal_kembali' => null,
                    'status' => 'dipinjam',
                ]
            );
        }

        if ($bukuKedua && $anggotaSiswa2) {
            \App\Models\Peminjaman::firstOrCreate(
                [
                    'anggota_id' => $anggotaSiswa2->id,
                    'buku_id' => $bukuKedua->id,
                ],
                [
                    'tanggal_pinjam' => now()->subDays(2)->toDateString(),
                    'tanggal_kembali' => null,
                    'status' => 'dipinjam',
                ]
            );
        }
    }
}
