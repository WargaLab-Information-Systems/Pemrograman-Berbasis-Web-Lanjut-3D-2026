<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

// Kolom role TIDAK ada di daftar Fillable: role tidak bisa diisi lewat form biasa.
#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed', // kata sandi otomatis di-hash saat disimpan
        ];
    }

    /** Data keanggotaan milik akun ini. */
    public function anggota(): HasOne
    {
        return $this->hasOne(Anggota::class, 'user_id');
    }

    /** Seluruh peminjaman milik akun ini (lewat tabel anggotas). */
    public function peminjamans(): HasManyThrough
    {
        return $this->hasManyThrough(Peminjaman::class, Anggota::class, 'user_id', 'anggota_id');
    }

    /** Ambil data keanggotaan; dibuat otomatis jika belum ada. */
    public function profilAnggota(): Anggota
    {
        return $this->anggota()->firstOrCreate([], [
            'nama' => $this->name,
            'email' => $this->email,
        ]);
    }
}
