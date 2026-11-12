<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentBorrowingTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_petugas_and_siswa_can_request_a_book_using_their_own_member_records(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $kategori = Kategori::factory()->create();
        $buku = Buku::factory()->create(['kategori_id' => $kategori->id]);

        foreach (['admin', 'petugas', 'siswa'] as $role) {
            $user = User::factory()->create();
            $user->assignRole($role);

            $response = $this->actingAs($user)
                ->post(route('peminjaman.request', $buku));

            if ($role === 'siswa') {
                $response->assertRedirect(route('siswa.peminjaman.index'));
            } else {
                $response->assertSessionHas('success');
            }

            $anggota = $user->anggota()->firstOrFail();

            $this->assertSame($user->name, $anggota->nama);
            $this->assertSame($user->email, $anggota->email);
            $this->assertDatabaseHas('peminjamans', [
                'anggota_id' => $anggota->id,
                'buku_id' => $buku->id,
                'status' => 'menunggu',
            ]);
        }
    }

    public function test_student_cannot_submit_another_members_identity_or_duplicate_active_request(): void
    {
        $this->seed(RolePermissionSeeder::class);
        [$student, $buku] = $this->createStudentAndBook();
        $otherStudent = User::factory()->create();
        $otherStudent->assignRole('siswa');
        $otherAnggota = Anggota::factory()->create(['user_id' => $otherStudent->id]);

        $this->actingAs($student)
            ->post(route('peminjaman.request', $buku), ['anggota_id' => $otherAnggota->id])
            ->assertRedirect(route('siswa.peminjaman.index'));

        $this->assertDatabaseHas('peminjamans', [
            'anggota_id' => $student->anggota()->firstOrFail()->id,
            'buku_id' => $buku->id,
            'status' => 'menunggu',
        ]);

        $this->assertDatabaseMissing('peminjamans', [
            'anggota_id' => $otherAnggota->id,
            'buku_id' => $buku->id,
        ]);

        $this->actingAs($student)
            ->post(route('peminjaman.request', $buku))
            ->assertSessionHas('error');

        $this->assertDatabaseCount('peminjamans', 1);
    }

    public function test_guest_and_users_without_an_allowed_role_cannot_request_books(): void
    {
        $this->seed(RolePermissionSeeder::class);
        [, $buku] = $this->createStudentAndBook();

        $this->post(route('peminjaman.request', $buku))
            ->assertRedirect(route('login'));

        $userWithoutRole = User::factory()->create();

        $this->actingAs($userWithoutRole)
            ->post(route('peminjaman.request', $buku))
            ->assertForbidden();
    }

    public function test_each_role_can_only_see_its_own_borrowing_history(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $kategori = Kategori::factory()->create();

        foreach ([
            ['admin', 'admin.peminjaman.saya'],
            ['petugas', 'petugas.peminjaman.saya'],
            ['siswa', 'siswa.peminjaman.index'],
        ] as [$role, $routeName]) {
            $user = User::factory()->create();
            $user->assignRole($role);
            $anggota = $user->anggota()->create([
                'nama' => $user->name,
                'email' => $user->email,
                'no_telepon' => '-',
            ]);
            $ownBook = Buku::factory()->create([
                'kategori_id' => $kategori->id,
                'judul' => "Buku {$role} Milik Sendiri",
            ]);
            $otherBook = Buku::factory()->create([
                'kategori_id' => $kategori->id,
                'judul' => "Buku {$role} Milik Orang Lain",
            ]);
            $otherAnggota = Anggota::factory()->create();

            $anggota->peminjamans()->create([
                'buku_id' => $ownBook->id,
                'tanggal_pinjam' => now()->toDateString(),
                'status' => 'dikembalikan',
                'tanggal_kembali' => now()->toDateString(),
            ]);
            $otherAnggota->peminjamans()->create([
                'buku_id' => $otherBook->id,
                'tanggal_pinjam' => now()->toDateString(),
                'status' => 'dikembalikan',
                'tanggal_kembali' => now()->toDateString(),
            ]);

            $this->actingAs($user)
                ->get(route($routeName))
                ->assertOk()
                ->assertSee($ownBook->judul)
                ->assertDontSee($otherBook->judul);
        }
    }

    /** @return array{User, Buku} */
    private function createStudentAndBook(): array
    {
        $student = User::factory()->create();
        $student->assignRole('siswa');
        $student->anggota()->create([
            'nama' => $student->name,
            'email' => $student->email,
            'no_telepon' => '-',
        ]);

        $kategori = Kategori::factory()->create();
        $buku = Buku::factory()->create(['kategori_id' => $kategori->id]);

        return [$student, $buku];
    }
}
