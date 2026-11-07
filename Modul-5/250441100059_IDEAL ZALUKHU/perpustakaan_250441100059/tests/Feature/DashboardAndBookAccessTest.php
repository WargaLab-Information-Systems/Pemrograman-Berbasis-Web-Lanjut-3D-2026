<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DashboardAndBookAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_admin_dashboard_to_login(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }

    public function test_registration_assigns_student_role_and_dashboard_redirects_by_role(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $response = $this->post(route('register'), [
            'name' => 'Test Siswa',
            'email' => 'siswa@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'admin',
        ]);

        $user = User::where('email', 'siswa@example.com')->firstOrFail();

        $response->assertRedirect(route('dashboard', absolute: false));
        $this->assertTrue($user->hasRole('siswa'));
        $this->assertFalse($user->hasRole('admin'));
        $this->get(route('dashboard'))->assertRedirect(route('siswa.dashboard'));
    }

    public function test_student_can_view_book_detail_but_cannot_open_admin_dashboard(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('siswa');
        $kategori = Kategori::factory()->create();
        $buku = Buku::factory()->create(['kategori_id' => $kategori->id]);

        $this->actingAs($user)->get(route('siswa.buku.show', $buku))->assertOk();
        $this->actingAs($user)->get(route('admin.dashboard'))->assertForbidden();
    }

    public function test_student_book_detail_page_has_a_valid_return_link(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('siswa');
        $kategori = Kategori::factory()->create();
        $buku = Buku::factory()->create(['kategori_id' => $kategori->id]);

        $this->actingAs($user)
            ->get(route('siswa.buku.show', $buku))
            ->assertOk()
            ->assertSee(route('siswa.buku.index'));
    }

    public function test_petugas_can_open_the_book_edit_form_on_the_book_management_page(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $petugas = User::factory()->create();
        $petugas->assignRole('petugas');
        $kategori = Kategori::factory()->create(['nama' => 'Teknologi']);
        $buku = Buku::factory()->create(['kategori_id' => $kategori->id]);

        $this->actingAs($petugas)
            ->get(route('petugas.buku.index', ['edit' => $buku->id]))
            ->assertOk()
            ->assertSee('Formulir Edit Buku')
            ->assertSee('Teknologi')
            ->assertSee(route('petugas.buku.update', $buku))
            ->assertSee(route('petugas.buku.index'))
            ->assertDontSee(route('admin.buku.update', $buku));
    }

    public function test_petugas_edit_route_redirects_to_the_book_management_page_edit_form(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $petugas = User::factory()->create();
        $petugas->assignRole('petugas');
        $buku = Buku::factory()->create(['kategori_id' => Kategori::factory()->create()->id]);

        $this->actingAs($petugas)
            ->get(route('petugas.buku.edit', $buku))
            ->assertRedirect(route('petugas.buku.index', ['edit' => $buku->id]));
    }

    public function test_petugas_without_book_management_permission_cannot_open_the_inline_edit_form(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $petugas = User::factory()->create();
        $petugas->assignRole('petugas');
        Role::findByName('petugas', 'web')->revokePermissionTo('books.manage');
        $buku = Buku::factory()->create(['kategori_id' => Kategori::factory()->create()->id]);

        $this->actingAs($petugas)
            ->get(route('petugas.buku.index', ['edit' => $buku->id]))
            ->assertForbidden();
    }

    public function test_admin_can_open_the_book_edit_form_on_the_book_management_page(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $kategori = Kategori::factory()->create(['nama' => 'Sains']);
        $buku = Buku::factory()->create(['kategori_id' => $kategori->id]);

        $this->actingAs($admin)
            ->get(route('admin.buku.index', ['edit' => $buku->id]))
            ->assertOk()
            ->assertSee('Formulir Edit Buku')
            ->assertSee('Sains')
            ->assertSee(route('admin.buku.update', $buku))
            ->assertSee(route('admin.buku.index'))
            ->assertDontSee(route('petugas.buku.update', $buku));
    }

    public function test_admin_edit_route_redirects_to_the_book_management_page_edit_form(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $buku = Buku::factory()->create(['kategori_id' => Kategori::factory()->create()->id]);

        $this->actingAs($admin)
            ->get(route('admin.buku.edit', $buku))
            ->assertRedirect(route('admin.buku.index', ['edit' => $buku->id]));
    }

    public function test_admin_without_book_management_permission_cannot_open_the_inline_edit_form(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $admin = User::factory()->create();
        $admin->assignRole('admin');
        Role::findByName('admin', 'web')->revokePermissionTo('books.manage');
        $buku = Buku::factory()->create(['kategori_id' => Kategori::factory()->create()->id]);

        $this->actingAs($admin)
            ->get(route('admin.buku.index', ['edit' => $buku->id]))
            ->assertForbidden();
    }
}
