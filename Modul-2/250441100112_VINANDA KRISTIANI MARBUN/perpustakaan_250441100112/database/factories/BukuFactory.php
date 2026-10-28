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
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        
        $daftarBuku = [
            [
                'judul' => 'Laskar Pelangi',
                'penulis' => 'Andrea Hirata',
                'tahun_terbit' => 2008,
                'kategori_nama' => 'Novel'
            ],
            [
                'judul' => 'Bumi Manusia',
                'penulis' => 'Pramoedya Ananta Toer',
                'tahun_terbit' => 1980,
                'kategori_nama' => 'Fiksi Sejarah'
            ],
            [
                'judul' => 'Filosofi Teras',
                'penulis' => 'Henry Manampiring',
                'tahun_terbit' => 2018,
                'kategori_nama' => 'Pengembangan Diri'
            ],
            [
                'judul' => 'Negeri 5 Menara',
                'penulis' => 'Ahmad Fuadi',
                'tahun_terbit' => 2009,
                'kategori_nama' => 'Novel'
            ],
            [
                'judul' => 'Perahu Kertas',
                'penulis' => 'Dee Lestari',
                'tahun_terbit' => 2009,
                'kategori_nama' => 'Novel'
            ],
        ];

        
        $buku = fake()->randomElement($daftarBuku);

        // Cari kategori berdasarkan nama, jika belum ada maka buat baru, lalu ambil ID-nya
        $kategori = Kategori::firstOrCreate([
            'nama' => $buku['kategori_nama']
        ]);

        return [
            'kategori_id' => $kategori->id, // Menjamin 1 buku mendapatkan 1 ID kategori yang valid
            'judul' => $buku['judul'],
            'penulis' => $buku['penulis'],
            'tahun_terbit' => $buku['tahun_terbit'],
        ];
    }
}