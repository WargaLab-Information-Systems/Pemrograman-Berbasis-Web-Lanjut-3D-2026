<?php

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
});

// Skenario 1: Belum login membuka halaman dashboard admin -> Dialihkan ke halaman login
test('skenario 1: belum login membuka dashboard admin dialihkan ke login', function () {
    $response = $this->get('/admin/dashboard');
    $response->assertRedirect('/login');
});

// Skenario 2: Admin membuka dashboard admin -> Dashboard admin dapat diakses
test('skenario 2: admin membuka dashboard admin dapat diakses', function () {
    $admin = User::where('email', 'admin@perpustakaan.com')->first();
    $response = $this->actingAs($admin)->get('/admin/dashboard');
    $response->assertOk();
    $response->assertSee('Dashboard Admin');
});

// Skenario 3: Admin menambah, mengubah, dan menghapus data buku -> Operasi berhasil
test('skenario 3: admin menambah, mengubah, dan menghapus data buku berhasil', function () {
    $admin = User::where('email', 'admin@perpustakaan.com')->first();
    $kategori = Kategori::firstOrCreate(['nama' => 'Teknologi']);

    // Tambah buku
    $responseStore = $this->actingAs($admin)->postJson('/api/buku', [
        'kategori_id' => $kategori->id,
        'judul' => 'Buku Uji Admin',
        'penulis' => 'Penulis Uji',
        'tahun_terbit' => 2024,
    ]);
    $responseStore->assertStatus(201);
    $bukuId = $responseStore->json('data.id');

    // Ubah buku
    $responseUpdate = $this->actingAs($admin)->putJson("/api/buku/{$bukuId}", [
        'kategori_id' => $kategori->id,
        'judul' => 'Buku Uji Admin Diperbarui',
        'penulis' => 'Penulis Uji Baru',
        'tahun_terbit' => 2024,
    ]);
    $responseUpdate->assertOk();

    // Hapus buku
    $responseDelete = $this->actingAs($admin)->deleteJson("/api/buku/{$bukuId}");
    $responseDelete->assertOk();
});

// Skenario 4: Admin mengelola akun dan role pengguna -> Operasi berhasil
test('skenario 4: admin mengelola akun dan role pengguna berhasil', function () {
    $admin = User::where('email', 'admin@perpustakaan.com')->first();

    // Membuka halaman kelola pengguna
    $responseIndex = $this->actingAs($admin)->get('/admin/users');
    $responseIndex->assertOk();

    // Tambah pengguna dengan role petugas
    $responseStore = $this->actingAs($admin)->post('/admin/users', [
        'name' => 'Akun Baru Test',
        'email' => 'akunbaru@perpustakaan.com',
        'password' => 'password123',
        'role' => 'petugas',
    ]);
    $responseStore->assertRedirect(route('admin.users.index'));

    $newUser = User::where('email', 'akunbaru@perpustakaan.com')->first();
    expect($newUser)->not->toBeNull();
    expect($newUser->hasRole('petugas'))->toBeTrue();

    // Ubah role pengguna menjadi siswa
    $responseUpdate = $this->actingAs($admin)->put("/admin/users/{$newUser->id}", [
        'name' => 'Akun Baru Diubah',
        'email' => 'akunbaru@perpustakaan.com',
        'role' => 'siswa',
    ]);
    $responseUpdate->assertRedirect(route('admin.users.index'));
    expect($newUser->fresh()->hasRole('siswa'))->toBeTrue();

    // Hapus pengguna
    $responseDelete = $this->actingAs($admin)->delete("/admin/users/{$newUser->id}");
    $responseDelete->assertRedirect(route('admin.users.index'));
    expect(User::find($newUser->id))->toBeNull();
});

// Skenario 5: Admin mengakses data peminjaman, pengembalian, dan laporan -> Data dapat diakses sesuai hak akses
test('skenario 5: admin mengakses data peminjaman pengembalian dan laporan berhasil', function () {
    $admin = User::where('email', 'admin@perpustakaan.com')->first();

    $responsePeminjaman = $this->actingAs($admin)->get('/peminjaman');
    $responsePeminjaman->assertOk();

    $responseLaporan = $this->actingAs($admin)->get('/laporan');
    $responseLaporan->assertOk();
});

