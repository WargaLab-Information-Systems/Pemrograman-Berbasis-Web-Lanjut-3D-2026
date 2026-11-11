<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;
use App\Models\Buku;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = ['Fiksi', 'Pelajaran', 'Sejarah', 'Teknologi', 'Sastra'];
        
        foreach ($kategoris as $kategori) {
            Kategori::create(['nama' => $kategori]);
        }

        Buku::factory(10)->create();
    }
}