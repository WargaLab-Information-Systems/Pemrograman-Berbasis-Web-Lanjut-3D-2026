<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        Kategori::create(['nama' => 'Novel / Fiksi']);
        Kategori::create(['nama' => 'Novel / Aksi']);
        Kategori::create(['nama' => 'Novel / Fantasi']);
    }
}