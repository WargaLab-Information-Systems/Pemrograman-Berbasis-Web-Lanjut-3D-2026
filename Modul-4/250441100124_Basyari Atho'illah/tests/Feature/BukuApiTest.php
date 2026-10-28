<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BukuApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Menguji halaman web /buku dapat diakses dengan respons HTTP 200.
     */
    public function test_halaman_buku_dapat_diakses(): void
    {
        $response = $this->get('/buku');

        $response->assertStatus(200);
        $response->assertSee('Manajemen Buku & Konsumsi RESTful API');
        $response->assertSee('GET /api/buku');
    }

    /**
     * Tugas 1: Menguji GET /api/buku mengembalikan struktur API Resource
     * dengan pemetaan atribut kategori, judul, penulis, tahun_terbit.
     */
    public function test_get_api_buku_mengembalikan_struktur_api_resource(): void
    {
        $kategori = Kategori::firstOrCreate(['nama' => 'Teknologi Informasi']);
        $buku = Buku::create([
            'kategori_id' => $kategori->id,
            'judul' => 'Pemrograman Web Lanjut dengan Laravel',
            'penulis' => 'Basyari Athoillah',
            'tahun_terbit' => 2024,
        ]);

        $response = $this->getJson('/api/buku');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'kategori',
                        'kategori_id',
                        'judul',
                        'penulis',
                        'tahun_terbit',
                    ],
                ],
            ]);

        $this->assertDatabaseHas('bukus', [
            'id' => $buku->id,
            'judul' => 'Pemrograman Web Lanjut dengan Laravel',
        ]);
    }

    /**
     * Tugas 2: Menguji POST /api/buku berhasil menambahkan data buku baru
     * dengan validasi controller ($request->validate).
     */
    public function test_post_api_buku_berhasil_menambahkan_data(): void
    {
        $kategori = Kategori::firstOrCreate(['nama' => 'Sains']);

        $payload = [
            'kategori_id' => $kategori->id,
            'judul' => 'Fisika Kuantum untuk Pemula',
            'penulis' => 'Prof. Haryanto',
            'tahun_terbit' => 2023,
        ];

        $response = $this->postJson('/api/buku', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.judul', 'Fisika Kuantum untuk Pemula')
            ->assertJsonPath('data.penulis', 'Prof. Haryanto')
            ->assertJsonPath('data.kategori', $kategori->nama);

        $this->assertDatabaseHas('bukus', [
            'judul' => 'Fisika Kuantum untuk Pemula',
            'penulis' => 'Prof. Haryanto',
        ]);
    }

    /**
     * Tugas 5: Menguji validasi $request->validate() pada controller
     * ketika payload data tidak valid menghasilkan respons HTTP 422.
     */
    public function test_post_api_buku_gagal_validasi_menghasilkan_422(): void
    {
        $payload = [
            'kategori_id' => 999999, // Kategori tidak ada di database
            'judul' => '',           // Judul kosong
            'penulis' => 'A',        // Terlalu pendek (< 3 karakter)
            'tahun_terbit' => 1800,  // Di luar range 1900 - tahun ini
        ];

        $response = $this->postJson('/api/buku', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['kategori_id', 'judul', 'penulis', 'tahun_terbit']);
    }

    /**
     * Tugas 3: Menguji PUT /api/buku/{id} berhasil memperbarui data buku
     * tanpa mengubah data buku lainnya.
     */
    public function test_put_api_buku_berhasil_memperbarui_data(): void
    {
        $kategori = Kategori::firstOrCreate(['nama' => 'Filsafat']);
        $buku = Buku::create([
            'kategori_id' => $kategori->id,
            'judul' => 'Judul Sebelum Diubah',
            'penulis' => 'Penulis Asli',
            'tahun_terbit' => 2020,
        ]);

        $bukuLain = Buku::create([
            'kategori_id' => $kategori->id,
            'judul' => 'Buku Lain Tidak Terubah',
            'penulis' => 'Penulis Lain',
            'tahun_terbit' => 2021,
        ]);

        $updatePayload = [
            'kategori_id' => $kategori->id,
            'judul' => 'Judul Setelah Diubah Sukses',
            'penulis' => 'Penulis Terkini',
            'tahun_terbit' => 2024,
        ];

        $response = $this->putJson('/api/buku/'.$buku->id, $updatePayload);

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.judul', 'Judul Setelah Diubah Sukses');

        $this->assertDatabaseHas('bukus', [
            'id' => $buku->id,
            'judul' => 'Judul Setelah Diubah Sukses',
        ]);

        // Pastikan buku lain tidak ikut berubah
        $this->assertDatabaseHas('bukus', [
            'id' => $bukuLain->id,
            'judul' => 'Buku Lain Tidak Terubah',
        ]);
    }

    /**
     * Tugas 4: Menguji DELETE /api/buku/{id} berhasil menghapus buku dari basis data.
     */
    public function test_delete_api_buku_berhasil_menghapus_data(): void
    {
        $kategori = Kategori::firstOrCreate(['nama' => 'Umum']);
        $buku = Buku::create([
            'kategori_id' => $kategori->id,
            'judul' => 'Buku Untuk Dihapus',
            'penulis' => 'Penulis Hapus',
            'tahun_terbit' => 2022,
        ]);

        $response = $this->deleteJson('/api/buku/'.$buku->id);

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success');

        $this->assertDatabaseMissing('bukus', [
            'id' => $buku->id,
        ]);
    }
}
