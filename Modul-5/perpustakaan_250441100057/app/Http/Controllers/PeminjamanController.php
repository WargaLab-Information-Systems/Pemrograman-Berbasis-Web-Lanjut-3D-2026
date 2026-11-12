<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{

    public function index(Request $request)
    {
        $user = auth()->user();
        $bukus = Buku::all();

        if ($user->hasRole('siswa')) {
            $anggota = Anggota::where('email', $user->email)->first();

            $query = Peminjaman::with(['buku', 'anggota']);

            if ($anggota) {
                $query->where('anggota_id', $anggota->id);
            } else {
                $query->whereRaw('1 = 0');
            }
        } else {
            $query = Peminjaman::with(['buku', 'anggota']);
        }

        if ($request->filled('cari')) {
            $cari = $request->cari;

            $query->where(function ($q) use ($cari) {
                $q->whereHas('anggota', function ($anggota) use ($cari) {
                    $anggota->where('nama', 'like', "%{$cari}%");
                })->orWhereHas('buku', function ($buku) use ($cari) {
                    $buku->where('judul', 'like', "%{$cari}%");
                });
            });
        }

        $statusValid = [
            'menunggu',
            'dipinjam',
            'pengembalian_diajukan',
            'dikembalikan',
            'ditolak',
        ];

        if (
            $request->filled('status') &&
            in_array($request->status, $statusValid)
        ) {
            $query->where('status', $request->status);
        }

        $peminjamans = $query->latest()->get();

        $rekap = Peminjaman::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN status = 'menunggu' THEN 1 ELSE 0 END) as menunggu,
            SUM(CASE WHEN status = 'dipinjam' THEN 1 ELSE 0 END) as dipinjam,
            SUM(CASE WHEN status = 'pengembalian_diajukan' THEN 1 ELSE 0 END) as pengembalian_diajukan,
            SUM(CASE WHEN status = 'dikembalikan' THEN 1 ELSE 0 END) as dikembalikan,
            SUM(CASE WHEN status = 'ditolak' THEN 1 ELSE 0 END) as ditolak
        ")->first();

        return view('peminjaman.index', compact(
            'peminjamans',
            'bukus',
            'rekap'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:bukus,id',
        ]);

        $anggota = Anggota::where('email', auth()->user()->email)->first();

        if (!$anggota) {
            return back()->with('error', 'Data anggota belum ditemukan.');
        }

        $sudahMeminjam = Peminjaman::where('anggota_id', $anggota->id)
            ->where('buku_id', $request->buku_id)
            ->whereIn('status', [
                'menunggu',
                'dipinjam',
                'pengembalian_diajukan',
            ])
            ->exists();

        if ($sudahMeminjam) {
            return back()->with('error', 'Buku ini masih dalam proses peminjaman.');
        }

        Peminjaman::create([
            'anggota_id' => $anggota->id,
            'buku_id' => $request->buku_id,
            'tanggal_pinjam' => now()->toDateString(),
            'status' => 'menunggu',
        ]);

        return back()->with('success', 'Pengajuan peminjaman berhasil dikirim.');
    }

    public function requestReturn($id)
    {
        $anggota = Anggota::where('email', auth()->user()->email)->first();

        $peminjaman = Peminjaman::where('id', $id)
            ->where('anggota_id', $anggota?->id)
            ->firstOrFail();

        if ($peminjaman->status !== 'dipinjam') {
            return back()->with('error', 'Peminjaman ini belum dapat dikembalikan.');
        }

        $peminjaman->update([
            'status' => 'pengembalian_diajukan',
        ]);

        return back()->with('success', 'Pengajuan pengembalian berhasil dikirim.');
    }

    public function approve($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->status !== 'menunggu') {
            return back()->with('error', 'Pengajuan tidak dapat diproses.');
        }

        $peminjaman->update([
            'status' => 'dipinjam',
            'tanggal_pinjam' => now()->toDateString(),
        ]);

        return back()->with('success', 'Peminjaman berhasil disetujui.');
    }

    public function reject($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->status !== 'menunggu') {
            return back()->with('error', 'Pengajuan tidak dapat ditolak.');
        }

        $peminjaman->update([
            'status' => 'ditolak',
        ]);

        return back()->with('success', 'Pengajuan peminjaman ditolak.');
    }

    public function confirmReturn($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->status !== 'pengembalian_diajukan') {
            return back()->with('error', 'Pengajuan pengembalian belum tersedia.');
        }

        $peminjaman->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => now()->toDateString(),
        ]);

        return back()->with('success', 'Pengembalian buku berhasil dikonfirmasi.');
    }
}