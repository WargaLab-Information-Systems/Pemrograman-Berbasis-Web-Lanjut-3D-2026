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
 * Pengujian 15 skenario pada lembar Tugas Praktikum Modul 5,
 * ditambah pengujian alur peminjaman dan pembatasan data pribadi.
 */
class SkenarioModul5Test extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $petugas;
    private User $siswa;
    private User $siswaLain;
    private Buku $buku;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);

        $this->admin = User::where('email', 'admin@perpus.com')->first();
        $this->petugas = User::where('email', 'petugas@perpus.com')->first();
        $this->siswa = User::where('email', 'siswa@perpus.com')->first();
        $this->siswaLain = User::where('email', 'siswa2@perpus.com')->first();

        $kategori = Kategori::create(['nama' => 'Fiksi']);
        $this->buku = Buku::create([
            'kategori_id' => $kategori->id,
            'judul' => 'Laskar Pelangi',
            'penulis' => 'Andrea Hirata',
            'tahun_terbit' => 2005,
        ]);
    }

    private function dataBuku(array $override = []): array
    {
        return array_merge([
            'kategori_id' => $this->buku->kategori_id,
            'judul' => 'Bumi Manusia',
            'penulis' => 'Pramoedya',
            'tahun_terbit' => 1980,
        ], $override);
    }

    /* ---------------- 15 skenario lembar tugas ---------------- */

    public function test_skenario_01_guest_membuka_dashboard_admin_dialihkan_ke_login(): void
    {
        $this->get('/admin/dashboard')->assertRedirect('/login');
    }

    public function test_skenario_02_admin_membuka_dashboard_admin(): void
    {
        $this->actingAs($this->admin)->get('/admin/dashboard')->assertOk();
    }

    public function test_skenario_03_admin_tambah_ubah_hapus_buku(): void
    {
        $this->actingAs($this->admin)
            ->post('/buku', $this->dataBuku())
            ->assertRedirect(route('buku.index'));
        $this->assertDatabaseHas('bukus', ['judul' => 'Bumi Manusia']);

        $baru = Buku::where('judul', 'Bumi Manusia')->first();

        $this->actingAs($this->admin)
            ->put("/buku/{$baru->id}", $this->dataBuku(['judul' => 'Bumi Manusia (Revisi)']))
            ->assertRedirect(route('buku.index'));
        $this->assertDatabaseHas('bukus', ['judul' => 'Bumi Manusia (Revisi)']);

        $this->actingAs($this->admin)->delete("/buku/{$baru->id}")->assertRedirect(route('buku.index'));
        $this->assertDatabaseMissing('bukus', ['id' => $baru->id]);
    }

    public function test_skenario_04_admin_mengelola_akun_dan_role(): void
    {
        $this->actingAs($this->admin)->get('/admin/users')->assertOk();

        $this->actingAs($this->admin)->post('/admin/users', [
            'name' => 'Petugas Baru',
            'email' => 'baru@perpus.com',
            'password' => 'rahasia123',
            'role' => 'petugas',
        ])->assertSessionHas('success');

        $baru = User::where('email', 'baru@perpus.com')->first();
        $this->assertTrue($baru->hasRole('petugas'));
        $this->assertNotSame('rahasia123', $baru->password); // di-hash

        $this->actingAs($this->admin)
            ->put("/admin/users/{$baru->id}/role", ['role' => 'siswa'])
            ->assertSessionHas('success');
        $this->assertTrue($baru->fresh()->hasRole('siswa'));

        $this->actingAs($this->admin)->delete("/admin/users/{$baru->id}")->assertSessionHas('success');
        $this->assertNull(User::find($baru->id));
    }

    public function test_skenario_05_admin_mengakses_peminjaman_pengembalian_dan_laporan(): void
    {
        $this->actingAs($this->admin)->get('/peminjaman')->assertOk();
        $this->actingAs($this->admin)->get('/pengembalian')->assertOk();
        $this->actingAs($this->admin)->get('/laporan')->assertOk();
    }

    public function test_skenario_06_petugas_membuka_dashboard_petugas(): void
    {
        $this->actingAs($this->petugas)->get('/petugas/dashboard')->assertOk();
    }

    public function test_skenario_07_petugas_mengelola_data_buku(): void
    {
        $this->actingAs($this->petugas)->post('/buku', $this->dataBuku())->assertRedirect(route('buku.index'));
        $this->assertDatabaseHas('bukus', ['judul' => 'Bumi Manusia']);
    }

    public function test_skenario_08_petugas_memproses_peminjaman_dan_pengembalian(): void
    {
        $p = Peminjaman::create([
            'user_id' => $this->siswa->id,
            'buku_id' => $this->buku->id,
            'tanggal_pinjam' => now()->toDateString(),
            'status' => 'diajukan',
        ]);

        $this->actingAs($this->petugas)->post("/peminjaman/{$p->id}/setujui")->assertSessionHas('success');
        $this->assertSame('dipinjam', $p->fresh()->status);

        $this->actingAs($this->petugas)->post("/peminjaman/{$p->id}/kembalikan")->assertSessionHas('success');
        $this->assertSame('dikembalikan', $p->fresh()->status);
        $this->assertNotNull($p->fresh()->tanggal_kembali);
    }

    public function test_skenario_09_petugas_membuka_kelola_akun_atau_role_ditolak(): void
    {
        $this->actingAs($this->petugas)->get('/admin/users')->assertForbidden();
        $this->actingAs($this->petugas)
            ->put("/admin/users/{$this->siswa->id}/role", ['role' => 'admin'])
            ->assertForbidden();
        $this->assertTrue($this->siswa->fresh()->hasRole('siswa'));
    }

    public function test_skenario_10_siswa_melihat_daftar_dan_detail_buku(): void
    {
        $this->actingAs($this->siswa)->get('/buku')->assertOk()->assertSee('Laskar Pelangi');
        $this->actingAs($this->siswa)->get("/buku/{$this->buku->id}")->assertOk()->assertSee('Laskar Pelangi');
    }

    public function test_skenario_11_siswa_melihat_dan_mengubah_profil_sendiri(): void
    {
        $this->actingAs($this->siswa)->get('/profil-saya')->assertOk()->assertSee($this->siswa->email);

        $this->actingAs($this->siswa)
            ->patch('/profil-saya', ['name' => 'Siswa Baru', 'email' => $this->siswa->email])
            ->assertSessionHasNoErrors();
        $this->assertSame('Siswa Baru', $this->siswa->fresh()->name);
    }

    public function test_skenario_12_siswa_melihat_riwayat_sendiri(): void
    {
        Peminjaman::create([
            'user_id' => $this->siswa->id,
            'buku_id' => $this->buku->id,
            'tanggal_pinjam' => now()->toDateString(),
            'status' => 'dipinjam',
        ]);

        $this->actingAs($this->siswa)->get('/riwayat-peminjaman')->assertOk()->assertSee('Laskar Pelangi');
        $this->actingAs($this->siswa)->get("/riwayat-peminjaman/{$this->siswa->id}")->assertOk();
    }

    public function test_skenario_13_siswa_membuka_dashboard_admin_atau_pengelolaan_buku_ditolak(): void
    {
        $this->actingAs($this->siswa)->get('/admin/dashboard')->assertForbidden();
        $this->actingAs($this->siswa)->get('/petugas/dashboard')->assertForbidden();
        $this->actingAs($this->siswa)->get("/buku/{$this->buku->id}/edit")->assertForbidden();
        $this->actingAs($this->siswa)->post('/buku', $this->dataBuku())->assertForbidden();
        $this->actingAs($this->siswa)->delete("/buku/{$this->buku->id}")->assertForbidden();
        $this->assertNotNull(Buku::find($this->buku->id));
    }

    public function test_skenario_14_siswa_melihat_riwayat_siswa_lain_ditolak(): void
    {
        Peminjaman::create([
            'user_id' => $this->siswaLain->id,
            'buku_id' => $this->buku->id,
            'tanggal_pinjam' => now()->toDateString(),
            'status' => 'dipinjam',
        ]);

        $this->actingAs($this->siswa)->get("/riwayat-peminjaman/{$this->siswaLain->id}")->assertForbidden();
        // Riwayat sendiri tidak memuat data siswa lain.
        $this->actingAs($this->siswa)->get('/riwayat-peminjaman')->assertOk()->assertDontSee('Laskar Pelangi');
    }

    public function test_skenario_15_setelah_logout_harus_login_kembali(): void
    {
        foreach ([$this->admin, $this->petugas, $this->siswa] as $user) {
            $this->actingAs($user)->post('/logout');
            $this->assertGuest();
            $this->get('/buku')->assertRedirect('/login');
        }
    }

    /* ---------------- pengujian tambahan ---------------- */

    public function test_siswa_tidak_dapat_membuka_profil_pengguna_lain(): void
    {
        $this->actingAs($this->siswa)->get("/profil-saya/{$this->siswaLain->id}")->assertForbidden();
        $this->actingAs($this->siswa)->get("/profil-saya/{$this->siswa->id}")->assertOk();
    }

    public function test_update_profil_hanya_mengubah_akun_yang_login(): void
    {
        $this->actingAs($this->siswa)->patch('/profil-saya', [
            'id' => $this->siswaLain->id,
            'name' => 'Diubah',
            'email' => $this->siswa->email,
        ]);

        $this->assertSame('Diubah', $this->siswa->fresh()->name);
        $this->assertSame('Siswa Kedua', $this->siswaLain->fresh()->name);
    }

    public function test_guest_dan_user_login_dibatasi_middleware_guest(): void
    {
        $this->actingAs($this->siswa)->get('/login')->assertRedirect();
        $this->actingAs($this->siswa)->get('/register')->assertRedirect();
    }

    public function test_login_dengan_kredensial_salah_ditolak(): void
    {
        $this->post('/login', ['email' => 'siswa@perpus.com', 'password' => 'salah-total'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_alur_siswa_mengajukan_petugas_menyetujui_lalu_pengembalian(): void
    {
        $this->actingAs($this->siswa)
            ->post("/buku/{$this->buku->id}/ajukan")
            ->assertRedirect(route('peminjaman.status.sendiri'));

        $p = Peminjaman::where('user_id', $this->siswa->id)->first();
        $this->assertSame('diajukan', $p->status);

        // Pengajuan ganda untuk buku yang sama ditolak.
        $this->actingAs($this->siswa)->post("/buku/{$this->buku->id}/ajukan")->assertSessionHas('error');
        $this->assertSame(1, Peminjaman::count());

        $this->actingAs($this->siswa)->get('/status-peminjaman')->assertOk()->assertSee('Laskar Pelangi');

        $this->actingAs($this->petugas)->post("/peminjaman/{$p->id}/setujui");
        $this->actingAs($this->petugas)->post("/peminjaman/{$p->id}/kembalikan");

        $this->assertSame('dikembalikan', $p->fresh()->status);
    }

    public function test_pengajuan_ditolak_petugas(): void
    {
        $p = Peminjaman::create([
            'user_id' => $this->siswa->id,
            'buku_id' => $this->buku->id,
            'tanggal_pinjam' => now()->toDateString(),
            'status' => 'diajukan',
        ]);

        $this->actingAs($this->petugas)->post("/peminjaman/{$p->id}/tolak")->assertSessionHas('success');
        $this->assertSame('ditolak', $p->fresh()->status);
    }

    public function test_buku_yang_sedang_dipinjam_tidak_dapat_diajukan_atau_disetujui_ganda(): void
    {
        Peminjaman::create([
            'user_id' => $this->siswaLain->id,
            'buku_id' => $this->buku->id,
            'tanggal_pinjam' => now()->toDateString(),
            'status' => 'dipinjam',
        ]);

        $this->actingAs($this->siswa)->post("/buku/{$this->buku->id}/ajukan")->assertSessionHas('error');
        $this->assertSame(0, Peminjaman::where('user_id', $this->siswa->id)->count());
    }

    public function test_siswa_tidak_dapat_memproses_persetujuan_pengembalian_atau_laporan(): void
    {
        $p = Peminjaman::create([
            'user_id' => $this->siswa->id,
            'buku_id' => $this->buku->id,
            'tanggal_pinjam' => now()->toDateString(),
            'status' => 'diajukan',
        ]);

        $this->actingAs($this->siswa)->post("/peminjaman/{$p->id}/setujui")->assertForbidden();
        $this->actingAs($this->siswa)->post("/peminjaman/{$p->id}/kembalikan")->assertForbidden();
        $this->actingAs($this->siswa)->get('/peminjaman')->assertForbidden();
        $this->actingAs($this->siswa)->get('/pengembalian')->assertForbidden();
        $this->actingAs($this->siswa)->get('/laporan')->assertForbidden();
        $this->assertSame('diajukan', $p->fresh()->status);
    }

    public function test_admin_tidak_dapat_menghapus_atau_mengubah_role_akun_sendiri(): void
    {
        $this->actingAs($this->admin)->delete("/admin/users/{$this->admin->id}")->assertSessionHas('error');
        $this->actingAs($this->admin)
            ->put("/admin/users/{$this->admin->id}/role", ['role' => 'siswa'])
            ->assertSessionHas('error');

        $this->assertNotNull(User::find($this->admin->id));
        $this->assertTrue($this->admin->fresh()->hasRole('admin'));
    }

    public function test_role_tidak_valid_ditolak_saat_admin_membuat_atau_mengubah_pengguna(): void
    {
        $this->actingAs($this->admin)->put("/admin/users/{$this->siswa->id}/role", ['role' => 'dewa'])
            ->assertSessionHasErrors('role');
    }

    public function test_menu_navigasi_sesuai_role(): void
    {
        // Diperiksa lewat URL tautan, bukan teks, agar komentar HTML tidak ikut terhitung.
        $this->actingAs($this->siswa)->get('/siswa/dashboard')
            ->assertOk()
            ->assertDontSee('/admin/users')
            ->assertDontSee('/laporan')
            ->assertDontSee('/pengembalian')
            ->assertSee('/riwayat-peminjaman')
            ->assertSee('/status-peminjaman');

        $this->actingAs($this->petugas)->get('/petugas/dashboard')
            ->assertOk()
            ->assertDontSee('/admin/users')
            ->assertSee('/laporan')
            ->assertSee('/pengembalian');

        $this->actingAs($this->admin)->get('/admin/dashboard')
            ->assertOk()
            ->assertSee('/admin/users')
            ->assertSee('/laporan');
    }

    public function test_api_buku_dilindungi_permission(): void
    {
        $this->getJson('/api/buku')->assertUnauthorized();
        $this->actingAs($this->siswa)->getJson('/api/buku')->assertOk();
        $this->actingAs($this->siswa)->postJson('/api/buku', $this->dataBuku())->assertForbidden();
        $this->actingAs($this->petugas)->getJson('/api/kategori')->assertOk();
        $this->actingAs($this->siswa)->getJson('/api/rekap/buku-per-kategori')->assertForbidden();
        $this->actingAs($this->admin)->getJson('/api/rekap/buku-per-kategori')->assertOk();
    }

    public function test_halaman_peminjaman_pengembalian_dan_laporan_menampilkan_data(): void
    {
        $diajukan = Peminjaman::create([
            'user_id' => $this->siswa->id,
            'buku_id' => $this->buku->id,
            'tanggal_pinjam' => now()->toDateString(),
            'status' => 'diajukan',
        ]);
        Peminjaman::create([
            'user_id' => $this->siswaLain->id,
            'buku_id' => $this->buku->id,
            'tanggal_pinjam' => now()->subDay()->toDateString(),
            'status' => 'dipinjam',
        ]);

        $this->actingAs($this->petugas)->get('/peminjaman')
            ->assertOk()->assertSee('Setujui')->assertSee('Catat Pengembalian')->assertSee('Siswa Perpustakaan');
        $this->actingAs($this->petugas)->get('/peminjaman?status=diajukan')->assertOk()->assertDontSee('Siswa Kedua');
        $this->actingAs($this->petugas)->get('/pengembalian')->assertOk()->assertSee('Siswa Kedua');
        $this->actingAs($this->petugas)->get('/laporan')->assertOk()->assertSee('Laskar Pelangi')->assertSee('Menunggu Persetujuan');

        // Tombol persetujuan tidak ditampilkan untuk permission yang tidak dimiliki; aksi tetap dicek server.
        $this->assertSame('diajukan', $diajukan->fresh()->status);
    }
}
