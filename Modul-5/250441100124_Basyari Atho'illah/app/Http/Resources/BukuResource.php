<?php

namespace App\Http\Resources;

use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class BukuResource
 *
 * Mengatur representasi data buku untuk respons API.
 * Sesuai ketentuan praktikum Modul IV:
 * Nama atribut pada respons API Resource dapat berbeda dari nama kolom di database:
 * - Kolom 'kategori_id' & relasi 'kategoriRelasi->nama' dipetakan menjadi atribut respons 'kategori'
 * - Kolom database 'judul' dipetakan menjadi atribut respons 'judul'
 * - Kolom database 'penulis' dipetakan menjadi atribut respons 'penulis'
 * - Kolom database 'tahun_terbit' dipetakan menjadi atribut respons 'tahun_terbit' (dan alias 'tahun')
 *
 * @mixin Buku
 */
class BukuResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            // Pemetaan relasi tabel kategoris (kategoriRelasi->nama) ke atribut 'kategori'
            'kategori' => $this->kategoriRelasi?->nama ?? 'Tanpa Kategori',
            'kategori_id' => $this->kategori_id,
            // Pemetaan kolom database 'judul' ke atribut 'judul'
            'judul' => $this->judul,
            // Pemetaan kolom database 'penulis' ke atribut 'penulis'
            'penulis' => $this->penulis,
            // Pemetaan kolom database 'tahun_terbit' ke atribut 'tahun_terbit' & 'tahun'
            'tahun_terbit' => (int) $this->tahun_terbit,
            'tahun' => (int) $this->tahun_terbit,
            // Hitungan peminjaman jika dimuat
            'total_peminjaman' => $this->peminjamans_count ?? 0,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
