<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Dashboard utama: Mengarahkan pengguna sesuai role / hak akses
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->can('Akses dashboard admin')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->can('Akses dashboard petugas')) {
            return redirect()->route('petugas.dashboard');
        }

        // Dashboard untuk Siswa
        $riwayatPeminjaman = Peminjaman::with('buku')
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id);
                if ($user->anggota) {
                    $query->orWhere('anggota_id', $user->anggota->id);
                }
            })
            ->latest()
            ->take(5)
            ->get();

        $bukuPopuler = Buku::with('kategori')->latest()->take(6)->get();

        return view('dashboard.siswa', [
            'riwayatPeminjaman' => $riwayatPeminjaman,
            'bukuPopuler' => $bukuPopuler,
        ]);
    }

    /**
     * Dashboard khusus Admin
     */
    public function admin(): View
    {
        return view('dashboard.admin', [
            'totalBuku' => Buku::count(),
            'totalKategori' => Kategori::count(),
            'totalUser' => User::count(),
            'totalAnggota' => Anggota::count(),
            'totalPeminjaman' => Peminjaman::count(),
            'peminjamanAktif' => Peminjaman::whereNull('tanggal_kembali')->count(),
            'peminjamanTerbaru' => Peminjaman::with(['buku', 'user', 'anggota'])->latest()->take(5)->get(),
        ]);
    }

    /**
     * Dashboard khusus Petugas
     */
    public function petugas(): View
    {
        return view('dashboard.petugas', [
            'totalBuku' => Buku::count(),
            'peminjamanAktif' => Peminjaman::whereNull('tanggal_kembali')->count(),
            'menungguPersetujuan' => Peminjaman::where('status', 'menunggu')->count(),
            'sudahKembali' => Peminjaman::whereNotNull('tanggal_kembali')->count(),
            'peminjamanTerbaru' => Peminjaman::with(['buku', 'user', 'anggota'])->latest()->take(5)->get(),
        ]);
    }
}

