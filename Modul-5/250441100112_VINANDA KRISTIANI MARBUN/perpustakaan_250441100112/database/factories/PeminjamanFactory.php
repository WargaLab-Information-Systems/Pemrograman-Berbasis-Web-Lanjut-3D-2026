<?php

namespace Database\Factories;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Peminjaman>
 */
class PeminjamanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tanggalPinjam = $this->faker->dateTimeBetween('-1 month', 'now');

        return [
            'anggota_id' => Anggota::query()->inRandomOrder()->value('id') ?? Anggota::factory(),
            'buku_id' => Buku::query()->inRandomOrder()->value('id') ?? Buku::factory(),
            'tanggal_pinjam' => $tanggalPinjam->format('Y-m-d'),
            'tanggal_kembali' => $this->faker->optional(0.7)
                ->dateTimeBetween($tanggalPinjam, 'now')
                ?->format('Y-m-d'),
        ];
    }
}
