<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PeminjamanController extends Controller
{
    /**
     * Menampilkan detail data peminjaman buku.
     * 
     * SOAL 4:
     * Menutup celah A4 (IDOR) menggunakan Policy (PeminjamanPolicy).
     * Memanggil Gate::authorize('view', $peminjaman) sebelum menampilkan view.
     * - Admin & Petugas -> 200 OK (Boleh melihat peminjaman siapa pun)
     * - Siswa Pemilik -> 200 OK (Boleh melihat peminjaman miliknya sendiri)
     * - Siswa Lain -> 403 Forbidden (Ditolak karena bukan miliknya)
     */
    public function show($id)
    {
        $peminjaman = Peminjaman::with(['anggota', 'buku'])->findOrFail($id);

        // SOAL 4: Otorisasi berbasis Policy
        Gate::authorize('view', $peminjaman);

        return view('peminjaman.show', compact('peminjaman'));
    }
}
