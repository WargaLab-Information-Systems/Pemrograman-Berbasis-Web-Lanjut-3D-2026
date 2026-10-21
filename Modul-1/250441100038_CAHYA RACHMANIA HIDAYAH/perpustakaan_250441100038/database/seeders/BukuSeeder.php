<?php

namespace Database\Seeders;

use App\Models\Buku;
use Illuminate\Database\Seeder;

class BukuSeeder extends Seeder
{
    public function run(): void
    {
        Buku::create(['kategori_id' => 1, 'judul' => 'Laskar Pelangi', 'penulis' => 'Andrea Hirata', 'tahun_terbit' => 2005]);
        Buku::create(['kategori_id' => 2, 'judul' => 'Pulang-Pergi', 'penulis' => 'Tere Liye', 'tahun_terbit' => 2021]);
        Buku::create(['kategori_id' => 3, 'judul' => 'Komet', 'penulis' => 'Tere Liye', 'tahun_terbit' => 2018]);
        Buku::create(['kategori_id' => 3, 'judul' => 'Komet Minor', 'penulis' => 'Tere Liye', 'tahun_terbit' => 2019]);
        Buku::create(['kategori_id' => 3, 'judul' => 'Nebula', 'penulis' => 'Tere Liye', 'tahun_terbit' => 2020]);
    }
}