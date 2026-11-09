<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Tests\TestCase;

class AuthenticationAndRbacTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
    }

    // Skenario 1: Belum login membuka dashboard admin -> Dialihkan ke login
    public function test_skenario_1_tamu_buka_dashboard_admin_dialihkan_ke_login(): void
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/login');
    }

    // Skenario 2: Admin membuka dashboard admin -> Dapat diakses
    public function test_skenario_2_admin_dapat_akses_dashboard_admin(): void
    {
        $admin = User::where('email', 'admin@perpustakaan.com')->first();
        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertOk();
    }

    // Skenario 3: Admin menambah data buku -> Operasi berhasil
    public function test_skenario_3_admin_dapat_kelola_buku(): void
    {
        $admin = User::where('email', 'admin@perpustakaan.com')->first();
        $kategori = Kategori::first() ?? Kategori::create(['nama' => 'Teknologi']);

        $response = $this->actingAs($admin)->postJson('/api/buku', [
            'kategori_id' => $kategori->id,
            'judul' => 'Buku Uji Admin',
            'penulis' => 'Penulis Uji',
            'tahun_terbit' => 2024,
        ]);

        $response->assertStatus(201);
    }

    // Skenario 4: Admin mengelola akun dan role pengguna -> Operasi berhasil
    public function test_skenario_4_admin_dapat_kelola_akun_dan_role(): void
    {
        $admin = User::where('email', 'admin@perpustakaan.com')->first();
        $siswa = User::where('email', 'siswa1@perpustakaan.com')->first();

        $pageResponse = $this->actingAs($admin)->get('/admin/users');
        $pageResponse->assertOk();

        $updateRoleResponse = $this->actingAs($admin)->post('/admin/users/'.$siswa->id.'/role', [
            'role' => 'petugas',
        ]);
        $updateRoleResponse->assertRedirect('/admin/users');
        $this->assertTrue($siswa->fresh()->hasRole('petugas'));
    }

    // Skenario 5: Admin mengakses data peminjaman, pengembalian, dan laporan -> Berhasil
    public function test_skenario_5_admin_akses_peminjaman_dan_laporan(): void
    {
        $admin = User::where('email', 'admin@perpustakaan.com')->first();

        $peminjamanResponse = $this->actingAs($admin)->get('/admin/peminjaman');
        $peminjamanResponse->assertOk();

        $laporanResponse = $this->actingAs($admin)->get('/admin/laporan');
        $laporanResponse->assertOk();
    }

    // Skenario 6: Petugas membuka dashboard petugas -> Dapat diakses
    public function test_skenario_6_petugas_dapat_akses_dashboard_petugas(): void
    {
        $petugas = User::where('email', 'petugas@perpustakaan.com')->first();
        $response = $this->actingAs($petugas)->get('/petugas/dashboard');
        $response->assertOk();
    }

    // Skenario 7: Petugas mengelola data buku -> Operasi berhasil
    public function test_skenario_7_petugas_dapat_kelola_buku(): void
    {
        $petugas = User::where('email', 'petugas@perpustakaan.com')->first();
        $kategori = Kategori::first() ?? Kategori::create(['nama' => 'Teknologi']);

        $response = $this->actingAs($petugas)->postJson('/api/buku', [
            'kategori_id' => $kategori->id,
            'judul' => 'Buku Uji Petugas',
            'penulis' => 'Penulis Petugas',
            'tahun_terbit' => 2024,
        ]);

        $response->assertStatus(201);
    }

    // Skenario 8: Petugas memproses peminjaman dan pengembalian -> Operasi berhasil
    public function test_skenario_8_petugas_memproses_peminjaman(): void
    {
        $petugas = User::where('email', 'petugas@perpustakaan.com')->first();
        $peminjaman = Peminjaman::first();

        $response = $this->actingAs($petugas)->get('/petugas/peminjaman');
        $response->assertOk();

        if ($peminjaman) {
            $kembaliResponse = $this->actingAs($petugas)->post('/petugas/peminjaman/'.$peminjaman->id.'/kembali');
            $kembaliResponse->assertRedirect();
        }
    }

    // Skenario 9: Petugas membuka halaman kelola akun atau role pengguna -> Akses ditolak (403)
    public function test_skenario_9_petugas_ditolak_kelola_akun_dan_role(): void
    {
        $petugas = User::where('email', 'petugas@perpustakaan.com')->first();
        $response = $this->actingAs($petugas)->get('/admin/users');
        $response->assertForbidden();
    }

    // Skenario 10: Siswa melihat daftar dan detail buku -> Berhasil
    public function test_skenario_10_siswa_dapat_melihat_daftar_dan_detail_buku(): void
    {
        $siswa = User::where('email', 'siswa1@perpustakaan.com')->first();
        $buku = Buku::first();

        $indexResponse = $this->actingAs($siswa)->get('/buku');
        $indexResponse->assertOk();

        if ($buku) {
            $detailResponse = $this->actingAs($siswa)->get('/buku/'.$buku->id);
            $detailResponse->assertOk();
        }
    }

    // Skenario 11: Siswa melihat dan mengubah profil sendiri -> Berhasil
    public function test_skenario_11_siswa_melihat_dan_mengubah_profil_sendiri(): void
    {
        $siswa = User::where('email', 'siswa1@perpustakaan.com')->first();

        $editResponse = $this->actingAs($siswa)->get('/profile');
        $editResponse->assertOk();

        $updateResponse = $this->actingAs($siswa)->patch('/profile', [
            'name' => 'Siswa Satu Diperbarui',
            'email' => $siswa->email,
        ]);
        $updateResponse->assertRedirect('/profile');
        $this->assertEquals('Siswa Satu Diperbarui', $siswa->fresh()->name);
    }

    // Skenario 12: Siswa melihat riwayat peminjaman sendiri -> Berhasil
    public function test_skenario_12_siswa_melihat_riwayat_peminjaman_sendiri(): void
    {
        $siswa1 = User::where('email', 'siswa1@perpustakaan.com')->first();
        $response = $this->actingAs($siswa1)->get('/riwayat-peminjaman');
        $response->assertOk();
    }

    // Skenario 13: Siswa membuka dashboard admin atau halaman kelola buku -> Akses ditolak (403)
    public function test_skenario_13_siswa_ditolak_buka_dashboard_admin_atau_kelola_buku(): void
    {
        $siswa = User::where('email', 'siswa1@perpustakaan.com')->first();

        $dashboardResponse = $this->actingAs($siswa)->get('/admin/dashboard');
        $dashboardResponse->assertForbidden();

        $kategori = Kategori::first() ?? Kategori::create(['nama' => 'Teknologi']);
        $kelolaBukuResponse = $this->actingAs($siswa)->postJson('/api/buku', [
            'kategori_id' => $kategori->id,
            'judul' => 'Buku Siswa Ilegal',
            'penulis' => 'Penulis Siswa',
            'tahun_terbit' => 2024,
        ]);
        $kelolaBukuResponse->assertForbidden();
    }

    // Skenario 14: Siswa mencoba melihat riwayat peminjaman siswa lain -> Akses ditolak (403)
    public function test_skenario_14_siswa_ditolak_lihat_riwayat_siswa_lain(): void
    {
        $siswa1 = User::where('email', 'siswa1@perpustakaan.com')->first();
        $siswa2 = User::where('email', 'siswa2@perpustakaan.com')->first();

        $peminjamanSiswa2 = Peminjaman::where('user_id', $siswa2->id)->first();

        if ($peminjamanSiswa2) {
            $response = $this->actingAs($siswa1)->get('/riwayat-peminjaman/'.$peminjamanSiswa2->id);
            $response->assertForbidden();
        }
    }

    // Skenario 15: Melakukan logout lalu membuka halaman dilindungi -> Harus login kembali
    public function test_skenario_15_logout_lalu_akses_halaman_dilindungi_harus_login(): void
    {
        $admin = User::where('email', 'admin@perpustakaan.com')->first();

        // Login lalu logout
        $this->actingAs($admin)->post('/logout');
        $this->assertGuest();

        // Mencoba akses halaman yang dilindungi
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/login');

        $profileResponse = $this->get('/profile');
        $profileResponse->assertRedirect('/login');
    }

    // Fitur Mengajukan Pinjam Buku oleh Siswa (Permission #9)
    public function test_siswa_dapat_mengajukan_pinjam_buku(): void
    {
        $siswa = User::where('email', 'siswa1@perpustakaan.com')->first();
        $buku = Buku::latest()->first();

        // Ajukan peminjaman buku
        $response = $this->actingAs($siswa)->post('/pinjam/'.$buku->id);
        $response->assertRedirect('/riwayat-peminjaman');

        // Pastikan tercatat di database dengan status 'menunggu'
        $this->assertDatabaseHas('peminjamans', [
            'user_id' => $siswa->id,
            'buku_id' => $buku->id,
            'status' => 'menunggu',
            'tanggal_kembali' => null,
        ]);
    }

    // Uji Proteksi IDOR / Pemisahan Role: Admin tidak boleh masuk ke route khusus Petugas (/petugas/*)
    public function test_admin_ditolak_akses_area_khusus_petugas(): void
    {
        $admin = User::where('email', 'admin@perpustakaan.com')->first();
        $response = $this->actingAs($admin)->get('/petugas/dashboard');
        $response->assertForbidden();
    }

    // Uji Validasi Peminjaman dan Pengembalian oleh Admin
    public function test_admin_dapat_validasi_persetujuan_dan_pengembalian(): void
    {
        $admin = User::where('email', 'admin@perpustakaan.com')->first();
        $siswa = User::where('email', 'siswa1@perpustakaan.com')->first();
        $buku = Buku::first();
        $anggota = Anggota::first() ?? Anggota::create(['nama' => 'Anggota Default', 'nomor_telepon' => '08123456789', 'alamat' => 'Alamat']);

        $pinjam = Peminjaman::create([
            'user_id' => $siswa->id,
            'anggota_id' => $anggota->id,
            'buku_id' => $buku->id,
            'tanggal_pinjam' => now()->toDateString(),
            'status' => 'menunggu',
        ]);

        // Admin menyetujui peminjaman
        $approve = $this->actingAs($admin)->post('/admin/peminjaman/'.$pinjam->id.'/setujui');
        $approve->assertRedirect();
        $this->assertEquals('disetujui', $pinjam->fresh()->status);

        // Admin memvalidasi pengembalian
        $kembali = $this->actingAs($admin)->post('/admin/peminjaman/'.$pinjam->id.'/kembali');
        $kembali->assertRedirect();
        $this->assertEquals('dikembalikan', $pinjam->fresh()->status);
        $this->assertNotNull($pinjam->fresh()->tanggal_kembali);
    }

    // Uji Validasi Peminjaman dan Pengembalian oleh Petugas
    public function test_petugas_dapat_validasi_persetujuan_dan_pengembalian(): void
    {
        $petugas = User::where('email', 'petugas@perpustakaan.com')->first();
        $siswa = User::where('email', 'siswa2@perpustakaan.com')->first();
        $buku = Buku::first();
        $anggota = Anggota::first() ?? Anggota::create(['nama' => 'Anggota Default', 'nomor_telepon' => '08123456789', 'alamat' => 'Alamat']);

        $pinjam = Peminjaman::create([
            'user_id' => $siswa->id,
            'anggota_id' => $anggota->id,
            'buku_id' => $buku->id,
            'tanggal_pinjam' => now()->toDateString(),
            'status' => 'menunggu',
        ]);

        // Petugas menyetujui peminjaman
        $approve = $this->actingAs($petugas)->post('/petugas/peminjaman/'.$pinjam->id.'/setujui');
        $approve->assertRedirect();
        $this->assertEquals('disetujui', $pinjam->fresh()->status);

        // Petugas memvalidasi pengembalian
        $kembali = $this->actingAs($petugas)->post('/petugas/peminjaman/'.$pinjam->id.'/kembali');
        $kembali->assertRedirect();
        $this->assertEquals('dikembalikan', $pinjam->fresh()->status);
        $this->assertNotNull($pinjam->fresh()->tanggal_kembali);
    }
}
