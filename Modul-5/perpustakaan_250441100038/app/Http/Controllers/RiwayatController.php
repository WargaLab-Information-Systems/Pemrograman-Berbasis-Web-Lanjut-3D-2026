<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RiwayatController extends Controller
{
    /** Riwayat milik user yang sedang login. */
    public function index(Request $request)
    {
        return $this->tampilkan($request->user());
    }

    /**
     * Riwayat milik user tertentu. Gate "lihat-riwayat" memeriksa IDENTITAS:
     * siswa hanya boleh id miliknya sendiri; admin/petugas boleh semua.
     * Selain itu -> 403.
     */
    public function show(User $user)
    {
        Gate::authorize('lihat-riwayat', $user);

        return $this->tampilkan($user);
    }

    private function tampilkan(User $pemilik)
    {
        $peminjamans = Peminjaman::with('buku')
            ->where('user_id', $pemilik->id) // filter berdasarkan pemilik
            ->latest()->get();

        return view('riwayat.index', compact('pemilik', 'peminjamans'));
    }
}
