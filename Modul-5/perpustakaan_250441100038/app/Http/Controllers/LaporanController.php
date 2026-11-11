<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /** Halaman laporan (dengan filter tanggal & status). */
    public function index(Request $request)
    {
        return view('laporan.index', $this->data($request));
    }

    /** Halaman siap cetak (tanpa menu, rapi di kertas A4). */
    public function cetak(Request $request)
    {
        return view('laporan.cetak', $this->data($request));
    }

    private function data(Request $request): array
    {
        $request->validate([
            'dari' => ['nullable', 'date'],
            'sampai' => ['nullable', 'date', 'after_or_equal:dari'],
            'status' => ['nullable', 'in:diajukan,dipinjam,dikembalikan,ditolak'],
        ]);

        $query = Peminjaman::with(['user', 'anggota', 'buku'])->orderByDesc('tanggal_pinjam')->orderByDesc('id');

        if ($request->filled('dari')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->input('dari'));
        }
        if ($request->filled('sampai')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->input('sampai'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $peminjamans = $query->get();

        $ringkasan = [
            'Total buku' => Buku::count(),
            'Total peminjaman' => $peminjamans->count(),
            'Menunggu persetujuan' => $peminjamans->where('status', Peminjaman::STATUS_DIAJUKAN)->count(),
            'Sedang dipinjam' => $peminjamans->where('status', Peminjaman::STATUS_DIPINJAM)->count(),
            'Sudah dikembalikan' => $peminjamans->where('status', Peminjaman::STATUS_DIKEMBALIKAN)->count(),
            'Ditolak' => $peminjamans->where('status', Peminjaman::STATUS_DITOLAK)->count(),
        ];

        return [
            'ringkasan' => $ringkasan,
            'peminjamans' => $peminjamans,
            'perKategori' => Kategori::withCount('bukus')->get(),
            'populer' => Buku::withCount('peminjamans')->orderByDesc('peminjamans_count')->take(5)->get(),
            'filter' => $request->only(['dari', 'sampai', 'status']),
        ];
    }
}
