<?php

namespace Database\Factories;

use App\Models\Kategori;
use Illuminate\Database\Eloquent\Factories\Factory;

class BukuFactory extends Factory
{
    public function definition(): array
    {
        return [
            'kategori_id' => Kategori::inRandomOrder()->first()->id ?? Kategori::factory(),
            'judul' => 'Novel '.fake('id_ID')->words(3, true),
            'penulis' => fake('id_ID')->name(),
            'tahun_terbit' => fake()->numberBetween(1980, 2024),
            'deskripsi' => fake('id_ID')->paragraph(3),
        ];
    }
}
