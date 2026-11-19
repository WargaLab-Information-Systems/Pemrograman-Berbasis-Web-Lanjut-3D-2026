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
        if (method_exists($user, 'hasAnyRole')) {
            if ($user->hasAnyRole(['admin', 'petugas'])) {
                return true;
            }
        } else {
            $role = strtolower((string) ($user->role ?? ''));
            if (in_array($role, ['admin', 'petugas'], true)) {
                return true;
            }
        }

        if ((int) $peminjaman->user_id === (int) $user->id) {
            return true;
        }

        if ($user->anggota && (int) $peminjaman->anggota_id === (int) $user->anggota->id) {
            return true;
        }

        return false;
    }
}