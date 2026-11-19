<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class BukuResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'kategori_id' => $this->kategori_id,
            'kategori'    => $this->whenLoaded('kategori', fn () => $this->kategori->nama),
            'judul'       => $this->judul,
            'penulis'     => $this->penulis,
            'tahun'       => $this->tahun_terbit,

            'jumlah_peminjaman' => $this->whenCounted('peminjamans'),

            'peminjaman' => $this->whenLoaded('peminjamans', fn () => $this->peminjamans->map(fn ($p) => [
                'id'              => $p->id,
                'anggota_id'      => $p->anggota_id,
                'tanggal_pinjam'  => $p->tanggal_pinjam,
                'tanggal_kembali' => $p->tanggal_kembali,
            ])),
        ];
    }
}
