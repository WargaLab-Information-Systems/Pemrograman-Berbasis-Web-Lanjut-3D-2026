<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    // Mencari anggota berdasarkan email akun yang login
    private function anggotaLogin()
    {
        return Anggota::where(
            'email',
            auth()->user()->email
        )->firstOrFail();
    }

    // SISWA: Melihat riwayat peminjaman sendiri
    public function indexSaya()
    {
        $anggota = $this->anggotaLogin();

        $peminjamans = Peminjaman::with('buku.kategori')
            ->where('anggota_id', $anggota->id)
            ->latest()
            ->get();

        return response()->json([
            'message' => 'Riwayat peminjaman berhasil diambil.',
            'data' => $peminjamans
        ]);
    }

    // SISWA: Mengajukan peminjaman
    public function store(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:bukus,id',
        ]);

        $anggota = $this->anggotaLogin();

        $masihMeminjam = Peminjaman::where(
            'anggota_id',
            $anggota->id
        )
        ->where('buku_id', $request->buku_id)
        ->whereIn('status', [
            'diajukan',
            'dipinjam',
            'menunggu_pengembalian'
        ])
        ->exists();

        if ($masihMeminjam) {
            return response()->json([
                'message' => 'Kamu masih memiliki pengajuan atau peminjaman aktif untuk buku ini.'
            ], 422);
        }

        $peminjaman = Peminjaman::create([
            'anggota_id' => $anggota->id,
            'buku_id' => $request->buku_id,
            'status' => 'diajukan',
        ]);

        return response()->json([
            'message' => 'Pengajuan peminjaman berhasil dikirim.',
            'data' => $peminjaman
        ], 201);
    }

    // PETUGAS: Melihat seluruh pengajuan
    public function indexPetugas()
    {
        $peminjamans = Peminjaman::with([
            'anggota',
            'buku.kategori'
        ])->latest()->get();

        return response()->json([
            'data' => $peminjamans
        ]);
    }

    // PETUGAS: Menyetujui peminjaman
    public function setujui($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->status !== 'diajukan') {
            return response()->json([
                'message' => 'Pengajuan ini tidak dapat disetujui.'
            ], 422);
        }

        $peminjaman->update([
            'status' => 'dipinjam',
            'tanggal_pinjam' => now()->toDateString(),
        ]);

        return response()->json([
            'message' => 'Peminjaman berhasil disetujui.',
            'data' => $peminjaman
        ]);
    }

    // PETUGAS: Menolak peminjaman
    public function tolak($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->status !== 'diajukan') {
            return response()->json([
                'message' => 'Pengajuan ini tidak dapat ditolak.'
            ], 422);
        }

        $peminjaman->update([
            'status' => 'ditolak',
        ]);

        return response()->json([
            'message' => 'Pengajuan peminjaman ditolak.'
        ]);
    }

    // SISWA: Mengajukan pengembalian
    public function ajukanPengembalian($id)
    {
        $anggota = $this->anggotaLogin();

        $peminjaman = Peminjaman::where('anggota_id', $anggota->id)
            ->findOrFail($id);

        if ($peminjaman->status !== 'dipinjam') {
            return response()->json([
                'message' => 'Buku ini belum dapat diajukan untuk pengembalian.'
            ], 422);
        }

        $peminjaman->update([
            'status' => 'menunggu_pengembalian',
        ]);

        return response()->json([
            'message' => 'Pengajuan pengembalian berhasil dikirim.'
        ]);
    }

    // PETUGAS: Mengonfirmasi buku sudah dikembalikan
    public function konfirmasiPengembalian($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->status !== 'menunggu_pengembalian') {
            return response()->json([
                'message' => 'Pengajuan ini belum menunggu pengembalian.'
            ], 422);
        }

        $peminjaman->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => now()->toDateString(),
        ]);

        return response()->json([
            'message' => 'Pengembalian buku berhasil dikonfirmasi.'
        ]);
    }

    // Detail peminjaman dengan Identity-Based Check (Skenario 14)
    public function show($id)
    {
        $peminjaman = Peminjaman::with(['anggota', 'buku.kategori'])->findOrFail($id);
        $user = auth()->user();

        // Jika siswa, hanya boleh melihat data miliknya sendiri
        if ($user->hasRole('siswa')) {
            $anggota = Anggota::where('email', $user->email)->first();
            if (!$anggota || $peminjaman->anggota_id !== $anggota->id) {
                return response()->json([
                    'message' => 'Akses ditolak. Anda tidak berhak melihat riwayat peminjaman siswa lain.'
                ], 403);
            }
        }

        return response()->json([
            'message' => 'Detail peminjaman berhasil diambil.',
            'data' => $peminjaman
        ]);
    }

    // PETUGAS / ADMIN: Mencatat pengembalian secara langsung
    public function catatPengembalian($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if (!in_array($peminjaman->status, ['dipinjam', 'menunggu_pengembalian'])) {
            return response()->json([
                'message' => 'Status peminjaman tidak valid untuk pengembalian.'
            ], 422);
        }

        $peminjaman->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => now()->toDateString(),
        ]);

        return response()->json([
            'message' => 'Pengembalian buku berhasil dicatat.',
            'data' => $peminjaman
        ]);
    }
}
