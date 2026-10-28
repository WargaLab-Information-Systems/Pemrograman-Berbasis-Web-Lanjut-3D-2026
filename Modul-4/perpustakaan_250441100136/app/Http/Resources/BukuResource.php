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
            // Pemetaan nama kolom DB/relasi ke atribut respons API
            'kategori' => $this->kategori->nama_kategori ?? $this->kategori_id,
            'kategori_id' => $this->kategori_id,
            'judulll' => $this->judul,
            'penulis' => $this->penulis,
            'tahun_terbit' => $this->tahun_terbit,
        ];
    }
}
