<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\bebas;
use Illuminate\Database\Seeder;

class BebasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Bebas::factory()->count(2)->create();
    }
}
