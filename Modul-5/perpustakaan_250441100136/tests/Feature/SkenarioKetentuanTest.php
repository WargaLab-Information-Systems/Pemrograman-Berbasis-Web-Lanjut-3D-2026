<?php

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    // Seed role dan permission sudah dipanggil di TestCase setUp
});

test('1. Pengguna belum login dialihkan ke halaman login saat membuka dashboard admin', function () {
    $response = $this->get('/admin/dashboard');

    $response->assertRedirect(route('login'));
});

test('2. Admin dapat membuka dashboard admin', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $response = $this->actingAs($admin)->get('/admin/dashboard');

    $response->assertStatus(200);
});

test('3. Admin dapat mengelola data buku via API', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $response = $this->actingAs($admin)->postJson('/api/buku', [
        'kategori' => 1,
        'judul' => 'Buku Pemrograman Web',
        'penulis' => 'Penulis Hebat',
        'tahun_terbit' => 2024,
    ]);

    $response->assertStatus(201);
});

test('4. Admin dapat mengelola akun dan role pengguna', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $targetUser = User::factory()->create();
    $targetUser->assignRole('siswa');

    $response = $this->actingAs($admin)->put(route('users.update', $targetUser->id), [
        'role' => 'petugas',
    ]);

    $response->assertRedirect(route('users.index'));
    expect($targetUser->fresh()->hasRole('petugas'))->toBeTrue();
});

test('5. Admin dapat mengakses data peminjaman dan laporan', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $responsePeminjaman = $this->actingAs($admin)->get(route('peminjaman.index'));
    $responsePeminjaman->assertStatus(200);

    $responseLaporan = $this->actingAs($admin)->get(route('laporan.index'));
    $responseLaporan->assertStatus(200);
});

test('6. Petugas dapat membuka dashboard petugas', function () {
    $petugas = User::factory()->create();
    $petugas->assignRole('petugas');

    $response = $this->actingAs($petugas)->get(route('petugas.dashboard'));

    $response->assertStatus(200);
});

test('7. Petugas dapat mengelola data buku', function () {
    $petugas = User::factory()->create();
    $petugas->assignRole('petugas');

    $response = $this->actingAs($petugas)->postJson('/api/buku', [
        'kategori' => 1,
        'judul' => 'Buku Petugas',
        'penulis' => 'Petugas Perpustakaan',
        'tahun_terbit' => 2024,
    ]);

    $response->assertStatus(201);
});

test('8. Petugas dapat memproses peminjaman dan pengembalian', function () {
    $petugas = User::factory()->create();
    $petugas->assignRole('petugas');

    $anggota = Anggota::create([
        'nama' => 'Peminjam Test',
        'email' => 'peminjam_test@mail.com',
        'no_telepon' => '081234567890',
    ]);

    $buku = Buku::firstOrCreate(
        ['judul' => 'Buku Sirkulasi'],
        ['kategori_id' => 1, 'penulis' => 'Penulis Sirkulasi', 'tahun_terbit' => 2024]
    );

    $peminjaman = Peminjaman::create([
        'anggota_id' => $anggota->id,
        'buku_id' => $buku->id,
        'tanggal_pinjam' => now()->toDateString(),
    ]);

    $response = $this->actingAs($petugas)->post(route('peminjaman.proses', $peminjaman->id));

    $response->assertStatus(302);
    expect($peminjaman->fresh()->tanggal_kembali)->not->toBeNull();
});

test('9. Petugas ditolak saat membuka halaman kelola akun atau role pengguna', function () {
    $petugas = User::factory()->create();
    $petugas->assignRole('petugas');

    $response = $this->actingAs($petugas)->get(route('users.index'));

    $response->assertStatus(403);
});

test('10. Siswa dapat melihat daftar buku dan mengajukan peminjaman buku', function () {
    $siswa = User::factory()->create();
    $siswa->assignRole('siswa');

    $buku = Buku::firstOrCreate(
        ['judul' => 'Buku Belajar Laravel'],
        ['kategori_id' => 1, 'penulis' => 'Penulis Asli', 'tahun_terbit' => 2024]
    );

    $responseView = $this->actingAs($siswa)->get(route('buku.index'));
    $responseView->assertStatus(200);

    $responsePinjam = $this->actingAs($siswa)->post(route('peminjaman.ajukan'), [
        'buku_id' => $buku->id,
    ]);

    $responsePinjam->assertRedirect(route('peminjaman.riwayat'));
    expect(Peminjaman::where('buku_id', $buku->id)->exists())->toBeTrue();
});

test('11. Siswa dapat melihat dan mengubah profil sendiri', function () {
    $siswa = User::factory()->create();
    $siswa->assignRole('siswa');

    $viewResponse = $this->actingAs($siswa)->get(route('profile.edit'));
    $viewResponse->assertStatus(200);

    $updateResponse = $this->actingAs($siswa)->patch(route('profile.update'), [
        'name' => 'Nama Baru Siswa',
        'email' => $siswa->email,
    ]);
    $updateResponse->assertRedirect(route('profile.edit'));
    expect($siswa->fresh()->name)->toBe('Nama Baru Siswa');
});

test('12. Siswa dapat melihat riwayat peminjaman sendiri', function () {
    $siswa = User::factory()->create();
    $siswa->assignRole('siswa');

    $response = $this->actingAs($siswa)->get(route('peminjaman.riwayat'));

    $response->assertStatus(200);
});

test('13. Siswa ditolak saat membuka dashboard admin, kelola pengguna, atau laporan', function () {
    $siswa = User::factory()->create();
    $siswa->assignRole('siswa');

    $adminDashboard = $this->actingAs($siswa)->get(route('admin.dashboard'));
    $adminDashboard->assertStatus(403);

    $usersManagement = $this->actingAs($siswa)->get(route('users.index'));
    $usersManagement->assertStatus(403);

    $laporan = $this->actingAs($siswa)->get(route('laporan.index'));
    $laporan->assertStatus(403);

    $createBuku = $this->actingAs($siswa)->postJson('/api/buku', [
        'kategori' => 1,
        'judul' => 'Buku Siswa Terlarang',
        'penulis' => 'Siswa',
        'tahun_terbit' => 2024,
    ]);
    $createBuku->assertStatus(403);
});

test('14. Siswa mengakses riwayat miliknya sendiri dan tidak menerima parameter riwayat orang lain', function () {
    $siswa1 = User::factory()->create();
    $siswa1->assignRole('siswa');

    $response = $this->actingAs($siswa1)->get(route('peminjaman.riwayat'));
    $response->assertStatus(200);
});

test('15. Pengguna logout lalu membuka halaman terlindungi harus login kembali', function () {
    $user = User::factory()->create();
    $user->assignRole('siswa');

    $this->actingAs($user)->post(route('logout'));

    $response = $this->get(route('buku.index'));
    $response->assertRedirect(route('login'));
});
