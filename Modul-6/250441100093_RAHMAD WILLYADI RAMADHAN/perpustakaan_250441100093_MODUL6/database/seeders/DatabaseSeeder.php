<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = ['Fiksi', 'Sains & Teknologi', 'Sejarah', 'Pendidikan', 'Agama'];
        foreach ($kategoris as $k) {
            try {
                DB::table('kategoris')->insert(['nama_kategori' => $k, 'created_at' => now(), 'updated_at' => now()]);
            } catch (\Exception $e) {
                DB::table('kategoris')->insert(['nama' => $k, 'created_at' => now(), 'updated_at' => now()]);
            }
        }

        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'petugas']);
        Role::firstOrCreate(['name' => 'siswa']);

        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            ['name' => 'Admin Utama', 'password' => Hash::make('password123')]
        );
        $admin->assignRole('admin');

        $petugas = User::firstOrCreate(
            ['email' => 'petugas@gmail.com'],
            ['name' => 'Petugas Perpustakaan', 'password' => Hash::make('password123')]
        );
        $petugas->assignRole('petugas');

        $siswa = User::firstOrCreate(
            ['email' => 'siswa@gmail.com'],
            ['name' => 'Siswa Teladan', 'password' => Hash::make('password123')]
        );
        $siswa->assignRole('siswa');
    }
}
