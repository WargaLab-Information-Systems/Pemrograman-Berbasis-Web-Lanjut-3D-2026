<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Buku;

class PeminjamanSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::role('siswa')->first() ?? User::first();
        $buku = Buku::first();

        if ($user && $buku) {
            Peminjaman::create([
                'user_id' => $user->id,
                'buku_id' => $buku->id,
                'tanggal_pinjam' => now()->toDateString(),
                'tanggal_kembali' => null,
                'status' => 'Dipinjam',
            ]);
        }
    }
}