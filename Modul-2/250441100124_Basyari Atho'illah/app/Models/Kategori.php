<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategoris';

    protected $fillable = [
        'nama',
    ];

    /**
     * Relasi: satu kategori memiliki banyak buku.
     *
     * @return HasMany
     */
    public function bukus()
    {
        return $this->hasMany(Buku::class, 'kategori_id');
    }
}
