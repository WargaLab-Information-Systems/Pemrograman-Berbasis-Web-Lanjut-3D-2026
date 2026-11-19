<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Riwayat peminjaman hanya boleh dilihat oleh pemiliknya sendiri,
     * atau oleh pengguna yang berhak mengelola peminjaman (admin/petugas).
     * Pemeriksaan memakai identitas pengguna yang sedang login, bukan hanya permission.
     */
    public function viewRiwayat(User $pengguna, User $target): bool
    {
        return $pengguna->id === $target->id
            || $pengguna->hasPermissionTo('kelola peminjaman');
    }
}
