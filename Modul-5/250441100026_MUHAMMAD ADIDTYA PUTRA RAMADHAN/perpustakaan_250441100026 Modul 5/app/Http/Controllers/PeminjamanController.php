<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PeminjamanController extends Controller
{
    /**
     * Tampilkan seluruh data peminjaman untuk Admin & Petugas.
     */
    public function index(): View
    {
        $peminjamans = Peminjaman::with(['user', 'buku', 'anggota'])->latest()->paginate(10);
        $bukus = Buku::orderBy('judul')->get();

        return view('peminjaman.index', compact('peminjamans', 'bukus'));
    }

    /**
     * Tampilkan riwayat peminjaman milik pengguna yang sedang login (Siswa).
     */
    public function riwayatSaya(): View
    {
        $peminjamans = Peminjaman::where('user_id', auth()->id())
            ->with('buku')
            ->latest()
            ->paginate(10);

        return view('peminjaman.riwayat', compact('peminjamans'));
    }

    /**
     * Tampilkan detail riwayat peminjaman tertentu.
     * Hak akses diperiksa berdasarkan identitas pengguna (ownership check).
     */
    public function showRiwayat(int $id): View
    {
        $peminjaman = Peminjaman::with('buku')->findOrFail($id);

        // Jika bukan peminjam dan bukan admin/petugas, tolak akses (403)
        if ($peminjaman->user_id !== auth()->id() && ! auth()->user()->hasAnyRole(['admin', 'petugas'])) {
            abort(403, 'Akses ditolak. Anda tidak berhak melihat riwayat peminjaman milik pengguna lain.');
        }

        return view('peminjaman.detail_riwayat', compact('peminjaman'));
    }

    /**
     * Ajukan peminjaman buku oleh Siswa/Petugas/Admin.
     */
    public function ajukan(Request $request): RedirectResponse
    {
        $request->validate([
            'buku_id' => ['required', 'exists:bukus,id'],
            'tanggal_pinjam' => ['nullable', 'date'],
        ]);

        $tanggalPinjam = $request->input('tanggal_pinjam', now()->toDateString());

        Peminjaman::create([
            'user_id' => auth()->id(),
            'buku_id' => $request->buku_id,
            'tanggal_pinjam' => $tanggalPinjam,
            'status' => 'dipinjam',
        ]);

        if (auth()->user()->hasRole('siswa')) {
            return redirect()->route('riwayat.index')->with('success', 'Buku berhasil dipinjam / diajukan!');
        }

        return redirect()->back()->with('success', 'Peminjaman berhasil dicatat.');
    }

    /**
     * Memproses persetujuan peminjaman (Admin & Petugas).
     */
    public function setujui(Peminjaman $peminjaman): RedirectResponse
    {
        $peminjaman->update([
            'status' => 'dipinjam',
        ]);

        return redirect()->back()->with('success', 'Peminjaman buku berhasil disetujui.');
    }

    /**
     * Mencatat pengembalian buku (Admin & Petugas).
     */
    public function kembali(Peminjaman $peminjaman): RedirectResponse
    {
        $peminjaman->update([
            'tanggal_kembali' => now()->toDateString(),
            'status' => 'dikembalikan',
        ]);

        return redirect()->back()->with('success', 'Buku berhasil dicatat telah dikembalikan.');
    }

    /**
     * Hapus data peminjaman (Admin & Petugas).
     */
    public function destroy(Peminjaman $peminjaman): RedirectResponse
    {
        $peminjaman->delete();

        return redirect()->back()->with('success', 'Data peminjaman berhasil dihapus.');
    }
}
