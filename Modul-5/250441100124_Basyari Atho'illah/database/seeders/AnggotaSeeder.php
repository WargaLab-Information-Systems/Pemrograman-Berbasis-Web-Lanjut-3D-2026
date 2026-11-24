<?php

namespace Database\Seeders;

use App\Models\Anggota;
use Illuminate\Database\Seeder;

class AnggotaSeeder extends Seeder
{
    /**
     * Mengisi database dengan data dummy untuk tabel anggotas.
     */
    public function run(): void
    {
        // Membuat 5 data dummy anggota perpustakaan
        Anggota::factory()->count(5)->create();
    }
}
