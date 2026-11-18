<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateBukuTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_endpoint_persists_the_submitted_book_data(): void
    {
        $kategori = Kategori::create(['nama' => 'Fiksi']);
        $buku = Buku::create([
            'kategori_id' => $kategori->id,
            'judul' => 'Judul Lama',
            'penulis' => 'Penulis Lama',
            'tahun_terbit' => 2020,
        ]);

        $response = $this->putJson("/api/buku/{$buku->id}", [
            'kategori_id' => $kategori->id,
            'judul' => 'Judul Baru',
            'penulis' => 'Penulis Baru',
            'tahun_terbit' => 2024,
        ]);

        $response->assertOk()
            ->assertJsonPath('data.judul', 'Judul Baru');

        $this->assertDatabaseHas('bukus', [
            'id' => $buku->id,
            'judul' => 'Judul Baru',
            'penulis' => 'Penulis Baru',
            'tahun_terbit' => 2024,
        ]);
    }
}
