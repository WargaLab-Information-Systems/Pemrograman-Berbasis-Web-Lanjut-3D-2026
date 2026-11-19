<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RolePermissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_guest_cannot_open_protected_book_page(): void
    {
        $this->get('/buku')->assertRedirect('/login');
    }

    public function test_student_can_view_book_page_but_cannot_manage_books(): void
    {
        $student = User::factory()->create();
        $student->assignRole('siswa');

        $this->actingAs($student)->get('/buku')->assertOk();

        $this->actingAs($student)
            ->postJson('/api/buku', [])
            ->assertForbidden();
    }

    public function test_staff_can_open_staff_dashboard_but_not_admin_dashboard(): void
    {
        $staff = User::factory()->create();
        $staff->assignRole('petugas');

        $this->actingAs($staff)->get('/petugas/dashboard')->assertOk();
        $this->actingAs($staff)->get('/admin/dashboard')->assertForbidden();
    }

    public function test_admin_can_open_admin_dashboard(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin)->get('/admin/dashboard')->assertOk();
    }
}
