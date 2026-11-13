<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory;

    // Menghubungkan Model ke tabelnya secara manual
    protected $table = 'anggotas';

    // Properti $fillable untuk semua kolom kecuali id
    protected $fillable = [
        'nama',
        'email',
        'no_telepon',
    ];
}