<?php

namespace App\Http\Controllers; 

class BukuController extends Controller
{
    private $buku = [
        [
            'id' => 1,
            'judul' => 'LASKAR PELANGI',
            'penulis' => 'Andrea Hirata',
            'tahun' => 2005,
            'kategori' => 'Novel',
            'gambar' => 'images/buku/laskar-pelangi.jpeg'
        ],
        [
            'id' => 2,
            'judul' => 'BUMI MANUSIA',
            'penulis' => 'Pramoedya Ananta Toer',
            'tahun' => 1980,
            'kategori' => 'Sejarah',
            'gambar' => 'images/buku/bumi-manusia.jpeg'
        ],
        [
            'id' => 3,
            'judul' => 'A SONG OF ICE AND FIRE',
            'penulis' => 'GEORGE RR MARTIN',
            'tahun' => 1996,
            'kategori' => 'Novel',
            'gambar' => 'images/buku/Asoiaf.jpg'
        ],
        [
            'id' => 4,
            'judul' => 'THE SILMALIRION',
            'penulis' => 'J.R.R. TOLKIEN',
            'tahun' => 1997,
            'kategori' => 'Novel',
            'gambar' => 'images/buku/Silmaliron.jpeg'
        ],
        [
            'id' => 5,
            'judul' => 'DON QUIXOTE',
            'penulis' => 'Miguel DE Cervantes',
            'tahun' => 1615,
            'kategori' => 'Novel',
            'gambar' => 'images/buku/Don-Quixote.jpeg'
        ],
    ];

    public function index()
    {
        return view('buku.index', [
            'buku' => $this->buku
        ]);
    }

    public function show($id)
    {
        $buku = null;

        foreach ($this->buku as $item) {
            if ($item['id'] == $id) {
                $buku = $item;
                break;
            }
        }

        return view('buku.detail', [
            'buku' => $buku
        ]);
    }
}

