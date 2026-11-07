<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    // Menampilkan seluruh data peminjaman
    public function index()
    {
        $peminjamans = Peminjaman::with(['anggota', 'buku'])
            ->latest()
            ->get();

        return view('petugas.peminjaman.index', compact('peminjamans'));
    }

    // Menampilkan formulir tambah peminjaman
    public function create()
    {
        $anggotas = Anggota::all();
        $bukus = Buku::all();

        return view('petugas.peminjaman.create', compact('anggotas', 'bukus'));
    }

    // Menyimpan data peminjaman
    public function store(Request $request)
    {
        $validated = $request->validate([
            'anggota_id' => 'required|exists:anggotas,id',
            'buku_id' => 'required|exists:bukus,id',
            'tanggal_pinjam' => 'required|date',
        ]);

        Peminjaman::create($validated);

        return redirect()
            ->route('petugas.peminjaman.index')
            ->with('success', 'Data peminjaman berhasil ditambahkan.');
    }

    // Memproses pengembalian buku
    public function kembalikan(Peminjaman $peminjaman)
    {
        if ($peminjaman->tanggal_kembali !== null || ! in_array($peminjaman->status, ['disetujui', 'dipinjam'], true)) {
            return redirect()
                ->route('petugas.peminjaman.index')
                ->with('error', 'Buku sudah dikembalikan sebelumnya.');
        }

        $peminjaman->update([
            'tanggal_kembali' => now()->toDateString(),
            'status' => 'dikembalikan',
        ]);

        return redirect()
            ->route('petugas.peminjaman.index')
            ->with('success', 'Buku berhasil dikembalikan.');
    }

    public function proses(Peminjaman $peminjaman, string $status): RedirectResponse
    {
        abort_unless(in_array($status, ['disetujui', 'ditolak'], true), 404);
        abort_unless($peminjaman->status === 'menunggu', 404);

        $peminjaman->update(['status' => $status]);

        return redirect()
            ->route('petugas.peminjaman.index')
            ->with('success', 'Pengajuan peminjaman berhasil diproses.');
    }
}
