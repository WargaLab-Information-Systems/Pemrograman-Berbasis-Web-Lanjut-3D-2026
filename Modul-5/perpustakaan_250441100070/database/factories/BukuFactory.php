<?php

namespace Database\Factories;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Database\Eloquent\Factories\Factory;

class BukuFactory extends Factory
{
    protected $model = Buku::class;

    public function definition(): array
    {
        return [
            // Diisi id kategori yang sudah ada di tabel induk (kategoris),
            // bukan dibuat baru, supaya tidak melanggar foreign key.
            'kategori_id' => Kategori::inRandomOrder()->first()->id,
            'judul' => ucfirst(fake()->words(3, true)),
            'penulis' => fake()->name(),
            'tahun_terbit' => fake()->year(),
        ];
    }
}
