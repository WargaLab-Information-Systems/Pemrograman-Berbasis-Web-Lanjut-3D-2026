<?php

namespace App\Policies;

use App\Models\Peminjaman;
use App\Models\User;

class PeminjamanPolicy
{
    /**
     * Data peminjaman hanya boleh dilihat oleh:
     * - pemiliknya sendiri (dicek lewat identitas user yang login), atau
     * - pengguna yang punya permission "kelola peminjaman" (admin & petugas).
     */
    public function view(User $user, Peminjaman $peminjaman): bool
    {
        if ($user->can('kelola peminjaman')) {
            return true;
        }

        $anggota = $user->anggota;

        return $user->can('melihat riwayat peminjaman sendiri')
            && $anggota !== null
            && $peminjaman->anggota_id === $anggota->id;
    }

    /**
     * Pengajuan hanya boleh dibatalkan oleh pemiliknya, dan hanya selama
     * statusnya masih "diajukan" (belum diproses petugas).
     */
    public function batalkan(User $user, Peminjaman $peminjaman): bool
    {
        $anggota = $user->anggota;

        return $user->can('mengajukan peminjaman buku')
            && $anggota !== null
            && $peminjaman->anggota_id === $anggota->id
            && $peminjaman->status === Peminjaman::STATUS_DIAJUKAN;
    }
}
