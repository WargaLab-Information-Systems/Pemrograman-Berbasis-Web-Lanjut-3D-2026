<?php

namespace Database\Seeders;

use App\Models\Petugas;
use Illuminate\Database\Seeder;

class PetugasSeeder extends Seeder
{
    /**
     * Mengisi database dengan data dummy untuk tabel petugas.
     */
    public function run(): void
    {
        // Membuat 5 data dummy petugas
        Petugas::factory()->count(5)->create();
    }
}
