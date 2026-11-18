<?php

namespace App\Policies;

use App\Models\Peminjaman;
use App\Models\User;

class PeminjamanPolicy
{
    /**
     * Determine whether the user can view the peminjaman model.
     * Admin dan petugas boleh melihat semua peminjaman.
     * Siswa hanya boleh melihat peminjaman miliknya sendiri.
     */
    public function view(User $user, Peminjaman $peminjaman): bool
    {
        // Admin dan Petugas diizinkan melihat semua peminjaman
        if ($user->hasRole(['admin', 'petugas']) || in_array($user->role, ['admin', 'petugas'])) {
            return true;
        }

        // Siswa hanya boleh melihat peminjaman miliknya sendiri (mencegah IDOR)
        return ($peminjaman->user_id === $user->id) ||
               ($user->anggota && $peminjaman->anggota_id === $user->anggota->id);
    }
}
