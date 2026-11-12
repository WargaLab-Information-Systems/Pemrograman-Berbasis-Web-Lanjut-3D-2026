<?php

namespace Database\Factories;

use App\Models\Peminjaman;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Peminjaman>
 */
class PeminjamanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'anggota_id' => fake()->numberBetween(1, 3),
            'buku_id' => fake()->numberBetween(1, 5),
            'tanggal_pinjam' => fake()->date(),
            'tanggal_kembali' => fake()->date(),
        ];
    }
}
