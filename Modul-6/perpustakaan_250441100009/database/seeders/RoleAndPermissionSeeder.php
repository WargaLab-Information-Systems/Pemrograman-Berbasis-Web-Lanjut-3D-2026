<?php

namespace Database\Seeders;

use App\Models\Anggota;
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
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

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
            Permission::firstOrCreate(['name' => $permission]);
        }

        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $rolePetugas = Role::firstOrCreate(['name' => 'petugas']);
        $roleSiswa = Role::firstOrCreate(['name' => 'siswa']);

        // Admin
        $roleAdmin->syncPermissions([
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

        // Petugas
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

        // Siswa
        $roleSiswa->syncPermissions([
            'lihat daftar buku',
            'mengajukan peminjaman buku',
            'melihat status peminjaman sendiri',
            'melihat riwayat peminjaman sendiri',
            'lihat profil sendiri',
            'ubah profil sendiri',
        ]);

        // Akun Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@perpustakaan.com'],
            [
                'name' => 'Admin Perpustakaan',
                'password' => Hash::make('password'),
            ]
        );
        $admin->syncRoles([$roleAdmin]);

        // Akun Petugas
        $petugas = User::firstOrCreate(
            ['email' => 'petugas@perpustakaan.com'],
            [
                'name' => 'Petugas Perpustakaan',
                'password' => Hash::make('password'),
            ]
        );
        $petugas->syncRoles([$rolePetugas]);

        // Siswa 1
        $siswa1 = User::firstOrCreate(
            ['email' => 'siswa1@perpustakaan.com'],
            [
                'name' => 'Siswa Satu',
                'password' => Hash::make('password'),
            ]
        );
        $siswa1->syncRoles([$roleSiswa]);

        // Siswa 2
        $siswa2 = User::firstOrCreate(
            ['email' => 'siswa2@perpustakaan.com'],
            [
                'name' => 'Siswa Dua',
                'password' => Hash::make('password'),
            ]
        );
        $siswa2->syncRoles([$roleSiswa]);

        $buku = Buku::first();
        $anggota = Anggota::first();
        $anggotaId = $anggota?->id ?? 1;

        if ($buku) {
            Peminjaman::updateOrCreate(
                [
                    'user_id' => $siswa1->id,
                    'buku_id' => $buku->id,
                    'tanggal_pinjam' => now()->subDays(3)->toDateString(),
                ],
                [
                    'anggota_id' => $anggotaId,
                    'tanggal_kembali' => null,
                    'status' => 'disetujui',
                ]
            );

            Peminjaman::updateOrCreate(
                [
                    'user_id' => $siswa2->id,
                    'buku_id' => $buku->id,
                    'tanggal_pinjam' => now()->subDays(5)->toDateString(),
                ],
                [
                    'anggota_id' => $anggotaId,
                    'tanggal_kembali' => now()->subDays(1)->toDateString(),
                    'status' => 'dikembalikan',
                ]
            );

            // Sampel pengajuan pinjam yang masih 'menunggu' persetujuan
            $bukuKedua = Buku::skip(1)->first() ?? $buku;
            Peminjaman::updateOrCreate(
                [
                    'user_id' => $siswa1->id,
                    'buku_id' => $bukuKedua->id,
                    'tanggal_pinjam' => now()->toDateString(),
                ],
                [
                    'anggota_id' => $anggotaId,
                    'tanggal_kembali' => null,
                    'status' => 'menunggu',
                ]
            );
        }
    }
}
