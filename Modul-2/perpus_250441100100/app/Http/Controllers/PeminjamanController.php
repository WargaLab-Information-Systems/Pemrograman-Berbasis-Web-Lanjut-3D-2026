<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class PeminjamanController extends Controller
{

    /**
     * Kelola peminjaman: daftar seluruh peminjaman + persetujuan.
     * Permission: kelola peminjaman.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');

        $peminjaman = Peminjaman::with(['user', 'anggota', 'buku'])
            ->when(
                in_array($status, $this->daftarStatus(), true),
                fn ($q) => $q->where('status', $status)
            )
            ->latest()
            ->get();

        return view('peminjaman.index', [
            'peminjaman' => $peminjaman,
            'status' => $status,
            'menunggu' => Peminjaman::where('status', Peminjaman::STATUS_DIAJUKAN)->count(),
        ]);
    }

    /**
     * Setujui pengajuan peminjaman.
     * Permission: memproses persetujuan peminjaman.
     */
    public function setujui(Peminjaman $peminjaman): RedirectResponse
    {
        if ($peminjaman->status !== Peminjaman::STATUS_DIAJUKAN) {
            return back()->with('error', 'Hanya pengajuan yang berstatus "diajukan" yang dapat disetujui.');
        }

        if ($peminjaman->buku->sedangDipinjam()) {
            return back()->with('error', 'Buku ini sedang dipinjam oleh pengguna lain.');
        }

        $peminjaman->update([
            'status' => Peminjaman::STATUS_DIPINJAM,
            'tanggal_pinjam' => now()->toDateString(),
        ]);

        return back()->with('success', 'Peminjaman disetujui.');
    }

    /**
     * Tolak pengajuan peminjaman.
     * Permission: memproses persetujuan peminjaman.
     */
    public function tolak(Peminjaman $peminjaman): RedirectResponse
    {
        if ($peminjaman->status !== Peminjaman::STATUS_DIAJUKAN) {
            return back()->with('error', 'Hanya pengajuan yang berstatus "diajukan" yang dapat ditolak.');
        }

        $peminjaman->update(['status' => Peminjaman::STATUS_DITOLAK]);

        return back()->with('success', 'Pengajuan peminjaman ditolak.');
    }

    /**
     * Halaman pengembalian: daftar buku yang sedang dipinjam.
     * Permission: kelola pengembalian.
     */
    public function pengembalian(): View
    {
        $dipinjam = Peminjaman::with(['user', 'anggota', 'buku'])
            ->where('status', Peminjaman::STATUS_DIPINJAM)
            ->oldest('tanggal_pinjam')
            ->get();

        $selesai = Peminjaman::with(['user', 'anggota', 'buku'])
            ->where('status', Peminjaman::STATUS_DIKEMBALIKAN)
            ->latest('tanggal_kembali')
            ->take(10)
            ->get();

        return view('peminjaman.pengembalian', compact('dipinjam', 'selesai'));
    }

    /**
     * Catat pengembalian buku.
     * Permission: mencatat pengembalian buku.
     */
    public function kembalikan(Peminjaman $peminjaman): RedirectResponse
    {
        if ($peminjaman->status !== Peminjaman::STATUS_DIPINJAM) {
            return back()->with('error', 'Hanya buku yang sedang dipinjam yang dapat dikembalikan.');
        }

        $peminjaman->update([
            'status' => Peminjaman::STATUS_DIKEMBALIKAN,
            'tanggal_kembali' => now()->toDateString(),
        ]);

        return back()->with('success', 'Pengembalian buku berhasil dicatat.');
    }

    /* SEMUA ROLE (data milik sendiri) */

    /**
     * Ajukan peminjaman buku atas nama pengguna yang sedang login.
     * Permission: mengajukan peminjaman buku.
     */
    public function ajukan(Buku $buku): RedirectResponse
    {
        $userId = auth()->id();

        $sudahAda = Peminjaman::milik($userId)
            ->where('buku_id', $buku->id)
            ->aktif()
            ->exists();

        if ($sudahAda) {
            return back()->with('error', 'Anda sudah mengajukan atau sedang meminjam buku ini.');
        }

        if ($buku->sedangDipinjam()) {
            return back()->with('error', 'Buku ini sedang dipinjam. Silakan ajukan kembali setelah dikembalikan.');
        }

        Peminjaman::create([
            'user_id' => $userId,
            'buku_id' => $buku->id,
            'tanggal_pinjam' => now()->toDateString(),
            'status' => Peminjaman::STATUS_DIAJUKAN,
        ]);

        return redirect()
            ->route('peminjaman.status.sendiri')
            ->with('success', 'Pengajuan peminjaman berhasil dikirim dan menunggu persetujuan petugas.');
    }

    /**
     * Status peminjaman milik sendiri (yang masih aktif / diproses).
     * Permission: melihat status peminjaman sendiri.
     */
    public function statusSendiri(): View
    {
        $peminjaman = Peminjaman::milik(auth()->id())
            ->with('buku')
            ->whereIn('status', [
                Peminjaman::STATUS_DIAJUKAN,
                Peminjaman::STATUS_DIPINJAM,
                Peminjaman::STATUS_DITOLAK,
            ])
            ->latest()
            ->get();

        return view('peminjaman.status-sendiri', compact('peminjaman'));
    }

    /**
     * Riwayat peminjaman milik sendiri.
     * Permission: melihat riwayat peminjaman sendiri.
     *
     * Selain permission, identitas pengguna yang login tetap diperiksa:
     * parameter {userId} hanya diterima jika sama dengan id pengguna login,
     * selain itu akses ditolak (403) untuk semua role.
     */
    public function riwayatSendiri($userId = null): View
    {
        if ($userId !== null && (int) $userId !== (int) auth()->id()) {
            abort(403, 'Anda tidak berhak melihat riwayat peminjaman pengguna lain.');
        }

        $peminjaman = Peminjaman::milik(auth()->id())
            ->with('buku')
            ->latest()
            ->get();

        return view('peminjaman.riwayat-sendiri', compact('peminjaman'));
    }

    private function daftarStatus(): array
    {
        return [
            Peminjaman::STATUS_DIAJUKAN,
            Peminjaman::STATUS_DIPINJAM,
            Peminjaman::STATUS_DITOLAK,
            Peminjaman::STATUS_DIKEMBALIKAN,
        ];
    }
}
