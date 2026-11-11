<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AkunRoleSeeder extends Seeder
{
    public function run(): void
    {
        // Membuat akun Admin jika belum terdaftar
        $admin = User::firstOrCreate(
            ['email' => 'admin2026@example.com'],
            [
                'name' => 'Admin Perpustakaan',
                'password' => Hash::make('Admin12345#'),
            ]
        );

        // Memberikan role Admin
        $admin->syncRoles(['admin']);

        // Membuat akun Petugas jika belum terdaftar
        $petugas = User::firstOrCreate(
            ['email' => 'petugas2026@example.com'],
            [
                'name' => 'Petugas Perpustakaan',
                'password' => Hash::make('Petugas12345#'),
            ]
        );

        // Memberikan role Petugas
        $petugas->syncRoles(['petugas']);

        $this->command->info(
            'Akun Admin dan Petugas berhasil disiapkan!'
        );
    }
}