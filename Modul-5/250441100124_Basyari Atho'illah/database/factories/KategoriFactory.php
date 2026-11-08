<?php

namespace Database\Factories;

use App\Models\Kategori;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Kategori>
 */
class KategoriFactory extends Factory
{
    /**
     * Model yang berpasangan dengan factory ini.
     */
    protected $model = Kategori::class;

    /**
     * Mendefinisikan struktur data dummy untuk model Kategori.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->randomElement([
                'Novel',
                'Novel Sejarah',
                'Pengembangan Diri',
                'Ilmu Pengetahuan',
                'Teknologi',
                'Fiksi Ilmiah',
                'Biografi',
                'Pendidikan',
            ]),
        ];
    }
}