// Skenario 6: Petugas membuka dashboard petugas -> Dashboard petugas dapat diakses
test('skenario 6: petugas membuka dashboard petugas dapat diakses', function () {
    $petugas = User::where('email', 'petugas@perpustakaan.com')->first();
    $response = $this->actingAs($petugas)->get('/petugas/dashboard');
    $response->assertOk();
    $response->assertSee('Dashboard Petugas');
});

// Skenario 7: Petugas mengelola data buku -> Operasi berhasil
test('skenario 7: petugas mengelola data buku berhasil', function () {
    $petugas = User::where('email', 'petugas@perpustakaan.com')->first();
    $kategori = Kategori::firstOrCreate(['nama' => 'Teknologi']);

    $responseStore = $this->actingAs($petugas)->postJson('/api/buku', [
        'kategori_id' => $kategori->id,
        'judul' => 'Buku Petugas',
        'penulis' => 'Penulis',
        'tahun_terbit' => 2024,
    ]);
    $responseStore->assertStatus(201);
    $bukuId = $responseStore->json('data.id');

    $responseDelete = $this->actingAs($petugas)->deleteJson("/api/buku/{$bukuId}");
    $responseDelete->assertOk();
});

// Skenario 8: Petugas memproses peminjaman dan pengembalian -> Operasi berhasil
test('skenario 8: petugas memproses peminjaman dan pengembalian berhasil', function () {
    $petugas = User::where('email', 'petugas@perpustakaan.com')->first();
    $siswa = User::where('email', 'siswa@perpustakaan.com')->first();
    $anggota = Anggota::where('email', $siswa->email)->first();
    $kategori = Kategori::firstOrCreate(['nama' => 'Sains']);
    $buku = Buku::firstOrCreate(
        ['judul' => 'Buku Sirkulasi Petugas'],
        ['kategori_id' => $kategori->id, 'penulis' => 'Penulis', 'tahun_terbit' => 2023]
    );

    $peminjaman = Peminjaman::create([
        'anggota_id' => $anggota->id,
        'buku_id' => $buku->id,
        'status' => 'diajukan',
    ]);

    // Petugas menyetujui peminjaman
    $responseSetujui = $this->actingAs($petugas)->patch("/peminjaman/{$peminjaman->id}/setujui");
    $responseSetujui->assertSessionHas('success');
    expect($peminjaman->fresh()->status)->toBe('dipinjam');

    // Siswa ajukan pengembalian
    $this->actingAs($siswa)->post("/peminjaman-saya/kembalikan/{$peminjaman->id}");
    expect($peminjaman->fresh()->status)->toBe('menunggu_pengembalian');

    // Petugas konfirmasi pengembalian
    $responseKonfirmasi = $this->actingAs($petugas)->patch("/peminjaman/{$peminjaman->id}/konfirmasi");
    $responseKonfirmasi->assertSessionHas('success');
    expect($peminjaman->fresh()->status)->toBe('dikembalikan');
});

// Skenario 9: Petugas membuka halaman pengelolaan akun atau role pengguna -> Akses ditolak (403)
test('skenario 9: petugas membuka halaman kelola akun role pengguna akses ditolak', function () {
    $petugas = User::where('email', 'petugas@perpustakaan.com')->first();
    $response = $this->actingAs($petugas)->get('/admin/users');
    $response->assertStatus(403);
});

// Skenario 10: Siswa melihat daftar dan detail buku -> Data buku dapat dilihat
test('skenario 10: siswa melihat daftar dan detail buku dapat dilihat', function () {
    $siswa = User::where('email', 'siswa@perpustakaan.com')->first();
    $kategori = Kategori::firstOrCreate(['nama' => 'Sastra']);
    $buku = Buku::firstOrCreate(
        ['judul' => 'Buku Baca Siswa'],
        ['kategori_id' => $kategori->id, 'penulis' => 'Penulis', 'tahun_terbit' => 2022]
    );

    $responseIndex = $this->actingAs($siswa)->get('/buku');
    $responseIndex->assertOk();

    $responseShow = $this->actingAs($siswa)->get("/buku/{$buku->id}");
    $responseShow->assertOk();
    $responseShow->assertSee($buku->judul);
});

