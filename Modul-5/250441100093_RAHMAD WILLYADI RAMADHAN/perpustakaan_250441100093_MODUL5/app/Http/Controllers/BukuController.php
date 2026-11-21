<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BukuController extends Controller
{
    public function index()
    {
        $buku = Buku::select('id', 'judul', 'penulis', 'tahun_terbit as tahun', 'kategori_id as kategori')
                    ->get()
                    ->toArray();

        return view('buku.index', compact('buku'));
    }

    public function show($id)
    {
        $buku = Buku::select('id', 'judul', 'penulis', 'tahun_terbit as tahun', 'kategori_id as kategori')
                    ->find($id);
                    
        $detailBuku = $buku ? $buku->toArray() : null;

        return view('buku.detail', compact('detailBuku'));
    }

    public function apiBuku()
    {
        try {
            $pengguna = User::find(Auth::id());
            $apakahSiswa = $pengguna && $pengguna->hasRole('siswa');
            $idPengguna = $pengguna ? $pengguna->id : null;

            $bukuDipinjamSiswaLain = [];

            if ($apakahSiswa) {
                $bukuDipinjamSiswaLain = Peminjaman::whereIn('status', ['Menunggu Persetujuan', 'Disetujui'])
                    ->where('anggota_id', '!=', $idPengguna)
                    ->pluck('buku_id')
                    ->toArray();
            }

            $semuaBuku = Buku::all();

            $bukuTersedia = $semuaBuku->filter(function ($buku) use ($apakahSiswa, $bukuDipinjamSiswaLain) {
                if ($apakahSiswa && in_array($buku->id, $bukuDipinjamSiswaLain)) {
                    return false;
                }
                return true;
            });

            $hasilAkhir = $bukuTersedia->map(function ($buku) {
                $namaKategori = '-';

                try {
                    $kategori = DB::table('kategoris')->where('id', $buku->kategori_id)->first();
                    if (!$kategori) {
                        $kategori = DB::table('kategori')->where('id', $buku->kategori_id)->first();
                    }
                    if ($kategori) {
                        $namaKategori = $kategori->nama_kategori ?? $kategori->nama ?? $kategori->kategori ?? '-';
                    }
                } catch (\Exception $e) {
                }

                return [
                    'id' => $buku->id,
                    'kategori_id' => $buku->kategori_id,
                    'kategori' => $namaKategori,
                    'judul_buku' => $buku->judul_buku ?? $buku->judul ?? '-',
                    'nama_penulis' => $buku->nama_penulis ?? $buku->penulis ?? '-',
                    'tahun' => $buku->tahun_terbit ?? $buku->tahun ?? '-',
                ];
            })->values();

            return response()->json(['data' => $hasilAkhir]);
        } catch (\Exception $e) {
            return response()->json(['data' => [], 'error' => $e->getMessage()]);
        }
    }

    public function apiKategori()
    {
        try {
            $kategoris = [];

            if (Schema::hasTable('kategoris')) {
                $kategoris = DB::table('kategoris')->get();
            } elseif (Schema::hasTable('kategori')) {
                $kategoris = DB::table('kategori')->get();
            }

            return response()->json(['data' => $kategoris]);
        } catch (\Exception $e) {
            return response()->json(['data' => []]);
        }
    }
}