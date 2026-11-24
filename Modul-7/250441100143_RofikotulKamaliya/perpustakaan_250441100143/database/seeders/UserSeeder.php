<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $passwordAdmin = env('SEED_ADMIN_PASSWORD') ?: Str::random(20);

        $akun = [
            ['Administrator', 'admin@gmail.com', 'admin'],
            ['Petugas Perpustakaan', 'petugas@gmail.com', 'petugas'],
            ['Siswa Teladan', 'siswa@gmail.com', 'siswa'],
            ['Siswa Kedua', 'siswa2@gmail.com', 'siswa'],
        ];

        foreach ($akun as [$nama, $email, $role]) {
            $password = $email === 'admin@gmail.com' ? $passwordAdmin : 'password';

            $user = User::updateOrCreate(
                ['email' => $email],
                ['name' => $nama, 'password' => Hash::make($password)]
            );

            $user->syncRoles([$role]);
        }
    }
}
