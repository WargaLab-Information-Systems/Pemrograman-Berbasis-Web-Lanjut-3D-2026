<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Membuat Permissions
        $kelolaPengguna = Permission::firstOrCreate([
            'name' => 'kelola pengguna',
            'guard_name' => 'web',
        ]);

        $lihatProgres = Permission::firstOrCreate([
            'name' => 'lihat progres',
            'guard_name' => 'web',
        ]);

        // Membuat Roles
        $admin = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $dosen = Role::firstOrCreate([
            'name' => 'dosen',
            'guard_name' => 'web',
        ]);

        Role::firstOrCreate([
            'name' => 'mahasiswa',
            'guard_name' => 'web',
        ]);

        // Assign Permissions ke Roles
        $admin->syncPermissions([
            $kelolaPengguna,
            $lihatProgres,
        ]);

        $dosen->syncPermissions([
            $lihatProgres,
        ]);
    }
}