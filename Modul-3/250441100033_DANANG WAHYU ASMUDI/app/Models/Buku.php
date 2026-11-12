<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'bukus';
    protected $fillable = ['kategori_id', 'judul', 'penulis', 'tahun_terbit'];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'buku_id');
    }
}

// tinker
// $buku = App\Models\Buku::create([
//     'id' => 9999,
//     'kategori_id' => 1,
//     'judul' => 'Tes Mass Assignment',
//     'penulis' => 'Budi Santoso',
//     'tahun_terbit' => 2025,
//     'created_at' => '2000-01-01 00:00:00'
// ]);

// $buku->id;

// $buku->created_at;

// exit;