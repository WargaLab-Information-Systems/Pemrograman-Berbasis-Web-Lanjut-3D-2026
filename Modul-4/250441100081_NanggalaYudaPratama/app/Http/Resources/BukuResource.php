<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BukuResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id'          => $this->id,
            'kategori_id' => $this->kategori_id,
            'kategori'    => $this->kategori?->nama ?? '-',
            'judul'       => $this->judul,
            'penulis'     => $this->penulis,
            'tahun'       => $this->tahun_terbit,
            'created_at'  => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
