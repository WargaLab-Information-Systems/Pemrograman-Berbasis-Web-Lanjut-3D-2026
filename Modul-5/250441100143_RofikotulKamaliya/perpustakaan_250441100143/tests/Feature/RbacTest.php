<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RbacTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
    }

    private function userDenganRole(string $role): User
    {
        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }

    private function buatPeminjaman(User $pemilik): Peminjaman
    {
        $kategori = Kategori::create(['nama' => 'Fiksi']);
        $buku = Buku::create([
            'kategori_id' => $kategori->id,
            'judul' => 'Buku Uji',
            'penulis' => 'Penulis Uji',
            'tahun_terbit' => 2020,
        ]);

        return Peminjaman::create([
            'anggota_id' => $pemilik->ensureAnggota()->id,
            'buku_id' => $buku->id,
            'tanggal_pinjam' => '2026-01-01',
            'status' => Peminjaman::STATUS_DIPINJAM,
        ]);
    }

    public function test_pengguna_belum_login_dialihkan_ke_login(): void
    {
        $this->get('/admin/dashboard')->assertRedirect('/login');
    }

    public function test_admin_dapat_membuka_dashboard_admin(): void
    {
        $this->actingAs($this->userDenganRole('admin'))
            ->get('/admin/dashboard')
            ->assertOk();
    }

    public function test_petugas_dan_siswa_tidak_dapat_membuka_dashboard_admin(): void
    {
        $this->actingAs($this->userDenganRole('petugas'))->get('/admin/dashboard')->assertForbidden();
        $this->actingAs($this->userDenganRole('siswa'))->get('/admin/dashboard')->assertForbidden();
    }

    public function test_petugas_dapat_membuka_dashboard_petugas(): void
    {
        $this->actingAs($this->userDenganRole('petugas'))
            ->get('/petugas/dashboard')
            ->assertOk();
    }

    public function test_petugas_tidak_dapat_mengelola_pengguna(): void
    {
        $this->actingAs($this->userDenganRole('petugas'))->get('/admin/users')->assertForbidden();
    }

    public function test_siswa_tidak_dapat_membuka_pengelolaan_buku_tetapi_dapat_melihat_daftar_buku(): void
    {
        $siswa = $this->userDenganRole('siswa');

        $this->actingAs($siswa)->get('/kelola/buku')->assertForbidden();
        $this->actingAs($siswa)->get('/buku')->assertOk();
    }

    public function test_admin_dan_petugas_dapat_membuka_pengelolaan_buku(): void
    {
        $this->actingAs($this->userDenganRole('admin'))->get('/kelola/buku')->assertOk();
        $this->actingAs($this->userDenganRole('petugas'))->get('/kelola/buku')->assertOk();
    }

    public function test_siswa_tidak_dapat_membuka_laporan(): void
    {
        $this->actingAs($this->userDenganRole('siswa'))->get('/laporan')->assertForbidden();
        $this->actingAs($this->userDenganRole('petugas'))->get('/laporan')->assertOk();
    }

    public function test_siswa_dapat_melihat_peminjaman_sendiri_tetapi_tidak_milik_siswa_lain(): void
    {
        $siswaA = $this->userDenganRole('siswa');
        $siswaB = $this->userDenganRole('siswa');

        $milikB = $this->buatPeminjaman($siswaB);

        $this->actingAs($siswaA)->get('/peminjaman/'.$milikB->id)->assertForbidden();
        $this->actingAs($siswaB)->get('/peminjaman/'.$milikB->id)->assertOk();
    }

    public function test_petugas_dapat_melihat_peminjaman_siapa_saja(): void
    {
        $milikSiswa = $this->buatPeminjaman($this->userDenganRole('siswa'));

        $this->actingAs($this->userDenganRole('petugas'))
            ->get('/peminjaman/'.$milikSiswa->id)
            ->assertOk();
    }

    public function test_registrasi_selalu_menghasilkan_role_siswa_walau_dikirim_role_lain(): void
    {
        $this->post('/register', [
            'name' => 'Pendaftar Nakal',
            'email' => 'nakal@example.com',
            'password' => 'password-kuat-123',
            'password_confirmation' => 'password-kuat-123',
            'role' => 'admin',
        ]);

        $user = User::where('email', 'nakal@example.com')->first();

        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole('siswa'));
        $this->assertFalse($user->hasRole('admin'));
    }

    public function test_setelah_logout_halaman_terlindungi_butuh_login_lagi(): void
    {
        $user = $this->userDenganRole('admin');

        $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $this->get('/admin/dashboard')->assertRedirect('/login');
    }

    public function test_siswa_dapat_membatalkan_pengajuan_sendiri_yang_belum_disetujui(): void
    {
        $siswa = $this->userDenganRole('siswa');
        $pengajuan = $this->buatPeminjaman($siswa);
        $pengajuan->update(['status' => Peminjaman::STATUS_DIAJUKAN]);

        $this->actingAs($siswa)->delete('/peminjaman/'.$pengajuan->id)->assertRedirect();

        $this->assertDatabaseMissing('peminjamans', ['id' => $pengajuan->id]);
    }

    public function test_siswa_tidak_dapat_membatalkan_pengajuan_siswa_lain_atau_yang_sudah_dipinjam(): void
    {
        $siswaA = $this->userDenganRole('siswa');
        $siswaB = $this->userDenganRole('siswa');

        $milikB = $this->buatPeminjaman($siswaB);
        $milikB->update(['status' => Peminjaman::STATUS_DIAJUKAN]);

        $this->actingAs($siswaA)->delete('/peminjaman/'.$milikB->id)->assertForbidden();

        $milikB->update(['status' => Peminjaman::STATUS_DIPINJAM]);

        $this->actingAs($siswaB)->delete('/peminjaman/'.$milikB->id)->assertForbidden();
        $this->assertDatabaseHas('peminjamans', ['id' => $milikB->id]);
    }
}
