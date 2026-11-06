<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class PeminjamanWebController extends Controller
{
    /**
     * Helper untuk mengambil data Anggota dari pengguna yang sedang login.
     */
    private function getAnggotaLogin()
    {
        return Anggota::firstOrCreate(
            ['email' => auth()->user()->email],
            ['nama' => auth()->user()->name]
        );
    }

    /**
     * ADMIN & PETUGAS: Menampilkan seluruh data peminjaman perpustakaan.
     */
    public function index(Request $request)
    {
        $query = Peminjaman::with(['anggota', 'buku.kategori'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $peminjamans = $query->paginate(15)->withQueryString();

        return view('peminjaman.index', compact('peminjamans'));
    }

    /**
     * SISWA / SEMUA: Menampilkan riwayat peminjaman milik sendiri.
     */
    public function indexSaya()
    {
        $anggota = $this->getAnggotaLogin();

        $peminjamans = Peminjaman::with('buku.kategori')
            ->where('anggota_id', $anggota->id)
            ->latest()
            ->paginate(15);

        return view('peminjaman.saya', compact('peminjamans'));
    }

    /**
     * Menampilkan detail peminjaman dengan Identity-Based Check (Skenario 14).
     */
    public function show($id)
    {
        $peminjaman = Peminjaman::with(['anggota', 'buku.kategori'])->findOrFail($id);
        $user = auth()->user();

        // Pemeriksaan identitas jika pengguna adalah Siswa
        if ($user->hasRole('siswa')) {
            $anggota = $this->getAnggotaLogin();

            if ($peminjaman->anggota_id !== $anggota->id) {
                abort(403, 'Akses ditolak. Anda tidak diperbolehkan melihat riwayat peminjaman siswa lain.');
            }
        }

        return view('peminjaman.show', compact('peminjaman'));
    }

    /**
     * PETUGAS / ADMIN: Menyetujui pengajuan peminjaman.
     */
    public function setujui($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->status !== 'diajukan') {
            return back()->with('error', 'Status peminjaman ini tidak dapat disetujui.');
        }

        $peminjaman->update([
            'status' => 'dipinjam',
            'tanggal_pinjam' => now()->toDateString(),
        ]);

        return back()->with('success', 'Peminjaman buku berhasil disetujui.');
    }

    /**
     * PETUGAS / ADMIN: Menolak pengajuan peminjaman.
     */
    public function tolak($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->status !== 'diajukan') {
            return back()->with('error', 'Status peminjaman ini tidak dapat ditolak.');
        }

        $peminjaman->update([
            'status' => 'ditolak',
        ]);

        return back()->with('success', 'Pengajuan peminjaman ditolak.');
    }

    /**
     * SISWA: Mengajukan permohonan pengembalian buku.
     */
    public function ajukanPengembalian($id)
    {
        $anggota = $this->getAnggotaLogin();

        $peminjaman = Peminjaman::where('anggota_id', $anggota->id)->findOrFail($id);

        if ($peminjaman->status !== 'dipinjam') {
            return back()->with('error', 'Buku ini belum dapat diajukan untuk pengembalian.');
        }

        $peminjaman->update([
            'status' => 'menunggu_pengembalian',
        ]);

        return back()->with('success', 'Pengajuan pengembalian buku berhasil dikirim.');
    }

    /**
     * PETUGAS / ADMIN: Mengonfirmasi / mencatat pengembalian buku.
     */
    public function konfirmasiPengembalian($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if (!in_array($peminjaman->status, ['menunggu_pengembalian', 'dipinjam'])) {
            return back()->with('error', 'Status peminjaman tidak valid untuk pengembalian.');
        }

        $peminjaman->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => now()->toDateString(),
        ]);

        return back()->with('success', 'Pengembalian buku berhasil dicatat.');
    }
}

