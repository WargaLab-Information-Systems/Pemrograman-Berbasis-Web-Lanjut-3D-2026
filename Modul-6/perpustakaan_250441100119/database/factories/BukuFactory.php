<?php

namespace Database\Factories;

use App\Models\Kategori;
use Illuminate\Database\Eloquent\Factories\Factory;

class BukuFactory extends Factory
{
    public function definition(): array
    {
        return [
            'kategori_id' => Kategori::inRandomOrder()->first()->id,

            'judul' => fake()->randomElement([
                'Laskar Pelangi',
                'Bumi Manusia',
                'Negeri 5 Menara',
                'Filosofi Teras',
                'Atomic Habits',
            ]),

            'penulis' => fake()->randomElement([
                'Andrea Hirata',
                'Pramoedya Ananta Toer',
                'Ahmad Fuadi',
                'Henry Manampiring',
                'James Clear',
            ]),

            'tahun_terbit' => fake()->randomElement([
                2005,
                1980,
                2009,
                2018,
                2018,
            ]),
        ];
    }
}