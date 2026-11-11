<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Skenario pengujian No. 1-15 pada soal 5 (Modul V).
 * Jalankan: php artisan test --filter=RbacTest
 */
class RbacTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $petugas;
    private User $siswa;
    private User $siswa2;
    private Kategori $kategori;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);

        $this->admin = User::factory()->create()->assignRole('admin');
        $this->petugas = User::factory()->create()->assignRole('petugas');
        $this->siswa = User::factory()->create()->assignRole('siswa');
        $this->siswa2 = User::factory()->create()->assignRole('siswa');
        $this->kategori = Kategori::create(['nama' => 'Fiksi']);
    }

    private function dataBuku(array $override = []): array
    {
        return array_merge([
            'kategori_id' => $this->kategori->id,
            'judul' => 'Laskar Pelangi',
            'penulis' => 'Andrea Hirata',
            'tahun_terbit' => 2005,
        ], $override);
    }

    private function buatBuku(): Buku
    {
        return Buku::create($this->dataBuku());
    }

    // ---- Autentikasi (soal 1 & 2) ----

    public function test_registrasi_selalu_menjadi_siswa_dan_role_dari_input_diabaikan(): void
    {
        $this->post('/register', [
            'name' => 'Pendaftar',
            'email' => 'baru@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin', // upaya menaikkan hak akses
        ]);

        $user = User::where('email', 'baru@example.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole('siswa'));
        $this->assertFalse($user->hasRole('admin'));
        $this->assertNotSame('password123', $user->password); // ter-hash
    }

    public function test_login_dengan_kredensial_salah_ditolak(): void
    {
        $this->post('/login', ['email' => $this->siswa->email, 'password' => 'salah-total']);

        $this->assertGuest();
    }

    public function test_user_yang_sudah_login_tidak_bisa_membuka_halaman_login(): void
    {
        $this->actingAs($this->siswa)->get('/login')->assertRedirect(route('dashboard'));
    }

    public function test_dashboard_mengarahkan_sesuai_role(): void
    {
        $this->actingAs($this->admin)->get('/dashboard')->assertRedirect(route('admin.dashboard'));
        $this->actingAs($this->petugas)->get('/dashboard')->assertRedirect(route('petugas.dashboard'));
        $this->actingAs($this->siswa)->get('/dashboard')->assertRedirect(route('buku.index'));
    }

    // ---- No. 1 ----
    public function test_01_belum_login_membuka_dashboard_admin_dialihkan_ke_login(): void
    {
        $this->get('/admin/dashboard')->assertRedirect(route('login'));
    }

    // ---- No. 2-5 Admin ----
    public function test_02_admin_dapat_membuka_dashboard_admin(): void
    {
        $this->actingAs($this->admin)->get('/admin/dashboard')->assertOk()->assertSee('Dashboard Admin');
    }

    public function test_03_admin_dapat_tambah_ubah_hapus_buku(): void
    {
        $this->actingAs($this->admin);

        $id = $this->postJson('/api/buku', $this->dataBuku())->assertCreated()->json('data.id');
        $this->putJson("/api/buku/{$id}", $this->dataBuku(['judul' => 'Judul Baru']))->assertOk();
        $this->assertDatabaseHas('bukus', ['id' => $id, 'judul' => 'Judul Baru']);
        $this->deleteJson("/api/buku/{$id}")->assertOk();
        $this->assertDatabaseMissing('bukus', ['id' => $id]);
    }

    public function test_04_admin_dapat_mengelola_akun_dan_role_pengguna(): void
    {
        $this->actingAs($this->admin)->get('/admin/pengguna')->assertOk();

        $this->put("/admin/pengguna/{$this->siswa->id}", [
            'name' => $this->siswa->name,
            'email' => $this->siswa->email,
            'role' => 'petugas',
        ])->assertRedirect(route('pengguna.index'));

        $this->assertTrue($this->siswa->fresh()->hasRole('petugas'));
        $this->assertFalse($this->siswa->fresh()->hasRole('siswa'));
    }

    public function test_05_admin_dapat_mengakses_peminjaman_pengembalian_dan_laporan(): void
    {
        $this->actingAs($this->admin);
        $this->get('/peminjaman')->assertOk();
        $this->get('/pengembalian')->assertOk();
        $this->get('/laporan')->assertOk();
    }

    // ---- No. 6-9 Petugas ----
    public function test_06_petugas_dapat_membuka_dashboard_petugas(): void
    {
        $this->actingAs($this->petugas)->get('/petugas/dashboard')->assertOk()->assertSee('Dashboard Petugas');
    }

    public function test_07_petugas_dapat_mengelola_data_buku(): void
    {
        $this->actingAs($this->petugas);
        $id = $this->postJson('/api/buku', $this->dataBuku())->assertCreated()->json('data.id');
        $this->putJson("/api/buku/{$id}", $this->dataBuku(['judul' => 'Diubah Petugas']))->assertOk();
        $this->deleteJson("/api/buku/{$id}")->assertOk();
    }

    public function test_08_petugas_dapat_memproses_peminjaman_dan_pengembalian(): void
    {
        $pinjam = Peminjaman::create([
            'user_id' => $this->siswa->id,
            'buku_id' => $this->buatBuku()->id,
            'tanggal_pinjam' => now()->toDateString(),
            'status' => Peminjaman::STATUS_DIAJUKAN,
        ]);

        $this->actingAs($this->petugas);

        $this->post(route('peminjaman.setujui', $pinjam))->assertRedirect();
        $this->assertSame('dipinjam', $pinjam->fresh()->status);

        $this->post(route('pengembalian.store', $pinjam))->assertRedirect();
        $this->assertSame('dikembalikan', $pinjam->fresh()->status);
        $this->assertNotNull($pinjam->fresh()->tanggal_kembali);
    }

    public function test_09_petugas_tidak_dapat_membuka_kelola_akun_atau_role(): void
    {
        $this->actingAs($this->petugas);
        $this->get('/admin/pengguna')->assertForbidden();
        $this->put("/admin/pengguna/{$this->siswa->id}", [
            'name' => 'x', 'email' => 'x@example.com', 'role' => 'admin',
        ])->assertForbidden();
        $this->get('/admin/dashboard')->assertForbidden();
    }

    // ---- No. 10-14 Siswa ----
    public function test_10_siswa_dapat_melihat_daftar_dan_detail_buku(): void
    {
        $buku = $this->buatBuku();

        $this->actingAs($this->siswa);
        $this->get('/buku')->assertOk();
        $this->get("/buku/{$buku->id}")->assertOk()->assertSee('Laskar Pelangi');
        $this->getJson('/api/buku')->assertOk();
    }

    public function test_11_siswa_dapat_melihat_dan_mengubah_profil_sendiri(): void
    {
        $this->actingAs($this->siswa);
        $this->get('/profile')->assertOk();
        $this->patch('/profile', ['name' => 'Nama Baru', 'email' => $this->siswa->email])
            ->assertSessionHasNoErrors();
        $this->assertSame('Nama Baru', $this->siswa->fresh()->name);
    }

    public function test_12_siswa_dapat_melihat_riwayat_sendiri(): void
    {
        $buku = $this->buatBuku();
        Peminjaman::create(['user_id' => $this->siswa->id, 'buku_id' => $buku->id, 'tanggal_pinjam' => now()->toDateString(), 'status' => 'dipinjam']);

        $this->actingAs($this->siswa);
        $this->get('/riwayat')->assertOk()->assertSee('Laskar Pelangi');
        $this->get("/riwayat/{$this->siswa->id}")->assertOk();
    }

    public function test_siswa_dapat_mengajukan_peminjaman(): void
    {
        $buku = $this->buatBuku();

        $this->actingAs($this->siswa)->post(route('peminjaman.ajukan', $buku->id))
            ->assertRedirect(route('riwayat.index'));

        $this->assertDatabaseHas('peminjamans', ['user_id' => $this->siswa->id, 'buku_id' => $buku->id, 'status' => 'diajukan']);
    }

    public function test_13_siswa_tidak_dapat_membuka_dashboard_admin_atau_mengelola_buku(): void
    {
        $this->actingAs($this->siswa);
        $this->get('/admin/dashboard')->assertForbidden();
        $this->get('/petugas/dashboard')->assertForbidden();
        $this->get('/admin/pengguna')->assertForbidden();
        $this->get('/peminjaman')->assertForbidden();
        $this->get('/laporan')->assertForbidden();
        $this->postJson('/api/buku', $this->dataBuku())->assertForbidden();
        $this->getJson('/api/rekap/buku-per-kategori')->assertForbidden();
    }

    public function test_14_siswa_tidak_dapat_melihat_riwayat_siswa_lain(): void
    {
        $buku = $this->buatBuku();
        Peminjaman::create(['user_id' => $this->siswa2->id, 'buku_id' => $buku->id, 'tanggal_pinjam' => now()->toDateString(), 'status' => 'dipinjam']);

        $this->actingAs($this->siswa)->get("/riwayat/{$this->siswa2->id}")->assertForbidden();
    }

    public function test_admin_dan_petugas_dapat_melihat_riwayat_siswa(): void
    {
        $this->actingAs($this->admin)->get("/riwayat/{$this->siswa->id}")->assertOk();
        $this->actingAs($this->petugas)->get("/riwayat/{$this->siswa->id}")->assertOk();
    }

    // ---- No. 15 ----
    public function test_15_setelah_logout_harus_login_kembali(): void
    {
        foreach ([$this->admin, $this->petugas, $this->siswa] as $user) {
            $this->post('/login', ['email' => $user->email, 'password' => 'password']);
            $this->assertAuthenticatedAs($user);

            $this->post('/logout');
            $this->assertGuest();

            $this->get('/riwayat')->assertRedirect(route('login'));
        }
    }

    // ---- Laporan & cetak ----
    public function test_admin_dan_petugas_dapat_membuka_dan_mencetak_laporan(): void
    {
        foreach ([$this->admin, $this->petugas] as $user) {
            $this->actingAs($user);
            $this->get('/laporan?dari=2020-01-01&status=dipinjam')->assertOk();
            $this->get('/laporan/cetak')->assertOk()->assertSee('Laporan Perpustakaan Digital');
        }
    }

    public function test_siswa_tidak_dapat_mencetak_laporan(): void
    {
        $this->actingAs($this->siswa)->get('/laporan/cetak')->assertForbidden();
    }
}
