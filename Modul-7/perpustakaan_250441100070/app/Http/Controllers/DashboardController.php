<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tujuan setelah login/registrasi: arahkan sesuai role.
     */
    public function arahkan(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->can('akses dashboard admin')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->can('akses dashboard petugas')) {
            return redirect()->route('petugas.dashboard');
        }

        return redirect()->route('buku.index');
    }

    public function admin(): View
    {
        return view('dashboard.admin', [
            'jumlahBuku' => Buku::count(),
            'jumlahKategori' => Kategori::count(),
            'jumlahPengguna' => User::count(),
            'diajukan' => Peminjaman::where('status', Peminjaman::DIAJUKAN)->count(),
            'dipinjam' => Peminjaman::where('status', Peminjaman::DIPINJAM)->count(),
            'dikembalikan' => Peminjaman::where('status', Peminjaman::DIKEMBALIKAN)->count(),
        ]);
    }

    public function petugas(): View
    {
        return view('dashboard.petugas', [
            'jumlahBuku' => Buku::count(),
            'dipinjam' => Peminjaman::where('status', Peminjaman::DIPINJAM)->count(),
            'menunggu' => Peminjaman::with(['anggota', 'buku'])
                ->where('status', Peminjaman::DIAJUKAN)
                ->latest('id')
                ->limit(5)
                ->get(),
        ]);
    }
}
