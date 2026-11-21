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

class Modul6KeamananTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $petugas;
    protected User $siswa;
    protected User $siswa2;
    protected Buku $buku;
    protected Kategori $kategori;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);

        $this->kategori = Kategori::firstOrCreate(['nama' => 'Teknologi Informasi']);
        $this->buku = Buku::firstOrCreate(
            ['judul' => 'Pemrograman Web Lanjut Modern'],
            [
                'kategori_id' => $this->kategori->id,
                'penulis' => 'Dr. Imamah',
                'tahun_terbit' => 2024,
            ]
        );

        $this->admin = User::where('email', 'admin@gmail.com')->first();
        $this->petugas = User::where('email', 'petugas@gmail.com')->first();
        $this->siswa = User::where('email', 'siswa@gmail.com')->first();
        $this->siswa2 = User::where('email', 'siswa2@gmail.com')->first();
    }

    /**
     * Skenario 1: Belum login membuka /peminjaman/1 -> Dialihkan ke halaman login
     */
    public function test_skenario_1_belum_login_akses_peminjaman_dialihkan_ke_login(): void
    {
        $response = $this->get('/peminjaman/1');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /**
     * Skenario 2: Admin menambah buku berjudul <script>alert(1)</script> -> Judul tampil sebagai teks, tanpa popup
     */
    public function test_skenario_2_tambah_buku_xss_tampil_sebagai_teks_escaped(): void
    {
        $payload = "<script>alert('250441100137')</script>";

        // Tambah buku dengan payload XSS
        $this->actingAs($this->admin)->post('/buku', [
            'kategori_id'  => $this->kategori->id,
            'judul'        => $payload,
            'penulis'      => 'Attacker',
            'tahun_terbit' => 2024,
        ]);

        $bukuXss = Buku::where('penulis', 'Attacker')->first();
        $this->assertNotNull($bukuXss);

        // Buka katalog buku
        $response = $this->actingAs($this->admin)->get('/buku');
        $response->assertStatus(200);

        // Memastikan teks ter-escape sebagai entitas HTML (tidak dieksekusi mentah)
        $response->assertSee('&lt;script&gt;alert(&#039;250441100137&#039;)&lt;/script&gt;', false);
        $response->assertDontSee($payload, false);
    }

    /**
     * Skenario 3: Admin membuka /buku-cari?judul=' OR '1'='1 -> Hasil kosong []
     */
    public function test_skenario_3_sqli_pada_buku_cari_menghasilkan_kosong(): void
    {
        $response = $this->actingAs($this->admin)->get("/buku-cari?judul=' OR '1'='1");

        $response->assertStatus(200);
        $response->assertExactJson([]);
    }

    /**
     * Skenario 4: Admin mengirim DELETE /buku/{id} tanpa token CSRF -> 419 Page Expired
     */
    public function test_skenario_4_delete_buku_tanpa_csrf_menghasilkan_419(): void
    {
        $request = \Illuminate\Http\Request::create("/buku/{$this->buku->id}", 'DELETE');
        $session = $this->app['session']->driver();
        $session->start();
        $request->setLaravelSession($session);

        $middleware = new class($this->app, $this->app['encrypter']) extends \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken {
            protected function runningUnitTests()
            {
                return false; // Paksa pemeriksaan CSRF seperti di lingkungan produksi / Postman
            }
        };

        try {
            $middleware->handle($request, function () {});
            $this->fail('Harus melempar TokenMismatchException (HTTP 419 Page Expired)');
        } catch (\Illuminate\Session\TokenMismatchException $e) {
            // Berhasil memverifikasi bahwa ketiadaan token menghasilkan TokenMismatchException (419)
            $this->assertTrue(true);
        }
    }

    /**
     * Skenario 5: Admin menghapus buku lewat tombol Hapus (dengan token CSRF) -> Buku terhapus
     */
    public function test_skenario_5_admin_menghapus_buku_dengan_csrf_berhasil(): void
    {
        $bukuBaru = Buku::create([
            'kategori_id'  => $this->kategori->id,
            'judul'        => 'Buku Untuk Dihapus',
            'penulis'      => 'Penulis Uji',
            'tahun_terbit' => 2023,
        ]);

        $response = $this->actingAs($this->admin)->delete("/buku/{$bukuBaru->id}");
        $response->assertStatus(302);

        $this->assertDatabaseMissing('bukus', ['id' => $bukuBaru->id]);
    }

    /**
     * Skenario 6: Admin membuka tautan jebakan.html saat login -> Buku tidak terhapus (404 atau 405)
     */
    public function test_skenario_6_jebakan_csrf_via_get_tidak_menghapus_buku(): void
    {
        $response = $this->actingAs($this->admin)->get("/buku/{$this->buku->id}/hapus");

        // Route GET /buku/{id}/hapus sudah dihapus, sehingga menghasilkan 404 Not Found
        $response->assertStatus(404);
        $this->assertDatabaseHas('bukus', ['id' => $this->buku->id]);
    }

    /**
     * Skenario 7: Siswa membuka /buku/create langsung pada URL -> 403 Forbidden, tombol kelola tidak tampil
     */
    public function test_skenario_7_siswa_akses_buku_create_ditolak_gate(): void
    {
        // Akses langsung ke URL /buku/create
        $response = $this->actingAs($this->siswa)->get('/buku/create');
        $response->assertStatus(403);

        // Buka katalog buku, pastikan form/tombol kelola tidak tampil
        $responseIndex = $this->actingAs($this->siswa)->get('/buku');
        $responseIndex->assertStatus(200);
        $responseIndex->assertDontSee('Tambah Buku Baru');
    }

    /**
     * Skenario 8: Siswa membuka peminjaman milik sendiri -> 200, data tampil
     */
    public function test_skenario_8_siswa_membuka_peminjaman_milik_sendiri(): void
    {
        $anggota = Anggota::where('user_id', $this->siswa->id)->first();
        $peminjaman = Peminjaman::create([
            'user_id'        => $this->siswa->id,
            'anggota_id'     => $anggota ? $anggota->id : 1,
            'buku_id'        => $this->buku->id,
            'tanggal_pinjam' => now()->toDateString(),
            'status'         => 'disetujui',
        ]);

        $response = $this->actingAs($this->siswa)->get("/peminjaman/{$peminjaman->id}");
        $response->assertStatus(200);
        $response->assertSee($this->buku->judul);

        $peminjaman->delete();
    }

    /**
     * Skenario 9: Siswa membuka peminjaman milik siswa lain -> 403 Forbidden (Policy)
     */
    public function test_skenario_9_siswa_membuka_peminjaman_siswa_lain_ditolak_policy(): void
    {
        $anggota2 = Anggota::where('user_id', $this->siswa2->id)->first();
        $peminjamanSiswa2 = Peminjaman::create([
            'user_id'        => $this->siswa2->id,
            'anggota_id'     => $anggota2 ? $anggota2->id : 2,
            'buku_id'        => $this->buku->id,
            'tanggal_pinjam' => now()->toDateString(),
            'status'         => 'disetujui',
        ]);

        // Siswa 1 mencoba mengakses peminjaman milik Siswa 2
        $response = $this->actingAs($this->siswa)->get("/peminjaman/{$peminjamanSiswa2->id}");
        $response->assertStatus(403);

        $peminjamanSiswa2->delete();
    }

    /**
     * Skenario 10: Petugas membuka peminjaman milik siswa mana pun -> 200, data tampil
     */
    public function test_skenario_10_petugas_membuka_peminjaman_siswa_mana_pun(): void
    {
        $anggota = Anggota::where('user_id', $this->siswa->id)->first();
        $peminjaman = Peminjaman::create([
            'user_id'        => $this->siswa->id,
            'anggota_id'     => $anggota ? $anggota->id : 1,
            'buku_id'        => $this->buku->id,
            'tanggal_pinjam' => now()->toDateString(),
            'status'         => 'disetujui',
        ]);

        $response = $this->actingAs($this->petugas)->get("/peminjaman/{$peminjaman->id}");
        $response->assertStatus(200);
        $response->assertSee($this->buku->judul);

        $peminjaman->delete();
    }

    /**
     * Skenario 11: Membuka halaman error dengan APP_DEBUG=false -> Pesan error umum tanpa jejak kode
     */
    public function test_skenario_11_halaman_error_dengan_app_debug_false_tidak_bocor_jejak_kode(): void
    {
        // Set konfigurasi debug false
        config(['app.debug' => false]);

        // Request ke rute yang melempar exception
        $this->app['router']->get('/test-error-debug', function () {
            throw new \RuntimeException('Database Connection Secret Key Leak Test');
        });

        $response = $this->get('/test-error-debug');
        $response->assertStatus(500);

        // Tidak boleh menampilkan jejak kode, path file, atau exception message
        $response->assertDontSee('Database Connection Secret Key Leak Test');
        $response->assertDontSee('RuntimeException');
        $response->assertDontSee('.php:');
    }

    /**
     * Skenario 12: Membuka /.env dan login admin dengan password lama -> 404 atau 403; login ditolak
     */
    public function test_skenario_12_env_terproteksi_dan_login_admin_password_lama_ditolak(): void
    {
        // 1. Coba login admin dengan password lama bawaan seeder ('password')
        $responseLogin = $this->post('/login', [
            'email'    => 'admin@gmail.com',
            'password' => 'password',
        ]);

        // Login harus ditolak (error session validation)
        $responseLogin->assertSessionHasErrors('email');
        $this->assertGuest();

        // 2. Akses ke /.env melalui route web
        $responseEnv = $this->get('/.env');
        $this->assertTrue(in_array($responseEnv->getStatusCode(), [403, 404]));
    }
}
