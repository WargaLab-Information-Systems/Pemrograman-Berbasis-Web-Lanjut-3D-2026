<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Sejarah', 'Filsafat', 'Novel'] as $nama) {
            Kategori::create(['nama' => $nama]);
        }
    }
}