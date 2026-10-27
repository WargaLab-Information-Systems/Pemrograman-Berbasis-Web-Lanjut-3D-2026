<?php

namespace App\Models;

use App\Models\Buku;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategoris';
    protected $fillable = ['nama'];

    public function bukus()
    {
        return $this->hasMany(Buku::class, 'kategori_id');
    }
}
