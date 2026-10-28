<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Buku;

class BukuController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::all();
        return view('buku.index', compact('kategoris'));
    }

    public function show($id)
    {
        return view('buku.show', compact('id'));
    }
}