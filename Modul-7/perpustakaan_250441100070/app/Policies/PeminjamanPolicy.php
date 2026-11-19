<?php

namespace App\Policies;

use App\Models\Peminjaman;
use App\Models\User;

/**
 * Soal 4: menutup celah A4 (IDOR) pada GET /peminjaman/{peminjaman}.
 * Admin dan petugas boleh melihat peminjaman siapa pun; siswa hanya boleh
 * melihat peminjaman miliknya sendiri, dicek lewat identitas pengguna yang
 * sedang login (anggota->user_id), bukan hanya lewat role atau permission
 * -- role saja tidak cukup, karena "siswa" tidak membedakan siswa A dari
 * siswa B; yang membedakan adalah KEPEMILIKAN data, dan itu yang diperiksa
 * Policy ini pada setiap pemanggilan, bukan sekali di level middleware.
 */
class PeminjamanPolicy
{
    public function view(User $user, Peminjaman $peminjaman): bool
    {
        if ($user->hasAnyRole(['admin', 'petugas'])) {
            return true;
        }

        return $peminjaman->anggota?->user_id === $user->id;
    }
}
