<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        // Minimal 3 data kategori
        Kategori::factory()->count(5)->create();
    }
}
