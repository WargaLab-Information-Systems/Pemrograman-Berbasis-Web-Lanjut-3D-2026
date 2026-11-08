<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Modul5RbacTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Pastikan role & permission serta data buku terdaftar
        $this->seed(\Database\Seeders\RoleAndPermissionSeeder::class);
        $this->seed(\Database\Seeders\KategoriSeeder::class);
        $this->seed(\Database\Seeders\BukuSeeder::class);
        $this->seed(\Database\Seeders\UserSeeder::class);
    }

    /**
     * Skenario 1: Belum login membuka halaman dashboard admin -> Dialihkan ke halaman login
     */
    public function test_skenario_1_guest_akses_dashboard_admin_dialihkan_ke_login(): void
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/login');
    }

    /**
     * Skenario 2: Admin membuka dashboard admin -> Dashboard admin dapat diakses
     */
    public function test_skenario_2_admin_dapat_mengakses_dashboard_admin(): void
    {
        $admin = User::where('email', 'admin@perpustakaan.test')->first();

        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Dashboard Administrasi Utama');
    }

    /**
     * Skenario 3: Admin menambah, mengubah, dan menghapus data buku -> Operasi berhasil
     */
    public function test_skenario_3_admin_mengelola_data_buku(): void
    {
        $admin = User::where('email', 'admin@perpustakaan.test')->first();
        $kategori = Kategori::first() ?? Kategori::create(['nama' => 'Teknologi']);

        // Tambah buku via API
        $postResponse = $this->actingAs($admin)->postJson('/api/buku', [
            'kategori_id' => $kategori->id,
            'judul' => 'Buku Uji Coba Admin',
            'penulis' => 'Penulis Uji Admin',
            'tahun_terbit' => 2024,
        ]);
        $postResponse->assertStatus(201);
        $bukuId = $postResponse->json('data.id');

        // Ubah buku via API
        $putResponse = $this->actingAs($admin)->putJson("/api/buku/{$bukuId}", [
            'kategori_id' => $kategori->id,
            'judul' => 'Buku Uji Coba Admin Diperbarui',
            'penulis' => 'Penulis Uji Admin',
            'tahun_terbit' => 2024,
        ]);
        $putResponse->assertStatus(200);

        // Hapus buku via API
        $deleteResponse = $this->actingAs($admin)->deleteJson("/api/buku/{$bukuId}");
        $deleteResponse->assertStatus(200);
    }

    /**
     * Skenario 4: Admin mengelola akun dan role pengguna -> Operasi berhasil
     */
    public function test_skenario_4_admin_mengelola_akun_dan_role_pengguna(): void
    {
        $admin = User::where('email', 'admin@perpustakaan.test')->first();

        // 1. Lihat daftar user
        $indexResponse = $this->actingAs($admin)->get('/admin/users');
        $indexResponse->assertStatus(200);

        // 2. Tambah user dengan role siswa
        $createResponse = $this->actingAs($admin)->post('/admin/users', [
            'name' => 'User Baru Uji',
            'email' => 'userbaru@perpustakaan.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'siswa',
        ]);
        $createResponse->assertRedirect('/admin/users');

        $newUser = User::where('email', 'userbaru@perpustakaan.test')->first();
        $this->assertNotNull($newUser);
        $this->assertTrue($newUser->hasRole('siswa'));

        // 3. Ubah role user menjadi petugas
        $updateResponse = $this->actingAs($admin)->put("/admin/users/{$newUser->id}", [
            'name' => 'User Baru Diperbarui',
            'email' => 'userbaru@perpustakaan.test',
            'role' => 'petugas',
        ]);
        $updateResponse->assertRedirect('/admin/users');
        $this->assertTrue($newUser->fresh()->hasRole('petugas'));

        // 4. Hapus user
        $deleteResponse = $this->actingAs($admin)->delete("/admin/users/{$newUser->id}");
        $deleteResponse->assertRedirect('/admin/users');
        $this->assertNull(User::where('email', 'userbaru@perpustakaan.test')->first());
    }

    /**
     * Skenario 5: Admin mengakses data peminjaman, pengembalian, dan laporan -> Data dapat diakses
     */
    public function test_skenario_5_admin_mengakses_peminjaman_dan_laporan(): void
    {
        $admin = User::where('email', 'admin@perpustakaan.test')->first();

        // Peminjaman
        $peminjamanResponse = $this->actingAs($admin)->get('/peminjaman');
        $peminjamanResponse->assertStatus(200);

        // Laporan
        $laporanResponse = $this->actingAs($admin)->get('/laporan');
        $laporanResponse->assertStatus(200);
        $laporanResponse->assertSee('Rekapitulasi Perpustakaan');
    }

    /**
     * Skenario 6: Petugas membuka dashboard petugas -> Dashboard petugas dapat diakses
     */
    public function test_skenario_6_petugas_membuka_dashboard_petugas(): void
    {
        $petugas = User::where('email', 'petugas@perpustakaan.test')->first();

        $response = $this->actingAs($petugas)->get('/petugas/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Dashboard Operasional Petugas');
    }

    /**
     * Skenario 7: Petugas mengelola data buku -> Operasi berhasil
     */
    public function test_skenario_7_petugas_mengelola_data_buku(): void
    {
        $petugas = User::where('email', 'petugas@perpustakaan.test')->first();
        $kategori = Kategori::first() ?? Kategori::create(['nama' => 'Sains']);

        $postResponse = $this->actingAs($petugas)->postJson('/api/buku', [
            'kategori_id' => $kategori->id,
            'judul' => 'Buku Petugas Uji',
            'penulis' => 'Penulis Petugas',
            'tahun_terbit' => 2023,
        ]);
        $postResponse->assertStatus(201);
        $bukuId = $postResponse->json('data.id');

        $deleteResponse = $this->actingAs($petugas)->deleteJson("/api/buku/{$bukuId}");
        $deleteResponse->assertStatus(200);
    }

    /**
     * Skenario 8: Petugas memproses peminjaman dan pengembalian -> Operasi berhasil
     */
    public function test_skenario_8_petugas_memproses_peminjaman_dan_pengembalian(): void
    {
        $petugas = User::where('email', 'petugas@perpustakaan.test')->first();
        $siswa = User::where('email', 'siswa1@perpustakaan.test')->first();
        $buku = Buku::first();

        // Buat data peminjaman berstatus menunggu
        $peminjaman = Peminjaman::create([
            'user_id' => $siswa->id,
            'anggota_id' => $siswa->anggota?->id ?? \App\Models\Anggota::first()?->id ?? 1,
            'buku_id' => $buku->id,
            'tanggal_pinjam' => now()->toDateString(),
            'status' => 'menunggu',
        ]);

        // Petugas menyetujui peminjaman
        $setujuiResponse = $this->actingAs($petugas)->patch("/peminjaman/{$peminjaman->id}/setujui");
        $setujuiResponse->assertRedirect();
        $this->assertEquals('dipinjam', $peminjaman->fresh()->status);

        // Petugas mencatat pengembalian
        $kembalikanResponse = $this->actingAs($petugas)->patch("/peminjaman/{$peminjaman->id}/kembalikan");
        $kembalikanResponse->assertRedirect();
        $this->assertEquals('dikembalikan', $peminjaman->fresh()->status);
    }

    /**
     * Skenario 9: Petugas membuka halaman pengelolaan akun atau role pengguna -> Akses ditolak (403)
     */
    public function test_skenario_9_petugas_membuka_kelola_user_akses_ditolak(): void
    {
        $petugas = User::where('email', 'petugas@perpustakaan.test')->first();

        $response = $this->actingAs($petugas)->get('/admin/users');
        $response->assertStatus(403);
    }

    /**
     * Skenario 10: Siswa melihat daftar data buku dan detail buku -> Berhasil
     */
    public function test_skenario_10_siswa_melihat_daftar_dan_detail_buku(): void
    {
        $siswa = User::where('email', 'siswa1@perpustakaan.test')->first();
        $buku = Buku::first();

        // Melihat katalog
        $indexResponse = $this->actingAs($siswa)->get('/buku');
        $indexResponse->assertStatus(200);

        // Melihat detail
        $showResponse = $this->actingAs($siswa)->get("/buku/{$buku->id}");
        $showResponse->assertStatus(200);
        $showResponse->assertSee($buku->judul);
    }

    /**
     * Skenario 11: Siswa melihat dan mengubah profil sendiri -> Berhasil
     */
    public function test_skenario_11_siswa_melihat_dan_mengubah_profil_sendiri(): void
    {
        $siswa = User::where('email', 'siswa1@perpustakaan.test')->first();

        // Buka form profil
        $editResponse = $this->actingAs($siswa)->get('/profile');
        $editResponse->assertStatus(200);

        // Update profil
        $patchResponse = $this->actingAs($siswa)->patch('/profile', [
            'name' => 'Ahmad Siswa 1 Updated',
            'email' => 'siswa1@perpustakaan.test',
        ]);
        $patchResponse->assertRedirect('/profile');
        $this->assertEquals('Ahmad Siswa 1 Updated', $siswa->fresh()->name);
    }

    /**
     * Skenario 12: Siswa melihat riwayat peminjaman sendiri -> Berhasil
     */
    public function test_skenario_12_siswa_melihat_riwayat_peminjaman_sendiri(): void
    {
        $siswa = User::where('email', 'siswa1@perpustakaan.test')->first();

        $response = $this->actingAs($siswa)->get('/siswa/riwayat-pinjam');
        $response->assertStatus(200);
        $response->assertSee('Riwayat Peminjaman Buku Saya');
    }

    /**
     * Skenario 13: Siswa membuka dashboard admin atau kelola buku -> Akses ditolak (403)
     */
    public function test_skenario_13_siswa_membuka_dashboard_admin_akses_ditolak(): void
    {
        $siswa = User::where('email', 'siswa1@perpustakaan.test')->first();

        // Coba akses dashboard admin
        $adminResponse = $this->actingAs($siswa)->get('/admin/dashboard');
        $adminResponse->assertStatus(403);

        // Coba akses route kelola buku
        $kelolaResponse = $this->actingAs($siswa)->get('/kelola-buku');
        $kelolaResponse->assertStatus(403);
    }

    /**
     * Skenario 14: Siswa mencoba melihat riwayat peminjaman siswa lain -> Akses ditolak (403)
     */
    public function test_skenario_14_siswa_mencoba_melihat_riwayat_siswa_lain_akses_ditolak(): void
    {
        $siswa1 = User::where('email', 'siswa1@perpustakaan.test')->first();
        $siswa2 = User::where('email', 'siswa2@perpustakaan.test')->first();
        $buku = Buku::first();

        // Buat peminjaman milik siswa 2
        $peminjamanSiswa2 = Peminjaman::create([
            'user_id' => $siswa2->id,
            'anggota_id' => $siswa2->anggota?->id ?? \App\Models\Anggota::first()?->id ?? 1,
            'buku_id' => $buku->id,
            'tanggal_pinjam' => now()->toDateString(),
            'status' => 'dipinjam',
            'catatan' => 'Peminjaman rahasia milik siswa 2',
        ]);

        // Siswa 1 mencoba mengakses detail peminjaman milik Siswa 2 via URL langsung
        $response = $this->actingAs($siswa1)->get("/peminjaman/{$peminjamanSiswa2->id}");
        $response->assertStatus(403);
    }

    /**
     * Skenario 15: Melakukan logout lalu membuka halaman yang dilindungi -> Pengguna harus login kembali
     */
    public function test_skenario_15_logout_lalu_akses_halaman_dilindungi_harus_login(): void
    {
        $admin = User::where('email', 'admin@perpustakaan.test')->first();

        // Login dan logout
        $logoutResponse = $this->actingAs($admin)->post('/logout');
        $logoutResponse->assertRedirect('/');

        // Buka halaman dilindungi setelah logout
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/login');
    }
}
