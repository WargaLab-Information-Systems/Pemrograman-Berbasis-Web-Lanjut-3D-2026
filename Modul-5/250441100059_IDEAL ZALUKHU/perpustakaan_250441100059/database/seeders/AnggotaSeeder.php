<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AnggotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::role('siswa')->each(function (User $user): void {
            $user->anggota()->firstOrCreate([], [
                'nama' => $user->name,
                'email' => $user->email,
                'no_telepon' => '-',
            ]);
        });

    }
}
