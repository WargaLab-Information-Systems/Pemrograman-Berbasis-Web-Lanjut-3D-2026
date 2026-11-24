<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class PeminjamanPermissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Siapkan role dan permission di database testing.
        $this->seed(RolePermissionSeeder::class);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    private function buatPengguna(string $role): User
    {
        $user = User::factory()->create();

        $user->assignRole($role);

        return $user;
    }

    private function buatPeminjaman(): Peminjaman
    {
        // Data berikut hanya dibuat pada database testing.
        $pemilik = $this->buatPengguna('siswa');

        $anggota = Anggota::create([
            'user_id' => $pemilik->id,
            'nama' => $pemilik->name,
            'email' => $pemilik->email,
            'no_telepon' => '081234567890',
        ]);

        $kategori = Kategori::create([
            'nama' => 'Kategori Pengujian',
        ]);

        $buku = Buku::create([
            'kategori_id' => $kategori->id,
            'judul' => 'Buku Pengujian',
            'penulis' => 'Penulis Pengujian',
            'tahun_terbit' => 2025,
        ]);

        return Peminjaman::create([
            'anggota_id' => $anggota->id,
            'buku_id' => $buku->id,
            'tanggal_pinjam' => now()->toDateString(),
            'tanggal_kembali' => null,
            'status' => 'menunggu',
        ]);
    }

    public function test_siswa_tidak_bisa_menyetujui_peminjaman(): void
    {
        $peminjaman = $this->buatPeminjaman();
        $siswa = $this->buatPengguna('siswa');

        $response = $this->actingAs($siswa)->patch(
            route('peminjaman.update-status', $peminjaman),
            ['status' => 'disetujui']
        );

        $response->assertForbidden();

        $this->assertDatabaseHas('peminjamans', [
            'id' => $peminjaman->id,
            'status' => 'menunggu',
        ]);
    }

    public function test_siswa_tidak_bisa_mencatat_pengembalian(): void
    {
        $peminjaman = $this->buatPeminjaman();
        $siswa = $this->buatPengguna('siswa');

        $response = $this->actingAs($siswa)->patch(
            route('peminjaman.kembalikan', $peminjaman)
        );

        $response->assertForbidden();

        $this->assertDatabaseHas('peminjamans', [
            'id' => $peminjaman->id,
            'status' => 'menunggu',
        ]);
    }

    public function test_siswa_tidak_bisa_mengakses_kelola_pengguna(): void
    {
        $siswa = $this->buatPengguna('siswa');

        $response = $this->actingAs($siswa)
            ->get(route('admin.users.index'));

        $response->assertForbidden();
    }

    public function test_petugas_tidak_bisa_mengakses_kelola_pengguna(): void
    {
        $petugas = $this->buatPengguna('petugas');

        $response = $this->actingAs($petugas)
            ->get(route('admin.users.index'));

        $response->assertForbidden();
    }
}