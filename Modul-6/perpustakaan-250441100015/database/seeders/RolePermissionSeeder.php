<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'access admin dashboard',
            'access petugas dashboard',
            'view books',
            'manage books',
            'manage users',
            'manage user roles',
            'manage loans',
            'manage returns',
            'request loan',
            'approve loans',
            'record returns',
            'view own loan status',
            'view own loan history',
            'view library reports',
            'view own profile',
            'update own profile',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $admin = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $petugas = Role::firstOrCreate([
            'name' => 'petugas',
            'guard_name' => 'web',
        ]);

        $siswa = Role::firstOrCreate([
            'name' => 'siswa',
            'guard_name' => 'web',
        ]);

        $admin->syncPermissions($permissions);

        $petugas->syncPermissions([
            'access petugas dashboard',
            'view books',
            'manage books',
            'manage loans',
            'manage returns',
            'approve loans',
            'record returns',
            'view library reports',
            'request loan',
            'view own loan status',
            'view own loan history',
            'view own profile',
            'update own profile',
        ]);

        $siswa->syncPermissions([
            'view books',
            'request loan',
            'view own loan status',
            'view own loan history',
            'view own profile',
            'update own profile',
        ]);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}