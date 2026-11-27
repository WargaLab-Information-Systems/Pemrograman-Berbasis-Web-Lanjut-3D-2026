<?php

namespace Database\Factories;

use App\Models\Anggota;
use App\Models\Buku;
use Illuminate\Database\Eloquent\Factories\Factory;

class PeminjamanFactory extends Factory
{
    public function definition(): array
    {
        $pinjam = fake()->dateTimeBetween('-1 month', 'now');
        return [
            'anggota_id'      => Anggota::inRandomOrder()->first()->id ?? Anggota::factory(),
            'buku_id'         => Buku::inRandomOrder()->first()->id ?? Buku::factory(),
            'tanggal_pinjam'  => $pinjam->format('Y-m-d'),
            'tanggal_kembali' => fake()->boolean(70) ? fake()->dateTimeBetween($pinjam, '+1 month')->format('Y-m-d') : null,
        ];
    }
}