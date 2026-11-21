<?php

namespace App\Policies;

use App\Models\Anggota;
use App\Models\Peminjaman;
use App\Models\User;

class PeminjamanPolicy
{
    public function view(User $user, Peminjaman $peminjaman): bool
    {
        if ($user->hasRole('admin') || $user->hasRole('petugas')) {
            return true;
        }

        if ($user->hasRole('siswa')) {
            $anggota = Anggota::where('email', $user->email)->first();

            return $anggota !== null
                && $peminjaman->anggota_id == $anggota->id;
        }

        return false;
    }
}
