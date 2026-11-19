<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BukuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bukus = [
            [
                'kategori' => 'Fiksi',
                'judul' => 'Laskar Pelangi',
                'penulis' => 'Andrea Hirata',
                'tahun_terbit' => '2005',
            ],
            [
                'kategori' => 'Fiksi',
                'judul' => 'The Great Gatsby',
                'penulis' => 'F. Scott Fitzgerald',
                'tahun_terbit' => '1925',
            ],
            [
                'kategori' => 'Nonfiksi',
                'judul' => 'Filosofi Teras',
                'penulis' => 'Henry Manampiring',
                'tahun_terbit' => '2018',
            ],
            [
                'kategori' => 'Nonfiksi',
                'judul' => 'Sapiens',
                'penulis' => 'Yuval Noah Harari',
                'tahun_terbit' => '2011',
            ],
            [
                'kategori' => 'Teknologi',
                'judul' => 'Clean Code',
                'penulis' => 'Robert C. Martin',
                'tahun_terbit' => '2008',
            ],
            [
                'kategori' => 'Teknologi',
                'judul' => 'The Pragmatic Programmer',
                'penulis' => 'Andrew Hunt, David Thomas',
                'tahun_terbit' => '1999',
            ],
            [
                'kategori' => 'Pendidikan',
                'judul' => 'How Children Succeed',
                'penulis' => 'Paul Tough',
                'tahun_terbit' => '2012',
            ],
            [
                'kategori' => 'Pendidikan',
                'judul' => 'Mindset: The New Psychology of Success',
                'penulis' => 'Carol S. Dweck',
                'tahun_terbit' => '2006',
            ],
            [
                'kategori' => 'Sejarah',
                'judul' => 'Guns, Germs, and Steel',
                'penulis' => 'Jared Diamond',
                'tahun_terbit' => '1997',
            ],
            [
                'kategori' => 'Sejarah',
                'judul' => "A People's History of the United States",
                'penulis' => "Howard Zinn",
                'tahun_terbit' => "1980",
            ],
        ];
        foreach ($bukus as $buku) {
            $kategori = Kategori::where(
                'nama',
                $buku['kategori']
            )->firstOrFail();
            Buku::updateOrCreate(
                ['judul' => $buku['judul']],
                [
                    'kategori_id' => $kategori->id,
                    'penulis' => $buku['penulis'],
                    'tahun_terbit' => $buku['tahun_terbit'],
                ]
            );
        }
    }
}
