<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Peminjaman extends Model
{
    protected $table = 'peminjamans'; // ganti sesuai hasil di atas

    public function buku(): BelongsTo
    {
        return $this->belongsTo(Buku::class);
    }
}