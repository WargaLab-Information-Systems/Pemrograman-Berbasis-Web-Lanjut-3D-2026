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
     * Model yang berpasangan dengan factory ini.
     */
    protected $model = Peminjaman::class;

    /**
     * Mendefinisikan struktur data dummy untuk model Peminjaman.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Menghasilkan tanggal pinjam dalam kurun waktu 3 bulan terakhir
        $tanggalPinjam = fake()->dateTimeBetween('-3 months', 'now');

        return [
            // Mengambil anggota_id & buku_id yang ada, atau otomatis membuat data baru jika belum ada
            'anggota_id' => Anggota::inRandomOrder()->value('id') ?? Anggota::factory(),
            'buku_id' => Buku::inRandomOrder()->value('id') ?? Buku::factory(),
            'tanggal_pinjam' => $tanggalPinjam->format('Y-m-d'),
            // 70% kemungkinan buku sudah dikembalikan, 30% masih sedang dipinjam (null)
            'tanggal_kembali' => fake()->boolean(70)
                ? fake()->dateTimeBetween($tanggalPinjam, 'now')->format('Y-m-d')
                : null,
        ];
    }
}
