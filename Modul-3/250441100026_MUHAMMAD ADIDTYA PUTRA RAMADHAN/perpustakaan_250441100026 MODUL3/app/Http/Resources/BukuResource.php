<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BukuResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            // Memetakan kolom DB 'judul' menjadi atribut API 'judul_buku'
            'judul_buku' => $this->judul, 
            // Memetakan kolom DB 'penulis' menjadi atribut API 'penulis_buku'
            'penulis_buku' => $this->penulis,
            // Memetakan kolom DB 'tahun_terbit' menjadi atribut API 'tahun_publikasi'
            'tahun_publikasi' => $this->tahun_terbit,
            // Mengambil relasi kategori langsung menjadi nama, bukan sekadar angka ID
            'kategori' => $this->kategori->nama ?? 'Tanpa Kategori',
            'kategori_id' => $this->kategori_id // Tetap dikirim untuk kebutuhan form edit
        ];
    }
}