<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Tests\TestCase;

class SecurityAuditTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
    }

    // A1: XSS - Judul buku dengan script dieksekusi / tidak di-escape
    public function test_a1_xss_menampilkan_script_tanpa_escaping(): void
    {
        $admin = User::where('email', 'admin@perpustakaan.com')->first();
        $buku = Buku::create([
            'kategori_id' => 1,
            'judul' => "<script>alert('250441100009')</script>",
            'penulis' => 'Penulis XSS',
            'tahun_terbit' => 2024,
        ]);

        $response = $this->actingAs($admin)->get('/buku');
        $response->assertOk();
        $response->assertSee("<script>alert('250441100009')</script>", false);
    }

    // A2: SQL Injection - /buku-cari?judul=' OR '1'='1 menampilkan seluruh data buku
    public function test_a2_sql_injection_menampilkan_seluruh_buku(): void
    {
        $admin = User::where('email', 'admin@perpustakaan.com')->first();
        $totalBuku = Buku::count();

        $response = $this->actingAs($admin)->get("/buku-cari?judul=' OR '1'='1");
        $response->assertOk();
        $this->assertCount($totalBuku, $response->json());
    }

    // A3: CSRF - Penghapusan buku dapat dipicu melalui request GET
    public function test_a3_csrf_menghapus_buku_melalui_metode_get(): void
    {
        $admin = User::where('email', 'admin@perpustakaan.com')->first();
        $buku = Buku::create([
            'kategori_id' => 1,
            'judul' => 'Buku Target CSRF',
            'penulis' => 'Penulis Target',
            'tahun_terbit' => 2024,
        ]);

        $response = $this->actingAs($admin)->get("/buku/{$buku->id}/hapus");
        $response->assertOk();
        $this->assertDatabaseMissing('bukus', ['id' => $buku->id]);
    }

    // A4: IDOR - Siswa dapat melihat peminjaman milik siswa lain dengan status 200
    public function test_a4_idor_siswa_dapat_melihat_peminjaman_siswa_lain(): void
    {
        $siswa1 = User::where('email', 'siswa1@perpustakaan.com')->first();
        $siswa2 = User::where('email', 'siswa2@perpustakaan.com')->first();
        $buku = Buku::first();
        $anggota = Anggota::first();

        $pinjamSiswa2 = Peminjaman::create([
            'user_id' => $siswa2->id,
            'anggota_id' => $anggota->id,
            'buku_id' => $buku->id,
            'tanggal_pinjam' => now()->toDateString(),
            'status' => 'menunggu',
        ]);

        $response = $this->actingAs($siswa1)->get("/peminjaman/{$pinjamSiswa2->id}");
        $response->assertOk();
    }
}

