<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookActiveLoanRestrictionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_and_petugas_cannot_edit_or_delete_books_with_active_loans(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $kategori = Kategori::factory()->create();
        $form = [
            'judul' => 'Judul yang Diubah',
            'penulis' => 'Penulis Baru',
            'tahun_terbit' => 2025,
            'kategori_id' => $kategori->id,
        ];

        foreach (['menunggu', 'disetujui', 'dipinjam'] as $status) {
            $buku = Buku::factory()->create(['kategori_id' => $kategori->id]);
            Peminjaman::factory()->create([
                'anggota_id' => Anggota::factory()->create()->id,
                'buku_id' => $buku->id,
                'status' => $status,
                'tanggal_kembali' => null,
            ]);

            foreach ([['admin', 'admin'], ['petugas', 'petugas']] as [$role, $routePrefix]) {
                $user = User::factory()->create();
                $user->assignRole($role);

                $this->actingAs($user)
                    ->get(route("{$routePrefix}.buku.edit", $buku))
                    ->assertRedirect(route("{$routePrefix}.buku.index"));

                $this->actingAs($user)
                    ->put(route("{$routePrefix}.buku.update", $buku), $form)
                    ->assertRedirect(route("{$routePrefix}.buku.index"));

                $this->actingAs($user)
                    ->delete(route("{$routePrefix}.buku.destroy", $buku))
                    ->assertRedirect(route("{$routePrefix}.buku.index"));

                $this->assertDatabaseHas('bukus', [
                    'id' => $buku->id,
                    'judul' => $buku->judul,
                ]);
            }
        }
    }

    public function test_rejected_and_returned_loans_do_not_block_book_edits_but_preserve_borrowing_history_when_deleting(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $user = User::factory()->create();
        $user->assignRole('admin');
        $kategori = Kategori::factory()->create();

        foreach ([['ditolak', null], ['dikembalikan', now()->toDateString()]] as [$status, $tanggalKembali]) {
            $buku = Buku::factory()->create(['kategori_id' => $kategori->id]);
            Peminjaman::factory()->create([
                'anggota_id' => Anggota::factory()->create()->id,
                'buku_id' => $buku->id,
                'status' => $status,
                'tanggal_kembali' => $tanggalKembali,
            ]);

            $this->actingAs($user)
                ->put(route('admin.buku.update', $buku), [
                    'judul' => 'Buku Diperbarui',
                    'penulis' => $buku->penulis,
                    'tahun_terbit' => $buku->tahun_terbit,
                    'kategori_id' => $kategori->id,
                ])
                ->assertRedirect(route('admin.buku.index'));

            $this->assertDatabaseHas('bukus', ['id' => $buku->id, 'judul' => 'Buku Diperbarui']);

            $this->actingAs($user)
                ->delete(route('admin.buku.destroy', $buku))
                ->assertRedirect(route('admin.buku.index'))
                ->assertSessionHas('error', 'Buku tidak dapat dihapus karena sudah memiliki riwayat peminjaman.');

            $this->assertDatabaseHas('bukus', ['id' => $buku->id]);
        }
    }
}
