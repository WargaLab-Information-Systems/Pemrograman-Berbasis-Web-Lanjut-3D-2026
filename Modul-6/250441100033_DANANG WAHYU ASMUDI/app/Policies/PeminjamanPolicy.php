<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Peminjaman;

class PeminjamanPolicy
{
    /**
     * Menentukan apakah pengguna boleh melihat detail peminjaman buku tertentu.
     * 
     * Sesuai SOAL 4:
     * - Admin dan Petugas boleh melihat semua data peminjaman siapa pun.
     * - Siswa HANYA boleh melihat data peminjaman miliknya sendiri.
     */
    public function view(User $user, Peminjaman $peminjaman): bool
    {
        // 1. Role Admin dan Petugas diizinkan mengakses semua peminjaman
        if ($user->hasRole(['admin', 'petugas'])) {
            return true;
        }

        // 2. Siswa hanya diizinkan melihat peminjaman miliknya sendiri (mencegah celah IDOR)
        if ($peminjaman->anggota && $peminjaman->anggota->email === $user->email) {
            return true;
        }

        return false;
    }
}
