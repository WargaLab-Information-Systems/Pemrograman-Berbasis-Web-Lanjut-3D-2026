<?php

namespace Database\Seeders;

use App\Models\Buku;
use Illuminate\Database\Seeder;

class BukuSeeder extends Seeder
{
    /**
     * Mengisi database dengan data dummy untuk tabel bukus.
     */
    public function run(): void
    {
        // Membuat 10 data dummy buku
        Buku::factory()->count(10)->create();
    }
}
