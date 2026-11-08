<?php

namespace Database\Seeders;

use App\Models\Anggota;
use App\Models\Petugas;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Akun Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@perpustakaan.test'],
            [
                'name' => 'Administrator Utama',
                'password' => Hash::make('password'),
            ]
        );
        $admin->syncRoles(['admin']);

        // 2. Akun Petugas
        $petugas = User::firstOrCreate(
            ['email' => 'petugas@perpustakaan.test'],
            [
                'name' => 'Petugas Perpustakaan',
                'password' => Hash::make('password'),
            ]
        );
        $petugas->syncRoles(['petugas']);

        // Sinkronisasi record Petugas perpustakaan
        Petugas::firstOrCreate(
            ['nip' => '1985010101'],
            [
                'nama' => $petugas->name,
            ]
        );

        // 3. Akun Siswa 1
        $siswa1 = User::firstOrCreate(
            ['email' => 'siswa1@perpustakaan.test'],
            [
                'name' => 'Ahmad Siswa 1',
                'password' => Hash::make('password'),
            ]
        );
        $siswa1->syncRoles(['siswa']);

        $anggota1 = Anggota::firstOrCreate(
            ['email' => $siswa1->email],
            [
                'user_id' => $siswa1->id,
                'nama' => $siswa1->name,
                'no_telepon' => '082198765432',
            ]
        );
        if ($anggota1->user_id !== $siswa1->id) {
            $anggota1->update(['user_id' => $siswa1->id]);
        }

        // 4. Akun Siswa 2 (untuk pengujian isolasi riwayat peminjaman)
        $siswa2 = User::firstOrCreate(
            ['email' => 'siswa2@perpustakaan.test'],
            [
                'name' => 'Budi Siswa 2',
                'password' => Hash::make('password'),
            ]
        );
        $siswa2->syncRoles(['siswa']);

        $anggota2 = Anggota::firstOrCreate(
            ['email' => $siswa2->email],
            [
                'user_id' => $siswa2->id,
                'nama' => $siswa2->name,
                'no_telepon' => '085233445566',
            ]
        );
        if ($anggota2->user_id !== $siswa2->id) {
            $anggota2->update(['user_id' => $siswa2->id]);
        }

        // Buat sample peminjaman untuk siswa1 dan siswa2 (jika ada buku)
        $buku1 = \App\Models\Buku::first();
        $buku2 = \App\Models\Buku::skip(1)->first() ?? $buku1;

        if ($buku1) {
            \App\Models\Peminjaman::firstOrCreate(
                [
                    'user_id' => $siswa1->id,
                    'buku_id' => $buku1->id,
                ],
                [
                    'anggota_id' => $anggota1->id,
                    'tanggal_pinjam' => now()->subDays(3)->toDateString(),
                    'tanggal_kembali' => null,
                    'status' => 'dipinjam',
                    'catatan' => 'Peminjaman aktif oleh Siswa 1',
                ]
            );
        }

        if ($buku2) {
            \App\Models\Peminjaman::firstOrCreate(
                [
                    'user_id' => $siswa2->id,
                    'buku_id' => $buku2->id,
                ],
                [
                    'anggota_id' => $anggota2->id,
                    'tanggal_pinjam' => now()->subDays(5)->toDateString(),
                    'tanggal_kembali' => null,
                    'status' => 'dipinjam',
                    'catatan' => 'Peminjaman rahasia Siswa 2',
                ]
            );
        }
    }
}
