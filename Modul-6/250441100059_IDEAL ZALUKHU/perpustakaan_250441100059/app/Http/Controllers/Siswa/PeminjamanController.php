<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PeminjamanController extends Controller
{
    public function index(Request $request): View
    {
        $anggota = $request->user()->anggota()->firstOrCreate([], [
            'nama' => $request->user()->name,
            'email' => $request->user()->email,
            'no_telepon' => '-',
        ]);

        $peminjamans = Peminjaman::with('buku')
            ->where('anggota_id', $anggota->id)
            ->latest()
            ->get();

        return view('siswa.peminjaman.index', compact('peminjamans'));
    }
}
