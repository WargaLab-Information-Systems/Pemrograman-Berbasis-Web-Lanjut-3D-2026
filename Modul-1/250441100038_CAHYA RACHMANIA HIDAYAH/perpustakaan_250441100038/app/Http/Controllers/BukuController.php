<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index()
    {
        $books = Buku::with('kategori')->get();
        
        $booksArray = $books->map(function($book) {
            return [
                'id' => $book->id,
                'judul' => $book->judul,
                'penulis' => $book->penulis,
                'tahun_terbit' => $book->tahun_terbit,
                'kategori' => $book->kategori ? $book->kategori->nama : '-'
            ];
        })->toArray();

        return view('buku.index', ['books' => $booksArray]);
    }

    public function show($id)
    {
        $bookModel = Buku::with('kategori')->find($id);

        $book = null;
        if ($bookModel) {
            $book = [
                'id' => $bookModel->id,
                'judul' => $bookModel->judul,
                'penulis' => $bookModel->penulis,
                'tahun_terbit' => $bookModel->tahun_terbit,
                'kategori' => $bookModel->kategori ? $bookModel->kategori->nama : '-'
            ];
        }

        return view('buku.show', ['book' => $book]);
    }
}