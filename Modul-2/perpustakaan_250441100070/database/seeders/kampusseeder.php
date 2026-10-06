<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kampus; // Import model Kampus

class kampusseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Masukkan data dummy menggunakan factory (contoh: buat 5 data gedung)
        Kampus::factory()->count(5)->create();
    }
}
