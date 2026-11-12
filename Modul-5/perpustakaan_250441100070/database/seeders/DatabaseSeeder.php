<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Urutan wajib: tabel induk dulu, baru yang punya foreign key.
        $this->call([
            KategoriSeeder::class,
            AnggotaSeeder::class,
            BukuSeeder::class,
            PeminjamanSeeder::class,

            // Modul 5: role, permission, dan akun contoh
            RolePermissionSeeder::class,
            UserSeeder::class,
        ]);
    }
}
