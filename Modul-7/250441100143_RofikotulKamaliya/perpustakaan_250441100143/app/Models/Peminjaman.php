<?php

namespace App\Models;

use App\Models\Anggota;
use App\Models\Buku;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    public const STATUS_DIAJUKAN = 'diajukan';
    public const STATUS_DIPINJAM = 'dipinjam';
    public const STATUS_DITOLAK = 'ditolak';
    public const STATUS_DIKEMBALIKAN = 'dikembalikan';

    protected $table = 'peminjamans';
    protected $fillable = ['anggota_id', 'buku_id', 'tanggal_pinjam', 'tanggal_kembali', 'status'];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }
}
