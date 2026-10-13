<?php

namespace Database\Factories;

use App\Models\Kampus; // Perbaiki ini
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Kampus>
 */
class KampusFactory extends Factory
{
    protected $model = Kampus::class;

    public function definition(): array
    {
        return [
            'nama_gedung' => fake()->randomElement([
                'Gedung A',
                'Gedung B',
                'Gedung C',
                'Gedung D',
                'Gedung E',
                'Gedung F',
            ]),
        ];
    }
}
