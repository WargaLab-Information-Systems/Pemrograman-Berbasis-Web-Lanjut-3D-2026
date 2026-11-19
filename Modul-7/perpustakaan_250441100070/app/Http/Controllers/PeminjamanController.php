<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class PeminjamanController extends Controller
{
    /** Pengguna mengajukan peminjaman sebuah buku. (Tidak berubah) */
    public function ajukan(Request $request, string $id): RedirectResponse
    {
        $buku = Buku::findOrFail($id);
        $anggota = $request->user()->profilAnggota();

        $sudahAda = Peminjaman::where('anggota_id', $anggota->id)
            ->where('buku_id', $buku->id)
            ->whereIn('status', [Peminjaman::DIAJUKAN, Peminjaman::DIPINJAM])
            ->exists();

        if ($sudahAda) {
            return redirect()->route('buku.show', $buku->id)
                ->with('galat', 'Anda sudah mengajukan atau sedang meminjam buku ini.');
        }

        Peminjaman::create([
            'anggota_id' => $anggota->id,
            'buku_id' => $buku->id,
            'status' => Peminjaman::DIAJUKAN,
        ]);

        return redirect()->route('riwayat.show', $request->user())
            ->with('sukses', 'Pengajuan peminjaman berhasil dikirim. Menunggu persetujuan petugas.');
    }

    /**
     * SOAL 4: ditutup dengan Policy.
     *
     * Dua perubahan dari versi Soal 1/2 (lihat diff-nya):
     * 1. Parameter diketik `Peminjaman $peminjaman` (implicit route model
     *    binding) menggantikan `string $id` + `Peminjaman::findOrFail($id)`
     *    manual -- route juga diubah memakai {peminjaman}, bukan {id}.
     * 2. Baris Gate::authorize ditambahkan SEBELUM data ditampilkan. Baris
     *    ini memanggil PeminjamanPolicy@view: kalau mengembalikan false,
     *    Laravel otomatis menghentikan request dengan 403 Forbidden --
     *    tidak perlu if/else manual.
     */
    public function show(Peminjaman $peminjaman): View
    {
        Gate::authorize('view', $peminjaman);

        $peminjaman->load(['anggota', 'buku.kategori']);

        return view('peminjaman.show', compact('peminjaman'));
    }

    /** Daftar pengajuan dan peminjaman berjalan (admin/petugas). (Tidak berubah) */
    public function index(): View
    {
        $peminjamans = Peminjaman::with(['anggota', 'buku', 'pemroses'])
            ->whereIn('status', [Peminjaman::DIAJUKAN, Peminjaman::DIPINJAM, Peminjaman::DITOLAK])
            ->latest('id')
            ->get();

        return view('peminjaman.index', compact('peminjamans'));
    }

    public function setujui(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        if ($peminjaman->status !== Peminjaman::DIAJUKAN) {
            return back()->with('galat', 'Hanya pengajuan yang bisa disetujui.');
        }

        $peminjaman->update([
            'status' => Peminjaman::DIPINJAM,
            'tanggal_pinjam' => now()->toDateString(),
            'diproses_oleh' => $request->user()->id,
        ]);

        return back()->with('sukses', 'Peminjaman disetujui.');
    }

    public function tolak(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        if ($peminjaman->status !== Peminjaman::DIAJUKAN) {
            return back()->with('galat', 'Hanya pengajuan yang bisa ditolak.');
        }

        $peminjaman->update([
            'status' => Peminjaman::DITOLAK,
            'diproses_oleh' => $request->user()->id,
        ]);

        return back()->with('sukses', 'Pengajuan ditolak.');
    }

    /** Daftar buku yang sedang dipinjam dan yang sudah dikembalikan. (Tidak berubah) */
    public function pengembalian(): View
    {
        $dipinjam = Peminjaman::with(['anggota', 'buku'])
            ->where('status', Peminjaman::DIPINJAM)
            ->latest('id')
            ->get();

        $dikembalikan = Peminjaman::with(['anggota', 'buku'])
            ->where('status', Peminjaman::DIKEMBALIKAN)
            ->latest('id')
            ->limit(20)
            ->get();

        return view('peminjaman.pengembalian', compact('dipinjam', 'dikembalikan'));
    }

    public function kembali(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        if ($peminjaman->status !== Peminjaman::DIPINJAM) {
            return back()->with('galat', 'Buku ini tidak sedang dipinjam.');
        }

        $peminjaman->update([
            'status' => Peminjaman::DIKEMBALIKAN,
            'tanggal_kembali' => now()->toDateString(),
            'diproses_oleh' => $request->user()->id,
        ]);

        return back()->with('sukses', 'Pengembalian buku berhasil dicatat.');
    }
}
