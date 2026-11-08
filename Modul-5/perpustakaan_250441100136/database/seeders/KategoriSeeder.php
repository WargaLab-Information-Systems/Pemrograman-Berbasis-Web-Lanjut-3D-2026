<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        Kategori::firstOrCreate(['id' => 1], ['nama' => 'Fiksi']);
        Kategori::firstOrCreate(['id' => 2], ['nama' => 'Non-Fiksi']);
    }
}
