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
            'dashboard_admin',
            'dashboard_petugas',
            'view_books',
            'manage_books',
            'manage_users',
            'manage_user_roles',
            'manage_borrowings',
            'manage_returns',
            'request_borrowing',
            'process_borrowing_approval',
            'record_return',
            'view_own_borrowing_status',
            'view_own_borrowing_history',
            'view_reports',
            'view_own_profile',
            'edit_own_profile',

            'request_return',
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

        $admin->syncPermissions([
            'dashboard_admin',
            'view_books',
            'manage_books',
            'manage_users',
            'manage_user_roles',
            'manage_borrowings',
            'manage_returns',
            'request_borrowing',
            'process_borrowing_approval',
            'record_return',
            'view_own_borrowing_status',
            'view_own_borrowing_history',
            'view_reports',
            'view_own_profile',
            'edit_own_profile',
        ]);

        $petugas->syncPermissions([
            'dashboard_petugas',
            'view_books',
            'manage_books',
            'manage_borrowings',
            'manage_returns',
            'request_borrowing',
            'process_borrowing_approval',
            'record_return',
            'view_own_borrowing_status',
            'view_own_borrowing_history',
            'view_reports',
            'view_own_profile',
            'edit_own_profile',
        ]);

        $siswa->syncPermissions([
            'view_books',
            'request_borrowing',
            'request_return',
            'view_own_borrowing_status',
            'view_own_borrowing_history',
            'view_own_profile',
            'edit_own_profile',
        ]);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
