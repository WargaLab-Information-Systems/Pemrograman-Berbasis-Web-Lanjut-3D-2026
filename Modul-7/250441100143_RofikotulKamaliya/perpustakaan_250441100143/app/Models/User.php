<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function booted(): void
    {
        // Setiap akun baru otomatis punya data anggota (dipakai untuk peminjaman).
        static::created(function (User $user) {
            $user->ensureAnggota();
        });

        // Nama & email anggota ikut diperbarui saat profil pengguna berubah.
        static::updated(function (User $user) {
            if ($user->wasChanged(['name', 'email'])) {
                $user->anggota()->update([
                    'nama' => $user->name,
                    'email' => $user->email,
                ]);
            }
        });
    }

    public function anggota(): HasOne
    {
        return $this->hasOne(Anggota::class, 'user_id');
    }

    /**
     * Ambil data anggota milik pengguna ini, buat jika belum ada.
     */
    public function ensureAnggota(): Anggota
    {
        return $this->anggota()->firstOrCreate([], [
            'nama' => $this->name,
            'email' => $this->email,
            'no_telepon' => '-',
        ]);
    }
}
