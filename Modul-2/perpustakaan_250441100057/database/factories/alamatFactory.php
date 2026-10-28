<?php

namespace Database\Factories;

use App\Models\alamt;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<alamt>
 */
class alamatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'jalan' => fake()->streetAddress(),
            'kecamatan' => fake()->citySuffix(),
            'kota' => fake()->city(),
            'provinsi' => fake()->state(),
            'kode_pos' => fake()->postcode(),  
        ];
    }
}
