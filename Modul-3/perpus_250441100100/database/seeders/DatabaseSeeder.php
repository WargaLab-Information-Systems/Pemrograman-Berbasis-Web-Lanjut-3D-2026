<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            KategoriSeeder::class,
            AnggotaSeeder::class,
            BukuSeeder::class,
            // Role & akun harus ada sebelum peminjaman agar bisa dikaitkan ke user.
            RolePermissionSeeder::class,
            PeminjamanSeeder::class,
        ]);
    }
}