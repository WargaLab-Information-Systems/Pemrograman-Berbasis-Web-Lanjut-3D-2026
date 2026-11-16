<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class AkunRoleSeeder extends Seeder
{
    public function run(): void
    {
        $passwordAdminBaru = env('MODUL6_ADMIN_PASSWORD');

        if (
            !is_string($passwordAdminBaru) ||
            strlen($passwordAdminBaru) < 12
        ) {
            throw new RuntimeException(
                'Atur MODUL6_ADMIN_PASSWORD di .env dengan password baru minimal 12 karakter.'
            );
        }

        // Membuat akun Admin jika belum ada,
        // atau memperbarui password jika sudah ada.
        $admin = User::firstOrNew([
            'email' => 'admin2026@example.com',
        ]);

        if (!$admin->exists) {
            $admin->name = 'Admin Perpustakaan';
        }

        $admin->password = Hash::make($passwordAdminBaru);
        $admin->save();

        $admin->syncRoles(['admin']);

        // Mempertahankan akun Petugas yang sudah ada.
        $petugas = User::firstOrCreate(
            ['email' => 'petugas2026@example.com'],
            [
                'name' => 'Petugas Perpustakaan',
                'password' => Hash::make('Petugas12345#'),
            ]
        );

        $petugas->syncRoles(['petugas']);

        $this->command->info(
            'Akun Admin berhasil diperbarui dan role Petugas dipertahankan!'
        );
    }
}