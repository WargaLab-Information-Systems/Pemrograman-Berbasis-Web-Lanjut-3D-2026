<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Peminjaman extends Model
{
    use HasFactory;

    public const DIAJUKAN = 'diajukan';
    public const DIPINJAM = 'dipinjam';
    public const DITOLAK = 'ditolak';
    public const DIKEMBALIKAN = 'dikembalikan';

    protected $table = 'peminjamans';

    protected $fillable = [
        'anggota_id',
        'buku_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',
        'diproses_oleh',
    ];

    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }

    public function buku(): BelongsTo
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }

    /** Petugas/admin yang memproses pengajuan. */
    public function pemroses(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diproses_oleh');
    }

    // Format tanggal untuk tampilan (kolom dibiarkan string agar JSON API Modul 4 tidak berubah).
    public function tglPinjam(): string
    {
        return $this->tanggal_pinjam ? Carbon::parse($this->tanggal_pinjam)->format('d/m/Y') : '-';
    }

    public function tglKembali(): string
    {
        return $this->tanggal_kembali ? Carbon::parse($this->tanggal_kembali)->format('d/m/Y') : '-';
    }
}
