<?php

namespace App\Policies;

use App\Models\Peminjaman;
use App\Models\User;

class PeminjamanPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
    public function view(User $user, Peminjaman $peminjaman): bool
    {
        // Admin dan Petugas berhak melihat peminjaman siapapun
        if ($user->hasRole(['admin', 'petugas'])) {
            return true;
        }
        // Siswa HANYA boleh melihat peminjaman miliknya sendiri
        return $peminjaman->anggota && $peminjaman->anggota->user_id === $user->id;
    }
}
