<?php

namespace Database\Factories;

use App\Models\Kategori;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Kategori>
 */
class KategoriFactory extends Factory
{
    protected $model = Kategori::class;

    public function definition(): array
    {
        return [
            // Dipilih acak dan tidak berulang; maksimal 5 kategori dalam satu kali seed
            'nama' => fake()->unique()->randomElement([
                'Novel',
                'Sastra',
                'Pengembangan Diri',
                'Teknologi',
                'Sejarah',
            ]),
        ];
    }
}
