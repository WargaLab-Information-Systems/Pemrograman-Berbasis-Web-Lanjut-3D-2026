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
    protected $model = Peminjaman::class;

    public function definition(): array
    {
        $pinjam = fake()->dateTimeBetween('-60 days', '-8 days');

        return [
            'anggota_id' => Anggota::inRandomOrder()->value('id'),
            'buku_id' => Buku::inRandomOrder()->value('id'),
            'tanggal_pinjam' => $pinjam->format('Y-m-d'),
            'tanggal_kembali' => fake()->boolean(60)
                ? (clone $pinjam)->modify('+' . fake()->numberBetween(3, 7) . ' days')->format('Y-m-d')
                : null,
        ];
    }
}
