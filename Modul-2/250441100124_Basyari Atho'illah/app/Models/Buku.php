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

    protected $table = 'bukus';

    protected $fillable = [
        'kategori_id',
        'judul',
        'penulis',
        'tahun_terbit',
    ];

    /**
     * Accessor: $book['tahun'] mengembalikan nilai tahun_terbit.
     * Agar view lama yang mengakses $book['tahun'] tetap berjalan tanpa perubahan.
     */
    protected function tahun(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->tahun_terbit,
        );
    }

    /**
     * Accessor: $book['kategori'] mengembalikan nama kategori dari relasi.
     * Agar view lama yang mengakses $book['kategori'] tetap berjalan tanpa perubahan.
     */
    protected function kategori(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->relationLoaded('kategori')
                ? optional($this->getRelation('kategori'))->nama
                : optional($this->kategoriRelasi)->nama,
        );
    }

    /**
     * Relasi: buku milik satu kategori.
     *
     * @return BelongsTo
     */
    public function kategoriRelasi()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    /**
     * Relasi: satu buku dapat dipinjam banyak kali.
     *
     * @return HasMany
     */
    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'buku_id');
    }
}
