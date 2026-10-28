<?php

namespace Database\Factories;

use App\Models\Buku;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Buku>
 */
class BukuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kategori_id' => fake()->numberBetween(1, 3),
            'judul' => fake()->words(3, true),
            'penulis' => fake()->name(),
            'tahun_terbit' => fake()->numberBetween(2020, 2025),
        ];
    }
}
