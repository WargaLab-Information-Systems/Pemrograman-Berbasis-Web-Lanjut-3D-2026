<?php

namespace App\Policies;

use App\Models\Peminjaman;
use App\Models\User;

class PeminjamanPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Peminjaman $peminjaman): bool
    {
        // Admin dan petugas boleh melihat semua data peminjaman
        if ($user->hasAnyRole(['admin', 'petugas'])) {
            return true;
        }

        // Siswa HANYA boleh melihat data peminjaman miliknya sendiri
        if ($user->hasRole('siswa')) {
            return $user->id === $peminjaman->anggota_id;
        }

        return false;
    }
}
