<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Database\Seeder;

class PeminjamanSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        $buku = Buku::first();

        if ($user && $buku) {
            Peminjaman::create([
                'user_id' => $user->id,
                'buku_id' => $buku->id,
                'tanggal_pinjam' => '2026-11-03',
                'tanggal_kembali' => '2026-11-29',
                'status' => 'dipinjam',
            ]);
        }
    }
}