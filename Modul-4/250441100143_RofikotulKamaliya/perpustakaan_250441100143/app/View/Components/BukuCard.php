<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BukuCard extends Component
{
    public function __construct(
        public $id,
        public $judul,
        public $penulis,
        public $tahun
    ) {
        //
    }

    public function render(): View|Closure
    {
        return view('components.buku-card');
    }
}