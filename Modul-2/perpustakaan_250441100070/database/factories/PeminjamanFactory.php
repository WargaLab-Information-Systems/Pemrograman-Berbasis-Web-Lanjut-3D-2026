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
        return [
            // FK diisi dari id yang sudah ada di tabel induknya masing-masing.
            'anggota_id' => Anggota::inRandomOrder()->first()->id,
            'buku_id' => Buku::inRandomOrder()->first()->id,
            'tanggal_pinjam' => fake()->dateTimeBetween('-2 months', 'now')->format('Y-m-d'),
            // 50% kemungkinan sudah dikembalikan, sisanya null (belum kembali)
            'tanggal_kembali' => fake()->optional(0.5)
                ->dateTimeBetween('now', '+1 month')?->format('Y-m-d'),
        ];
    }
}
