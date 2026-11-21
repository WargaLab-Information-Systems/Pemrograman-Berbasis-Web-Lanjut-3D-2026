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
}