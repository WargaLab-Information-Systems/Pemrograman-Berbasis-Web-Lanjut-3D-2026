<?php

namespace Database\Factories;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Buku>
 */
class BukuFactory extends Factory
{
    protected $model = Buku::class;

    public function definition(): array
    {
        // Data buku awal (yang dulu ada di array controller).
        // fake() memilih satu secara acak dan tidak berulang (unique),
        // jadi Seeder cukup memanggil factory tanpa mengetik data satu per satu.
        // Catatan: jumlah buku yang dibuat dalam satu kali seed maksimal 6 (sesuai isi daftar ini).
        $buku = fake()->unique()->randomElement([
            ['judul' => 'Laskar Pelangi', 'penulis' => 'Andrea Hirata',         'tahun_terbit' => 2005],
            ['judul' => 'Bumi Manusia',   'penulis' => 'Pramoedya Ananta Toer', 'tahun_terbit' => 2002],
            ['judul' => 'Filosofi Teras', 'penulis' => 'Henry Manampiring',     'tahun_terbit' => 2018],
            ['judul' => 'Atomic Habits',  'penulis' => 'James Clear',           'tahun_terbit' => 2018],
            ['judul' => 'Clean Code',     'penulis' => 'Robert C. Martin',      'tahun_terbit' => 2008],
            ['judul' => 'Sapiens',        'penulis' => 'Yuval Noah Harari',     'tahun_terbit' => 2011],
        ]);

        return [
            // Foreign key: id dari data yang sudah ada di tabel kategoris
            'kategori_id'  => Kategori::inRandomOrder()->first()?->id ?? Kategori::factory(),
            'judul'        => $buku['judul'],
            'penulis'      => $buku['penulis'],
            'tahun_terbit' => $buku['tahun_terbit'],
        ];
    }
}
