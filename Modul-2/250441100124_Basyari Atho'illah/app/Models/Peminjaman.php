<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjamans';

    protected $fillable = [
        'anggota_id',
        'buku_id',
        'tanggal_pinjam',
        'tanggal_kembali',
    ];

    /**
     * Relasi: peminjaman milik satu anggota.
     *
     * @return BelongsTo
     */
    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }

    /**
     * Relasi: peminjaman merujuk ke satu buku.
     *
     * @return BelongsTo
     */
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }
}
