<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Database\Seeder;

class PeminjamanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Peminjaman::factory()->count(3)->create();

        // Data contoh untuk dua siswa, supaya pembatasan "data milik sendiri" bisa diuji.
        $bukuIds = Buku::pluck('id')->values();

        if ($bukuIds->count() < 4) {
            return;
        }

        $siswa1 = User::where('email', 'siswa@gmail.com')->first()?->ensureAnggota();
        $siswa2 = User::where('email', 'siswa2@gmail.com')->first()?->ensureAnggota();

        if ($siswa1) {
            Peminjaman::create([
                'anggota_id' => $siswa1->id, 'buku_id' => $bukuIds[0],
                'tanggal_pinjam' => now()->subDays(10)->toDateString(),
                'tanggal_kembali' => now()->subDays(3)->toDateString(),
                'status' => Peminjaman::STATUS_DIKEMBALIKAN,
            ]);
            Peminjaman::create([
                'anggota_id' => $siswa1->id, 'buku_id' => $bukuIds[1],
                'tanggal_pinjam' => now()->subDays(2)->toDateString(),
                'status' => Peminjaman::STATUS_DIPINJAM,
            ]);
            Peminjaman::create([
                'anggota_id' => $siswa1->id, 'buku_id' => $bukuIds[2],
                'tanggal_pinjam' => now()->toDateString(),
                'status' => Peminjaman::STATUS_DIAJUKAN,
            ]);
        }

        if ($siswa2) {
            Peminjaman::create([
                'anggota_id' => $siswa2->id, 'buku_id' => $bukuIds[3],
                'tanggal_pinjam' => now()->subDays(1)->toDateString(),
                'status' => Peminjaman::STATUS_DIPINJAM,
            ]);
        }
    }
}
