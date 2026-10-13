<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Urutan wajib: tabel induk (Kategori, Anggota) lebih dulu,
        // baru tabel yang punya foreign key (Buku, lalu Peminjaman).
        $this->call([
            KategoriSeeder::class,
            AnggotaSeeder::class,
            BukuSeeder::class,
            PeminjamanSeeder::class,
            kampusseeder::class, // Seeder untuk tabel gedung
        ]);
    }
}
