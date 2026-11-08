<?php

namespace Database\Factories;

use App\Models\Anggota;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Anggota>
 */
class AnggotaFactory extends Factory
{
    /**
     * Model yang berpasangan dengan factory ini.
     */
    protected $model = Anggota::class;

    /**
     * Mendefinisikan struktur data dummy untuk model Anggota.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'no_telepon' => fake()->numerify('08##########'),
        ];
    }
}
