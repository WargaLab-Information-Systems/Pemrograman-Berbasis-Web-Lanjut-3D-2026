<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BukuResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'kategori_id'  => $this->kategori_id,
            'nama_kategori'=> $this->kategori->nama ?? $this->kategori->nama_kategori ?? 'Tanpa Kategori',
            'judul'        => $this->judul,
            'penulis'      => $this->penulis,
            'tahun_terbit' => $this->tahun_terbit,
        ];
    }
}