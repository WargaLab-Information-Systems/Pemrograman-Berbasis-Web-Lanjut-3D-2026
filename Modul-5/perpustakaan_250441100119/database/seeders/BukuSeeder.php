<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Database\Seeder;

class BukuSeeder extends Seeder
{
    public function run(): void
    {
        $novel = Kategori::where('nama', 'Novel')->first();
        $sejarah = Kategori::where('nama', 'Sejarah')->first();
        $pengembanganDiri = Kategori::where('nama', 'Pengembangan Diri')->first();

        Buku::factory()->create([
            'kategori_id' => $novel->id,
            'judul' => 'Laskar Pelangi',
            'penulis' => 'Andrea Hirata',
            'tahun_terbit' => 2005,
        ]);

        Buku::factory()->create([
            'kategori_id' => $sejarah->id,
            'judul' => 'Bumi Manusia',
            'penulis' => 'Pramoedya Ananta Toer',
            'tahun_terbit' => 1980,
        ]);

        Buku::factory()->create([
            'kategori_id' => $novel->id,
            'judul' => 'Negeri 5 Menara',
            'penulis' => 'Ahmad Fuadi',
            'tahun_terbit' => 2009,
        ]);

        Buku::factory()->create([
            'kategori_id' => $pengembanganDiri->id,
            'judul' => 'Filosofi Teras',
            'penulis' => 'Henry Manampiring',
            'tahun_terbit' => 2018,
        ]);

        Buku::factory()->create([
            'kategori_id' => $pengembanganDiri->id,
            'judul' => 'Atomic Habits',
            'penulis' => 'James Clear',
            'tahun_terbit' => 2018,
        ]);
    }
}