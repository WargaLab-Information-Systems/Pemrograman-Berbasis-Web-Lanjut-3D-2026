<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoris = [
            ['nama' => 'Fiksi'],
            ['nama' => 'NonFiksi'],
            ['nama' => 'Teknologi'],
            ['nama' => 'Pendidikan'],
            ['nama' => 'Sejarah'],
        ];
        foreach ($kategoris as $kategori) {
            Kategori::updateOrCreate(
                ['nama' => $kategori['nama']],
                $kategori
            );
        }
    }
}
