<?php

namespace Database\Factories;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PeminjamanFactory extends Factory
{
    public function definition(): array
    {
        $tanggalPinjam = fake()->dateTimeBetween('-6 months', 'now');
        $tanggalKembali = fake()->optional(0.7)->dateTimeBetween($tanggalPinjam, 'now');

        return [
            'user_id' => User::whereHas('roles', fn ($q) => $q->where('name', 'siswa'))
                ->inRandomOrder()
                ->value('id'),
            'anggota_id' => Anggota::inRandomOrder()->value('id'),
            'buku_id' => Buku::inRandomOrder()->value('id'),
            'tanggal_pinjam' => $tanggalPinjam,
            'tanggal_kembali' => $tanggalKembali,
            'status' => $tanggalKembali
                ? Peminjaman::STATUS_DIKEMBALIKAN
                : Peminjaman::STATUS_DIPINJAM,
        ];
    }
}
