<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BukuCard extends Component
{
    public mixed $book;

    public function __construct(mixed $book)
    {
        $this->book = $book;
    }

    public function render(): View|Closure|string
    {
        return view('components.buku-card');
    }
}
