<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    // Nama Model (Kategori) tidak otomatis cocok dengan nama tabel (kategoris),
    // jadi dihubungkan manual lewat $table.
    protected $table = 'kategoris';

    protected $fillable = [
        'nama',
    ];

    public function bukus()
    {
        return $this->hasMany(Buku::class, 'kategori_id');
    }
}
