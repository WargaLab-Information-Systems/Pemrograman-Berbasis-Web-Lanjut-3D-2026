<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Role, permission, dan akun awal (admin, petugas, siswa)
        $this->call([
            RolePermissionSeeder::class,
            UserSeeder::class,
        ]);

        foreach (['Fiksi', 'Pelajaran', 'Sejarah', 'Teknologi', 'Sastra'] as $nama) {
            Kategori::firstOrCreate(['nama' => $nama]);
        }

        if (Buku::count() < 3) {
            Buku::factory(10)->create();
        }

        // Contoh riwayat peminjaman untuk dua siswa (untuk menguji pembatasan data pribadi)
        if (Peminjaman::whereNotNull('user_id')->count() === 0) {
            $siswa1 = User::where('email', 'siswa@test.com')->first();
            $siswa2 = User::where('email', 'siswa2@test.com')->first();
            $buku = Buku::orderBy('id')->take(3)->get();

            Peminjaman::create(['user_id' => $siswa1->id, 'buku_id' => $buku[0]->id, 'tanggal_pinjam' => now()->subDays(10)->toDateString(), 'tanggal_kembali' => now()->subDays(3)->toDateString(), 'status' => 'dikembalikan']);
            Peminjaman::create(['user_id' => $siswa1->id, 'buku_id' => $buku[1]->id, 'tanggal_pinjam' => now()->subDays(2)->toDateString(), 'status' => 'dipinjam']);
            Peminjaman::create(['user_id' => $siswa2->id, 'buku_id' => $buku[2]->id, 'tanggal_pinjam' => now()->toDateString(), 'status' => 'diajukan']);
        }
    }
}