// Skenario 11: Siswa melihat dan mengubah profil sendiri -> Data profil milik sendiri dapat diakses dan diperbarui
test('skenario 11: siswa melihat dan mengubah profil sendiri berhasil', function () {
    $siswa = User::where('email', 'siswa@perpustakaan.com')->first();

    $responseEdit = $this->actingAs($siswa)->get('/profile');
    $responseEdit->assertOk();

    $responseUpdate = $this->actingAs($siswa)->patch('/profile', [
        'name' => 'Siswa Diperbarui',
        'email' => 'siswa@perpustakaan.com',
    ]);
    $responseUpdate->assertSessionHas('status', 'profile-updated');
    expect($siswa->fresh()->name)->toBe('Siswa Diperbarui');
});

// Skenario 12: Siswa melihat riwayat peminjaman sendiri -> Riwayat milik sendiri dapat dilihat
test('skenario 12: siswa melihat riwayat peminjaman sendiri berhasil', function () {
    $siswa = User::where('email', 'siswa@perpustakaan.com')->first();
    $response = $this->actingAs($siswa)->get('/peminjaman-saya');
    $response->assertOk();
});

// Skenario 13: Siswa membuka dashboard admin atau halaman pengelolaan buku -> Akses ditolak (403)
test('skenario 13: siswa membuka dashboard admin atau kelola buku akses ditolak', function () {
    $siswa = User::where('email', 'siswa@perpustakaan.com')->first();

    // Coba buka dashboard admin
    $responseDashboard = $this->actingAs($siswa)->get('/admin/dashboard');
    $responseDashboard->assertStatus(403);

    // Coba tambah buku
    $kategori = Kategori::firstOrCreate(['nama' => 'Umum']);
    $responseAddBuku = $this->actingAs($siswa)->postJson('/api/buku', [
        'kategori_id' => $kategori->id,
        'judul' => 'Buku Ilegal',
        'penulis' => 'Penulis',
        'tahun_terbit' => 2024,
    ]);
    $responseAddBuku->assertStatus(403);
});

// Skenario 14: Siswa mencoba melihat riwayat peminjaman siswa lain -> Akses ditolak (403)
test('skenario 14: siswa mencoba melihat riwayat peminjaman siswa lain akses ditolak', function () {
    $siswa1 = User::where('email', 'siswa@perpustakaan.com')->first();
    $siswa2 = User::where('email', 'siswa2@perpustakaan.com')->first();

    $anggota2 = Anggota::where('email', $siswa2->email)->first();
    $kategori = Kategori::firstOrCreate(['nama' => 'Fiksi']);
    $buku = Buku::firstOrCreate(
        ['judul' => 'Buku Siswa 2'],
        ['kategori_id' => $kategori->id, 'penulis' => 'Penulis', 'tahun_terbit' => 2021]
    );

    $peminjamanSiswa2 = Peminjaman::create([
        'anggota_id' => $anggota2->id,
        'buku_id' => $buku->id,
        'status' => 'dipinjam',
    ]);

    // Siswa 1 mencoba akses detail peminjaman milik Siswa 2 via Web
    $responseWeb = $this->actingAs($siswa1)->get("/peminjaman/{$peminjamanSiswa2->id}");
    $responseWeb->assertStatus(403);

    // Siswa 1 mencoba akses detail peminjaman milik Siswa 2 via API
    $responseApi = $this->actingAs($siswa1)->getJson("/api/peminjaman/{$peminjamanSiswa2->id}");
    $responseApi->assertStatus(403);
});

// Skenario 15: Admin/Petugas/Siswa melakukan logout lalu membuka halaman yang dilindungi -> Pengguna harus login kembali
test('skenario 15: logout lalu membuka halaman dilindungi harus login kembali', function () {
    $siswa = User::where('email', 'siswa@perpustakaan.com')->first();

    $this->actingAs($siswa)->post('/logout');

    $response = $this->get('/buku');
    $response->assertRedirect('/login');

    $response2 = $this->get('/dashboard');
    $response2->assertRedirect('/login');
});
