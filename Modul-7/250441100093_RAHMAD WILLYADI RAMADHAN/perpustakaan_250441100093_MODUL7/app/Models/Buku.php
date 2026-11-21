<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $guarded = []; 

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    // Relasi untuk mengecek siapa yang sedang meminjam buku ini
    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'buku_id');
    }
}