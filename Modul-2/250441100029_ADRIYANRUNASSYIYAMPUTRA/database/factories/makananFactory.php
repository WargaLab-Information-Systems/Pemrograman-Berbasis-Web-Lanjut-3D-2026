<?php

namespace Database\Factories;

use App\Models\makanan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<makanan>
 */
class makananFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama'=> fake()->name()
        ];
    }
}
