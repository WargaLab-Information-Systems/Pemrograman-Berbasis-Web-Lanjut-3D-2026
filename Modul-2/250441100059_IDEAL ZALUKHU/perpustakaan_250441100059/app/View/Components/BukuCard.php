<?php

namespace App\View\Components;

use Illuminate\View\Component;

class BukuCard extends Component
{
    public $id;
    public $judul;
    public $penulis;
    public $tahun;

    public function __construct($id, $judul, $penulis, $tahun)
    {
        $this->id = $id;
        $this->judul = $judul;
        $this->penulis = $penulis;
        $this->tahun = $tahun;
    }

    public function render()
    {
        return view('components.buku-card');
    }
}