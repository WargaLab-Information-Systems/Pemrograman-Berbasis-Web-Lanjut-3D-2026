<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BukuFactory extends Factory
{
    public function definition(): array
    {
        return [
            'kategori_id' => fake()->numberBetween(1, 5),
            'judul' => fake()->sentence(3),
            'penulis' => fake()->name(),
            'tahun_terbit' => fake()->numberBetween(1901, 2026),
        ];
    }
}