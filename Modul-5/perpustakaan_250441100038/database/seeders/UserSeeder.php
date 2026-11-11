<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Akun awal. Role hanya bisa ditetapkan lewat seeder ini atau
     * lewat halaman Kelola Pengguna (khusus admin), bukan lewat form register.
     */
    public function run(): void
    {
        $akun = [
            ['Admin Utama',  'admin@test.com',   'admin'],
            ['Petugas Satu', 'petugas@test.com', 'petugas'],
            ['Siswa Satu',   'siswa@test.com',   'siswa'],
            ['Siswa Dua',    'siswa2@test.com',  'siswa'],
        ];

        foreach ($akun as [$nama, $email, $role]) {
            $user = User::updateOrCreate(
                ['email' => $email],
                ['name' => $nama, 'password' => Hash::make('password')],
            );
            $user->syncRoles([$role]);
        }
    }
}
