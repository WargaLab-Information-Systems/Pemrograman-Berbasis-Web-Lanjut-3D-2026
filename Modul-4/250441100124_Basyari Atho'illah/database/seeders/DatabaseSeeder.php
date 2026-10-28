<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Menjalankan seluruh seeder aplikasi.
     *
     * Catatan: Urutan pemanggilan sangat penting!
     * 1. Tabel master independen (Petugas, Kategori, Anggota) dijalankan lebih dulu.
     * 2. Tabel Buku dijalankan setelah Kategori (karena membutuhkan foreign key kategori_id).
     * 3. Tabel Peminjaman dijalankan terakhir (karena membutuhkan foreign key anggota_id dan buku_id).
     */
    public function run(): void
    {
        $this->call([
            PetugasSeeder::class,
            KategoriSeeder::class,
            AnggotaSeeder::class,
            BukuSeeder::class,
            PeminjamanSeeder::class,
        ]);
    }
}
