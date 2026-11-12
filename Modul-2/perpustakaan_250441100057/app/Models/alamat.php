<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class alamat extends Model
{
    use HasFactory;
    protected $table = 'alamat';

    protected $fillable = [
        'jalan',
        'kecamatan',
        'kota',
        'provinsi',
        'kode_pos'
    ];
}