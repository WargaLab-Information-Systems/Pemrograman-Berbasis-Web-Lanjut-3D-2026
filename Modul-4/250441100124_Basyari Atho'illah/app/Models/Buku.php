<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Buku extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database yang terhubung dengan model ini.
     */
    protected $table = 'bukus';

    /**
     * Kolom-kolom tabel yang diizinkan untuk diisi secara massal (Mass Assignment).
     */
    protected $fillable = [
        'kategori_id',
        'judul',
        'penulis',
        'tahun_terbit',
    ];

    /**
     * Accessor: Menghubungkan nama atribut '$book->tahun' atau '$book[\'tahun\']'
     * langsung ke kolom 'tahun_terbit' agar view tetap kompatibel.
     */
    protected function tahun(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->tahun_terbit,
        );
    }

    /**
     * Accessor: Mempermudah akses nama kategori via '$book->kategori' atau '$book[\'kategori\']'
     * dengan mengambil nama kategori dari relasi yang sudah dimuat.
     */
    protected function kategori(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->relationLoaded('kategoriRelasi')
                ? $this->kategoriRelasi?->nama
                : ($this->kategoriRelasi()->first()?->nama ?? '-'),
        );
    }

    /**
     * Relasi Many-to-One: Setiap buku dimiliki oleh satu kategori (FK: kategori_id).
     */
    public function kategoriRelasi(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    /**
     * Relasi One-to-Many: Satu buku dapat memiliki banyak riwayat peminjaman.
     */
    public function peminjamans(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'buku_id');
    }
}
