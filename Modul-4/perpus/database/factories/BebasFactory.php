<?php

namespace Database\Factories;

use App\Models\Bebas;
use Illuminate\Database\Eloquent\Factories\Factory;


class BebasFactory extends Factory
{

    public function definition(): array
    {
        return [
            'nama'=>fake()->name()
        ];
    }
}
