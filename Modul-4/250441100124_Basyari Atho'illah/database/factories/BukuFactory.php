<?php

namespace Database\Factories;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Buku>
 */
class BukuFactory extends Factory
{
    /**
     * Model yang berpasangan dengan factory ini.
     */
    protected $model = Buku::class;

    /**
     * Mendefinisikan struktur data dummy untuk model Buku.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Mengambil ID Kategori yang sudah ada secara acak, atau membuat Kategori baru jika tabel masih kosong
            'kategori_id' => Kategori::inRandomOrder()->value('id') ?? Kategori::factory(),
            'judul' => fake()->sentence(3, true),
            'penulis' => fake()->name(),
            'tahun_terbit' => fake()->numberBetween(2000, 2026),
        ];
    }
}
