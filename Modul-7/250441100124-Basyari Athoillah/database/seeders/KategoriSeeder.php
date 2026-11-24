<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Mengisi database dengan data dummy untuk tabel kategoris.
     */
    public function run(): void
    {
        // Membuat 5 data dummy kategori
        Kategori::factory()->count(5)->create();
    }
}
