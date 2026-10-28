<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Anggota extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database yang terhubung dengan model ini.
     */
    protected $table = 'anggotas';

    /**
     * Kolom-kolom yang diizinkan untuk diisi secara massal (Mass Assignment).
     */
    protected $fillable = [
        'nama',
        'email',
        'no_telepon',
    ];

    /**
     * Relasi One-to-Many: Satu anggota dapat memiliki banyak riwayat peminjaman.
     */
    public function peminjamans(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'anggota_id');
    }
}
