<?php

namespace Database\Factories;

use App\Models\Petugas;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Petugas>
 */
class PetugasFactory extends Factory
{
    /**
     * Model yang berpasangan dengan factory ini.
     */
    protected $model = Petugas::class;

    /**
     * Mendefinisikan struktur data dummy untuk model Petugas.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nip' => fake()->unique()->numerify('P-####'),
            'nama' => fake()->name(),
        ];
    }
}
