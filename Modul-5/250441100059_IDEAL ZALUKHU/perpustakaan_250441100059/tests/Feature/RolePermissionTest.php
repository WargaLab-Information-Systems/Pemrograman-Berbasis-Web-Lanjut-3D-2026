<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RolePermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_roles_receive_the_permissions_defined_by_the_access_matrix(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $expectedPermissions = [
            'admin' => [
                'books.manage',
                'books.view',
                'dashboard.admin',
                'loans.approve',
                'loans.history-own',
                'loans.manage',
                'loans.request',
                'loans.view-own',
                'profile.update-own',
                'profile.view-own',
                'reports.view',
                'returns.manage',
                'returns.record',
                'users.assign-roles',
                'users.manage',
            ],
            'petugas' => [
                'books.manage',
                'books.view',
                'dashboard.petugas',
                'loans.approve',
                'loans.history-own',
                'loans.manage',
                'loans.request',
                'loans.view-own',
                'profile.update-own',
                'profile.view-own',
                'reports.view',
                'returns.manage',
                'returns.record',
            ],
            'siswa' => [
                'books.view',
                'loans.history-own',
                'loans.request',
                'loans.view-own',
                'profile.update-own',
                'profile.view-own',
            ],
        ];

        foreach ($expectedPermissions as $roleName => $permissions) {
            $role = Role::findByName($roleName, 'web');

            $this->assertSame(
                $permissions,
                $role->permissions->pluck('name')->sort()->values()->all(),
                "Unexpected permissions assigned to the {$roleName} role.",
            );
        }
    }

    public function test_admin_without_dashboard_permission_cannot_open_admin_dashboard(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('admin');
        Role::findByName('admin', 'web')->revokePermissionTo('dashboard.admin');

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_assigned_dashboard_permissions_allow_admin_and_petugas_to_open_their_dashboards(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $petugas = User::factory()->create();
        $petugas->assignRole('petugas');

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk();

        $this->actingAs($petugas)
            ->get(route('petugas.dashboard'))
            ->assertOk();
    }

    public function test_petugas_without_dashboard_permission_cannot_open_petugas_dashboard(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('petugas');
        Role::findByName('petugas', 'web')->revokePermissionTo('dashboard.petugas');

        $this->actingAs($user)
            ->get(route('petugas.dashboard'))
            ->assertForbidden();
    }

    public function test_user_without_profile_update_permission_cannot_delete_their_account(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('siswa');
        Role::findByName('siswa', 'web')->revokePermissionTo('profile.update-own');

        $this->actingAs($user)
            ->delete(route('profile.destroy'), ['password' => 'password'])
            ->assertForbidden();
    }

    public function test_admin_cannot_remove_their_own_admin_role(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin)
            ->put(route('admin.users.update', $admin), [
                'name' => $admin->name,
                'email' => $admin->email,
                'role' => 'siswa',
            ])
            ->assertRedirect();

        $this->assertTrue($admin->fresh()->hasRole('admin'));
    }

    public function test_admin_cannot_change_student_role_while_the_student_has_an_active_loan(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $student = User::factory()->create();
        $student->assignRole('siswa');
        $anggota = Anggota::factory()->create(['user_id' => $student->id]);
        $buku = Buku::factory()->create(['kategori_id' => Kategori::factory()->create()->id]);
        Peminjaman::factory()->create([
            'anggota_id' => $anggota->id,
            'buku_id' => $buku->id,
            'status' => 'dipinjam',
            'tanggal_kembali' => null,
        ]);

        $this->actingAs($admin)
            ->put(route('admin.users.update', $student), [
                'name' => $student->name,
                'email' => $student->email,
                'role' => 'petugas',
            ])
            ->assertRedirect()
            ->assertSessionHas('error', 'Role pengguna tidak dapat diubah karena masih memiliki peminjaman berstatus menunggu, disetujui, atau dipinjam. Ubah atau tunggu status peminjaman menjadi ditolak atau dikembalikan terlebih dahulu.');

        $this->assertTrue($student->fresh()->hasRole('siswa'));
        $this->assertFalse($student->fresh()->hasRole('petugas'));
    }

    public function test_editing_a_user_opens_the_inline_edit_form_on_the_user_management_page(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $student = User::factory()->create();
        $student->assignRole('siswa');

        $this->actingAs($admin)
            ->get(route('admin.users.index', ['edit' => $student->id]))
            ->assertOk()
            ->assertSee('Formulir Edit Pengguna')
            ->assertSee($student->name)
            ->assertDontSee('Formulir Tambah Pengguna');
    }

    public function test_admin_cannot_change_another_users_role_while_the_user_has_an_active_loan(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $petugas = User::factory()->create();
        $petugas->assignRole('petugas');
        $anggota = Anggota::factory()->create(['user_id' => $petugas->id]);
        $buku = Buku::factory()->create(['kategori_id' => Kategori::factory()->create()->id]);
        Peminjaman::factory()->create([
            'anggota_id' => $anggota->id,
            'buku_id' => $buku->id,
            'status' => 'dipinjam',
            'tanggal_kembali' => null,
        ]);

        $this->actingAs($admin)
            ->put(route('admin.users.update', $petugas), [
                'name' => $petugas->name,
                'email' => $petugas->email,
                'role' => 'siswa',
            ])
            ->assertRedirect()
            ->assertSessionHas('error', 'Role pengguna tidak dapat diubah karena masih memiliki peminjaman berstatus menunggu, disetujui, atau dipinjam. Ubah atau tunggu status peminjaman menjadi ditolak atau dikembalikan terlebih dahulu.');

        $this->assertTrue($petugas->fresh()->hasRole('petugas'));
        $this->assertFalse($petugas->fresh()->hasRole('siswa'));
    }

    public function test_admin_can_change_student_role_after_loans_are_rejected_or_returned(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $student = User::factory()->create();
        $student->assignRole('siswa');
        $anggota = Anggota::factory()->create(['user_id' => $student->id]);
        $buku = Buku::factory()->create(['kategori_id' => Kategori::factory()->create()->id]);

        foreach ([['ditolak', null], ['dikembalikan', now()->toDateString()]] as [$status, $tanggalKembali]) {
            Peminjaman::factory()->create([
                'anggota_id' => $anggota->id,
                'buku_id' => $buku->id,
                'status' => $status,
                'tanggal_kembali' => $tanggalKembali,
            ]);
        }

        $this->actingAs($admin)
            ->put(route('admin.users.update', $student), [
                'name' => $student->name,
                'email' => $student->email,
                'role' => 'petugas',
            ])
            ->assertRedirect(route('admin.users.index'));

        $this->assertTrue($student->fresh()->hasRole('petugas'));
        $this->assertFalse($student->fresh()->hasRole('siswa'));
    }
}
