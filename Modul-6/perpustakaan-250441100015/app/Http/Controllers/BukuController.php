<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// modul 6
use App\Models\Buku;
use Illuminate\Support\Facades\DB;
use App\Models\Kategori;
use App\Models\Peminjaman;

// end modul 6

class BukuController extends Controller
{
    private $books = [
        [
            'id' => 1,
            'judul' => 'Bumi',
            'penulis' => 'Tere Liye',
            'tahun' => 2014,
            'kategori' => 'Fantasi',
        ],
        [
            'id' => 2,
            'judul' => 'Bulan',
            'penulis' => 'Tere Liye',
            'tahun' => 2015,
            'kategori' => 'Fantasi',
        ],
        [
            'id' => 3,
            'judul' => 'Matahari',
            'penulis' => 'Tere Liye',
            'tahun' => 2016,
            'kategori' => 'Fantasi',
        ],
        [
            'id' => 4,
            'judul' => 'Hujan',
            'penulis' => 'Tere Liye',
            'tahun' => 2016,
            'kategori' => 'Drama',
        ],
        [
            'id' => 5,
            'judul' => 'Laut Bercerita',
            'penulis' => 'Tere Liye',
            'tahun' => 2017,
            'kategori' => 'Drama',
        ],
    ];
    // modul 6
    // public function index()
    // {
    //     return view('buku.index', [
    //         'books' => $this->books
    //     ]);
    // }
    public function index()
    {
        $books = Buku::with('kategori')->get();
        return view('buku.index', compact('books'));
    }

    public function cari(Request $request)
    {
        $judul = $request->query('judul');

        // Celah A2: Menggabungkan input langsung ke string SQL mentah
        // $hasil = DB::select("SELECT * FROM bukus WHERE judul = '$judul'");
        // kode perbaikan celah A2
        $hasil = DB::select('SELECT * FROM bukus WHERE judul = ?', [$judul]);
        // end kode perbaikan celah A2

        return response()->json($hasil);
    }

    public function create()
    {
        $kategoris = Kategori::all();
        return view('buku.create', compact('kategoris'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer',
        ]);
        Buku::create($validated);
        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function destroy(Buku $buku)
    {
        // Cek apakah buku sedang/pernah dipinjam
        if ($buku->peminjamans()->exists()) {
            return redirect()->route('buku.index')->with('error', 'Buku tidak dapat dihapus karena masih memiliki riwayat peminjaman.');
        }

        $buku->delete();

        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus.');
    }

    // end modul 6


    public function show(int $id)
    {
        $book = collect($this->books)->firstWhere('id', $id);

        return view('buku.show', [
            'book' => $book
        ]);
    }
}
