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
     * Akun contoh untuk pengujian. Kata sandi akun petugas dan siswa: password
     * Role ditetapkan oleh seeder (mekanisme berwenang), bukan dari form registrasi.
     *
     * Soal 5 (c): kata sandi akun admin TIDAK lagi memakai 'password' bawaan.
     * Seeder membaca ADMIN_PASSWORD dari .env:
     * - Kalau ADMIN_PASSWORD sudah diisi, kata sandi admin diperbarui ke nilai itu
     *   setiap kali seeder dijalankan ulang (termasuk saat mengganti kata sandi).
     * - Kalau ADMIN_PASSWORD kosong DAN akun admin belum ada, seeder berhenti
     *   dengan pesan error, supaya tidak ada yang lupa mengisinya dan malah
     *   terpasang dengan kata sandi tebakan seperti 'password'.
     * - Kalau akun admin SUDAH ada dan ADMIN_PASSWORD dikosongkan lagi, kata
     *   sandi yang sudah diganti sebelumnya TIDAK ditimpa balik ke bawaan.
     */
    public function run(): void
    {
        $passwordAdmin = env('ADMIN_PASSWORD');

        if (! $passwordAdmin && ! User::where('email', 'admin@perpustakaan.test')->exists()) {
            $this->command?->error(
                'ADMIN_PASSWORD belum diisi di .env. Isi dulu dengan kata sandi '.
                'yang kuat sebelum menjalankan seeder ini untuk pertama kali.'
            );

            return;
        }

        $akun = [
            ['name' => 'Administrator',        'email' => 'admin@perpustakaan.test',  'role' => 'admin', 'password' => $passwordAdmin],
            ['name' => 'Petugas Perpustakaan', 'email' => 'petugas@perpustakaan.test', 'role' => 'petugas', 'password' => 'password'],
            ['name' => 'Siswa Satu',           'email' => 'siswa1@perpustakaan.test', 'role' => 'siswa', 'password' => 'password'],
            ['name' => 'Siswa Dua',            'email' => 'siswa2@perpustakaan.test', 'role' => 'siswa', 'password' => 'password'],
        ];

        foreach ($akun as $data) {
            $atribut = ['name' => $data['name']];

            // Hanya tulis ulang password kalau memang ada nilainya, supaya
            // admin yang sudah diganti tidak ketiban balik 'password'.
            if (! empty($data['password'])) {
                $atribut['password'] = Hash::make($data['password']);
            }

            $user = User::updateOrCreate(['email' => $data['email']], $atribut);

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
