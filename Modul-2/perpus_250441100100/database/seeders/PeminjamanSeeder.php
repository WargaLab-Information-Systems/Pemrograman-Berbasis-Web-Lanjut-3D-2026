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
        Peminjaman::factory()->count(3)->create();

        // Data contoh agar riwayat dan status akun siswa uji tidak kosong.
        $siswa = User::where('email', 'siswa@perpus.com')->first();
        $bukuIds = Buku::orderBy('id')->pluck('id');

        if ($siswa && $bukuIds->count() >= 3) {
            Peminjaman::create([
                'user_id' => $siswa->id,
                'buku_id' => $bukuIds[0],
                'tanggal_pinjam' => now()->subDays(20)->toDateString(),
                'tanggal_kembali' => now()->subDays(6)->toDateString(),
                'status' => Peminjaman::STATUS_DIKEMBALIKAN,
            ]);

            Peminjaman::create([
                'user_id' => $siswa->id,
                'buku_id' => $bukuIds[1],
                'tanggal_pinjam' => now()->subDays(3)->toDateString(),
                'status' => Peminjaman::STATUS_DIPINJAM,
            ]);

            Peminjaman::create([
                'user_id' => $siswa->id,
                'buku_id' => $bukuIds[2],
                'tanggal_pinjam' => now()->toDateString(),
                'status' => Peminjaman::STATUS_DIAJUKAN,
            ]);
        }
    }
}
