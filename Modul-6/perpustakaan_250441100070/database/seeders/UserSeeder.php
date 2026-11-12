<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Akun contoh untuk pengujian. Kata sandi semuanya: password
     * Role ditetapkan oleh seeder (mekanisme berwenang), bukan dari form registrasi.
     */
    public function run(): void
    {
        $akun = [
            ['name' => 'Administrator',        'email' => 'admin@perpustakaan.test',  'role' => 'admin'],
            ['name' => 'Petugas Perpustakaan', 'email' => 'petugas@perpustakaan.test', 'role' => 'petugas'],
            ['name' => 'Siswa Satu',           'email' => 'siswa1@perpustakaan.test', 'role' => 'siswa'],
            ['name' => 'Siswa Dua',            'email' => 'siswa2@perpustakaan.test', 'role' => 'siswa'],
        ];

        foreach ($akun as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                ['name' => $data['name'], 'password' => Hash::make('password')],
            );

            $user->syncRoles($data['role']);

            if ($data['role'] === 'siswa') {
                $this->buatPeminjamanContoh($user);
            }
        }
    }

    /** Beberapa peminjaman contoh supaya halaman riwayat tidak kosong. */
    private function buatPeminjamanContoh(User $user): void
    {
        $anggota = $user->profilAnggota();

        if ($anggota->peminjamans()->exists()) {
            return;
        }

        $bukuIds = Buku::inRandomOrder()->limit(3)->pluck('id')->values();

        $contoh = [
            ['status' => Peminjaman::DIKEMBALIKAN, 'tanggal_pinjam' => now()->subDays(14)->toDateString(), 'tanggal_kembali' => now()->subDays(7)->toDateString()],
            ['status' => Peminjaman::DIPINJAM,     'tanggal_pinjam' => now()->subDays(3)->toDateString(),  'tanggal_kembali' => null],
            ['status' => Peminjaman::DIAJUKAN,     'tanggal_pinjam' => null,                               'tanggal_kembali' => null],
        ];

        foreach ($contoh as $indeks => $baris) {
            if (! isset($bukuIds[$indeks])) {
                break;
            }

            Peminjaman::create($baris + [
                'anggota_id' => $anggota->id,
                'buku_id' => $bukuIds[$indeks],
            ]);
        }
    }
}
