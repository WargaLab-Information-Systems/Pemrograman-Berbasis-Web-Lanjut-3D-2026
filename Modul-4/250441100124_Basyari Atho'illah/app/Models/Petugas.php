<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Petugas extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database yang terhubung dengan model ini.
     */
    protected $table = 'petugas';

    /**
     * Kolom-kolom yang diizinkan untuk diisi secara massal (Mass Assignment).
     */
    protected $fillable = [
        'nip',
        'nama',
    ];
}
