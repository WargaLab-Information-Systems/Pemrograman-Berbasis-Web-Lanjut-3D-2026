<?php

namespace Database\Factories;

use App\Models\Anggota;
use App\Models\Buku;
use Illuminate\Database\Eloquent\Factories\Factory;

class PeminjamanFactory extends Factory
{
    public function definition(): array
    {
        $kembali = fake()->optional()->date();

        return [
            'anggota_id' => Anggota::factory(),
            'buku_id' => Buku::factory(),
            'tanggal_pinjam' => fake()->date(),
            'tanggal_kembali' => $kembali,
            'status' => $kembali ? 'dikembalikan' : 'dipinjam',
        ];
    }
}