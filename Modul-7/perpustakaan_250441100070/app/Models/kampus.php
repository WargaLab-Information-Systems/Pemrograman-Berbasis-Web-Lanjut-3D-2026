<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kampus extends Model
{
    use HasFactory;

    protected $table = 'gedung';
    public $timestamps = false; // Tambahkan baris ini

    protected $fillable = [
        'nama_gedung',
    ];
}