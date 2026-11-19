<?php

namespace Database\Factories;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Database\Eloquent\Factories\Factory;

class PeminjamanFactory extends Factory
{
    protected $model = Peminjaman::class;

    public function definition(): array
    {
        $kembali = fake()->optional(0.5)
            ->dateTimeBetween('now', '+1 month')?->format('Y-m-d');

        return [
            'anggota_id' => Anggota::inRandomOrder()->first()->id,
            'buku_id' => Buku::inRandomOrder()->first()->id,
            'tanggal_pinjam' => fake()->dateTimeBetween('-2 months', 'now')->format('Y-m-d'),
            'tanggal_kembali' => $kembali,
            // Modul 5: status mengikuti ada/tidaknya tanggal kembali
            'status' => $kembali ? Peminjaman::DIKEMBALIKAN : Peminjaman::DIPINJAM,
        ];
    }
}
