<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Anggota;
use app\models\Buku;
use app\models\Kategori;
use app\modells\peminjamans;

class Gabunganseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
    }
}

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            KategoriSeeder::class,
            AnggotaSeeder::class,
            BukuSeeder::class,
            PeminjamanSeeder::class,
        ]);
    }
}

class AnggotaSeeder extends Seeder
{
    public function run(): void
    {
        Anggota::factory(3)->create();
    }
}

class BukuSeeder extends Seeder
{
    public function run(): void
    {
        Buku::factory(5)->create();
    }
}

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        Kategori::factory(3)->create();
    }
}

class PeminjamanSeeder extends Seeder
{
    public function run(): void
    {
        Peminjaman::factory(3)->create();
    }
}