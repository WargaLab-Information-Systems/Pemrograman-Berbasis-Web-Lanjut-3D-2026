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

class Modul5RbacTest extends TestCase
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

        // Jalankan seeder roles & permissions
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
     * Skenario 1: Belum login membuka halaman dashboard admin -> Dialihkan ke halaman login
     */
    public function test_skenario_1_belum_login_akses_dashboard_admin_dialihkan_ke_login(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /**
     * Skenario 2: Admin membuka dashboard admin -> Dashboard admin dapat diakses
     */
    public function test_skenario_2_admin_dapat_mengakses_dashboard_admin(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Dashboard Administrator');
    }

    /**
     * Skenario 3: Admin menambah, mengubah, dan menghapus data buku -> Operasi berhasil
     */
    public function test_skenario_3_admin_mengelola_data_buku_berhasil(): void
    {
        // 1. Tambah Buku
        $storeResponse = $this->actingAs($this->admin)->post('/buku', [
            'kategori_id'  => $this->kategori->id,
            'judul'        => 'Buku Baru Laravel RBAC',
            'penulis'      => 'Senior Developer',
            'tahun_terbit' => 2024,
        ]);
        $storeResponse->assertStatus(302);
        $this->assertDatabaseHas('bukus', ['judul' => 'Buku Baru Laravel RBAC']);

        $bukuBaru = Buku::where('judul', 'Buku Baru Laravel RBAC')->first();

        // 2. Ubah Buku
        $updateResponse = $this->actingAs($this->admin)->put("/buku/{$bukuBaru->id}", [
            'kategori_id'  => $this->kategori->id,
            'judul'        => 'Buku Laravel RBAC Diperbarui',
            'penulis'      => 'Senior Developer Updated',
            'tahun_terbit' => 2025,
        ]);
        $updateResponse->assertStatus(302);
        $this->assertDatabaseHas('bukus', ['judul' => 'Buku Laravel RBAC Diperbarui']);

        // 3. Hapus Buku
        $deleteResponse = $this->actingAs($this->admin)->delete("/buku/{$bukuBaru->id}");
        $deleteResponse->assertStatus(302);
        $this->assertDatabaseMissing('bukus', ['id' => $bukuBaru->id]);
    }

    /**
     * Skenario 4: Admin mengelola akun dan role pengguna -> Operasi berhasil
     */
    public function test_skenario_4_admin_mengelola_akun_dan_role_pengguna(): void
    {
        // 1. Tambah Pengguna Baru dengan Role
        $response = $this->actingAs($this->admin)->post('/users', [
            'name'     => 'User Testing Modul 5',
            'email'    => 'usertest_modul5@test.com',
            'password' => 'password123',
            'role'     => 'petugas',
        ]);
        $response->assertStatus(302);

        $userBaru = User::where('email', 'usertest_modul5@test.com')->first();
        $this->assertNotNull($userBaru);
        $this->assertTrue($userBaru->hasRole('petugas'));

        // 2. Ubah Role Pengguna ke 'siswa'
        $updateRoleResponse = $this->actingAs($this->admin)->put("/users/{$userBaru->id}", [
            'name'  => 'User Testing Modul 5 Updated',
            'email' => 'usertest_modul5@test.com',
            'role'  => 'siswa',
        ]);
        $updateRoleResponse->assertStatus(302);

        $userBaru->refresh();
        $this->assertTrue($userBaru->hasRole('siswa'));

        // Cleanup
        $userBaru->delete();
    }

    /**
     * Skenario 5: Admin mengakses data peminjaman, pengembalian, dan laporan -> Data dapat diakses
     */
    public function test_skenario_5_admin_mengakses_peminjaman_dan_laporan(): void
    {
        $responsePeminjaman = $this->actingAs($this->admin)->get('/peminjaman');
        $responsePeminjaman->assertStatus(200);

        $responseLaporan = $this->actingAs($this->admin)->get('/laporan');
        $responseLaporan->assertStatus(200);
        $responseLaporan->assertSee('Rekapitulasi Perpustakaan');
    }

    /**
     * Skenario 6: Petugas membuka dashboard petugas -> Dashboard petugas dapat diakses
     */
    public function test_skenario_6_petugas_membuka_dashboard_petugas(): void
    {
        $response = $this->actingAs($this->petugas)->get('/petugas/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Dashboard Petugas');
    }

    /**
     * Skenario 7: Petugas mengelola data buku -> Operasi berhasil
     */
    public function test_skenario_7_petugas_mengelola_data_buku(): void
    {
        $response = $this->actingAs($this->petugas)->post('/buku', [
            'kategori_id'  => $this->kategori->id,
            'judul'        => 'Buku Input Petugas',
            'penulis'      => 'Petugas Perpus',
            'tahun_terbit' => 2024,
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('bukus', ['judul' => 'Buku Input Petugas']);

        $buku = Buku::where('judul', 'Buku Input Petugas')->first();
        $buku->delete();
    }

    /**
     * Skenario 8: Petugas memproses peminjaman dan pengembalian -> Operasi berhasil
     */
    public function test_skenario_8_petugas_memproses_peminjaman_dan_pengembalian(): void
    {
        $anggota = Anggota::firstOrCreate(
            ['email' => $this->siswa->email],
            ['user_id' => $this->siswa->id, 'nama' => $this->siswa->name, 'no_telepon' => '08123']
        );

        $peminjaman = Peminjaman::create([
            'user_id'        => $this->siswa->id,
            'anggota_id'     => $anggota->id,
            'buku_id'        => $this->buku->id,
            'tanggal_pinjam' => now()->toDateString(),
            'status'         => 'menunggu',
        ]);

        // 1. Setujui Peminjaman
        $approveResponse = $this->actingAs($this->petugas)->post("/peminjaman/{$peminjaman->id}/setujui");
        $approveResponse->assertStatus(302);
        $peminjaman->refresh();
        $this->assertEquals('disetujui', $peminjaman->status);

        // 2. Catat Pengembalian Buku
        $returnResponse = $this->actingAs($this->petugas)->post("/peminjaman/{$peminjaman->id}/kembalikan");
        $returnResponse->assertStatus(302);
        $peminjaman->refresh();
        $this->assertEquals('dikembalikan', $peminjaman->status);
        $this->assertNotNull($peminjaman->tanggal_kembali);

        $peminjaman->delete();
    }

    /**
     * Skenario 9: Petugas membuka halaman pengelolaan akun atau role pengguna -> Akses ditolak (403)
     */
    public function test_skenario_9_petugas_akses_halaman_pengelolaan_akun_ditolak(): void
    {
        $response = $this->actingAs($this->petugas)->get('/users');

        $response->assertStatus(403);
    }

    /**
     * Skenario 10: Siswa melihat daftar dan detail buku -> Data buku dapat dilihat
     */
    public function test_skenario_10_siswa_melihat_daftar_dan_detail_buku(): void
    {
        // Lihat Daftar Buku
        $indexResponse = $this->actingAs($this->siswa)->get('/buku');
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee($this->buku->judul);

        // Lihat Detail Buku
        $showResponse = $this->actingAs($this->siswa)->get("/buku/{$this->buku->id}");
        $showResponse->assertStatus(200);
        $showResponse->assertSee($this->buku->judul);
    }

    /**
     * Skenario 11: Siswa melihat dan mengubah profil sendiri -> Data profil milik sendiri dapat diakses dan diperbarui
     */
    public function test_skenario_11_siswa_melihat_dan_mengubah_profil_sendiri(): void
    {
        // Lihat Profil Sendiri
        $responseEdit = $this->actingAs($this->siswa)->get('/profile');
        $responseEdit->assertStatus(200);
        $responseEdit->assertSee($this->siswa->email);

        // Ubah Profil Sendiri
        $responseUpdate = $this->actingAs($this->siswa)->patch('/profile', [
            'name'  => 'Siswa Budi Updated',
            'email' => $this->siswa->email,
        ]);
        $responseUpdate->assertStatus(302);
        $responseUpdate->assertSessionHas('status', 'profile-updated');

        $this->siswa->refresh();
        $this->assertEquals('Siswa Budi Updated', $this->siswa->name);
    }

    /**
     * Skenario 12: Siswa melihat riwayat peminjaman sendiri -> Riwayat milik sendiri dapat dilihat
     */
    public function test_skenario_12_siswa_melihat_riwayat_peminjaman_sendiri(): void
    {
        $anggota = Anggota::where('user_id', $this->siswa->id)->first();
        $peminjaman = Peminjaman::create([
            'user_id'        => $this->siswa->id,
            'anggota_id'     => $anggota ? $anggota->id : 1,
            'buku_id'        => $this->buku->id,
            'tanggal_pinjam' => now()->toDateString(),
            'status'         => 'disetujui',
        ]);

        $response = $this->actingAs($this->siswa)->get('/riwayat-peminjaman');
        $response->assertStatus(200);
        $response->assertSee($this->buku->judul);

        // Detail peminjaman milik sendiri
        $responseDetail = $this->actingAs($this->siswa)->get("/peminjaman/{$peminjaman->id}");
        $responseDetail->assertStatus(200);

        $peminjaman->delete();
    }

    /**
     * Skenario 13: Siswa membuka dashboard admin atau halaman pengelolaan buku -> Akses ditolak (403)
     */
    public function test_skenario_13_siswa_akses_dashboard_admin_atau_kelola_buku_ditolak(): void
    {
        // Akses Dashboard Admin
        $responseAdmin = $this->actingAs($this->siswa)->get('/admin/dashboard');
        $responseAdmin->assertStatus(403);

        // Akses Kelola Buku
        $responseKelolaBuku = $this->actingAs($this->siswa)->get('/kelola-buku');
        $responseKelolaBuku->assertStatus(403);
    }

    /**
     * Skenario 14: Siswa mencoba melihat riwayat peminjaman siswa lain -> Akses ditolak (403)
     */
    public function test_skenario_14_siswa_melihat_riwayat_peminjaman_siswa_lain_ditolak(): void
    {
        $anggota2 = Anggota::where('user_id', $this->siswa2->id)->first();

        // Buat peminjaman milik Siswa 2 (Siti)
        $peminjamanSiswa2 = Peminjaman::create([
            'user_id'        => $this->siswa2->id,
            'anggota_id'     => $anggota2 ? $anggota2->id : 2,
            'buku_id'        => $this->buku->id,
            'tanggal_pinjam' => now()->toDateString(),
            'status'         => 'disetujui',
        ]);

        // Siswa 1 (Budi) mencoba mengakses data peminjaman milik Siswa 2
        $response = $this->actingAs($this->siswa)->get("/peminjaman/{$peminjamanSiswa2->id}");

        $response->assertStatus(403);

        $peminjamanSiswa2->delete();
    }

    /**
     * Skenario 15: Admin/Petugas/Siswa melakukan logout lalu membuka halaman yang dilindungi -> Pengguna harus login kembali
     */
    public function test_skenario_15_logout_lalu_akses_halaman_dilindungi_harus_login(): void
    {
        // 1. Login sebagai Siswa
        $this->actingAs($this->siswa);

        // 2. Lakukan Logout
        $logoutResponse = $this->post('/logout');
        $logoutResponse->assertStatus(302);
        $this->assertGuest();

        // 3. Akses kembali halaman yang dilindungi
        $protectedResponse = $this->get('/riwayat-peminjaman');
        $protectedResponse->assertStatus(302);
        $protectedResponse->assertRedirect('/login');
    }
}
