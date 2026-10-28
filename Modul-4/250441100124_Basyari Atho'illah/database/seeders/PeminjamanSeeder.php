<?php

namespace Database\Seeders;

use App\Models\Peminjaman;
use Illuminate\Database\Seeder;

class PeminjamanSeeder extends Seeder
{
    /**
     * Mengisi database dengan data dummy untuk tabel peminjamans.
     */
    public function run(): void
    {
        // Membuat 10 data dummy riwayat peminjaman buku
        Peminjaman::factory()->count(10)->create();
    }
}
