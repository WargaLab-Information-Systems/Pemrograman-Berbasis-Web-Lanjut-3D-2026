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
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'Akses dashboard admin',
            'Akses dashboard petugas',
            'Lihat daftar buku',
            'Kelola data buku',
            'Kelola data pengguna',
            'Kelola peminjaman',
            'Ajukan peminjaman',
            'Lihat laporan',
            'Lihat profil',
            'Lihat riwayat peminjaman',
        ];

        foreach ($permissions as$permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        // Role Admin
        $roleAdmin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);$roleAdmin->syncPermissions([
            'Akses dashboard admin',
            'Lihat daftar buku',
            'Kelola data buku',
            'Kelola data pengguna',
            'Kelola peminjaman',
            'Lihat laporan',
            'Lihat profil',
        ]);

        // Role Petugas
        $rolePetugas = Role::firstOrCreate(['name' => 'petugas', 'guard_name' => 'web']);$rolePetugas->syncPermissions([
            'Akses dashboard petugas',
            'Lihat daftar buku',
            'Kelola data buku',
            'Kelola peminjaman',
            'Lihat laporan',
            'Lihat profil',
        ]);

        // Role Siswa
        $roleSiswa = Role::firstOrCreate(['name' => 'siswa', 'guard_name' => 'web']);$roleSiswa->syncPermissions([
            'Lihat daftar buku',
            'Ajukan peminjaman',
            'Lihat profil',
            'Lihat riwayat peminjaman',
        ]);

        // Akun Default
        $admin = User::firstOrCreate(
            ['email' => 'admin@perpus.com'],
            ['name' => 'Administrator AmbaPus', 'password' => Hash::make('password')]
        );
        $admin->syncRoles([$roleAdmin]);

        $petugas = User::firstOrCreate(
            ['email' => 'petugas@perpus.com'],
            ['name' => 'Petugas Perpustakaan', 'password' => Hash::make('password')]
        );
        $petugas->syncRoles([$rolePetugas]);

        $siswa = User::firstOrCreate(
            ['email' => 'siswa@perpus.com'],
            ['name' => 'Siswa Perpustakaan', 'password' => Hash::make('password')]
        );
        $siswa->syncRoles([$roleSiswa]);
    }
}