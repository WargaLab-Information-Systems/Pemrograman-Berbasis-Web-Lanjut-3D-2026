<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bebas extends Model
{
    use HasFactory;

    protected $table = 'table_bebas';
    protected $fillable = ['nama'];

}
