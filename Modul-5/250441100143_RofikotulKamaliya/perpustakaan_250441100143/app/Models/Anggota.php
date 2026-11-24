<?php

namespace App\Models;

use App\Models\Peminjaman;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Anggota extends Model
{
    use HasFactory;

    protected $table = 'anggotas';
    protected $fillable = ['user_id', 'nama', 'email', 'no_telepon'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'anggota_id');
    }
}
