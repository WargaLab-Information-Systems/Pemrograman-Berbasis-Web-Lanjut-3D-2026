<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    public function index()
    {
        $dataPeminjaman = Peminjaman::with(['anggota', 'buku'])->get();
        return view('peminjaman.index', compact('dataPeminjaman'));
    }

    public function riwayat()
    {
        // Filter langsung dari database menggunakan 'where' agar tidak error dengan Policy/Spatie
        $riwayat = Peminjaman::with('buku')
            ->where('anggota_id', Auth::id())
            ->latest()
            ->get();

        return view('peminjaman.riwayat', compact('riwayat'));
    }

    // Menambahkan tipe data 'string' agar garis biru di VS Code (Intelephense) hilang
    public function store(string $buku_id)
    {
        Peminjaman::create([
            'anggota_id' => Auth::id(),
            'buku_id' => $buku_id,
            'tanggal_pinjam' => now(),
            'status' => 'Menunggu Persetujuan'
        ]);

        return back()->with('status', 'Pengajuan peminjaman berhasil dikirim.');
    }

    public function update(Request $request, string $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update([
            'status' => $request->status
        ]);

        return back()->with('status', 'Status peminjaman diperbarui.');
    }

    public function laporan()
    {
        $dataPeminjaman = \App\Models\Peminjaman::latest()->get();

        $laporans = $dataPeminjaman->map(function ($transaksi) {
            $peminjam = \App\Models\User::find($transaksi->anggota_id);
            $buku = \App\Models\Buku::find($transaksi->buku_id);

            return (object)[
                'id' => $transaksi->id,
                'nama_peminjam' => $peminjam->name ?? '-',
                'judul_buku' => $buku->judul ?? $buku->judul_buku ?? '-',
                'tanggal_pinjam' => $transaksi->tanggal_pinjam ?? $transaksi->created_at,
                'tanggal_kembali' => $transaksi->tanggal_kembali ?? '-',
                'status' => $transaksi->status,
            ];
        });

        return view('laporan.index', compact('laporans'));
    }
}