<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AnggotaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama'  => fake()->name(),
            'nim'   => fake()->unique()->numerify('250441100###'),
            'email' => fake()->unique()->safeEmail(),
        ];
    }
}