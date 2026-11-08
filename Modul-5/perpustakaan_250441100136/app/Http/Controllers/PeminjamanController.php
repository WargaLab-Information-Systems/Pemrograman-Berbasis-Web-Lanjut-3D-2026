<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PeminjamanController extends Controller
{
    /**
     * Menampilkan daftar seluruh transaksi peminjaman (Admin & Petugas).
     */
    public function index(): View
    {
        $peminjamans = Peminjaman::with(['anggota', 'buku'])->latest('id')->paginate(15);

        return view('peminjaman.index', compact('peminjamans'));
    }

    /**
     * Mengajukan peminjaman buku oleh pengguna yang sedang login (Siswa/Admin/Petugas).
     */
    public function ajukan(Request $request): RedirectResponse
    {
        $request->validate([
            'buku_id' => ['required', 'exists:bukus,id'],
        ]);

        $user = auth()->user();

        // Hubungkan atau buat data Anggota berdasarkan User yang sedang login
        $anggota = Anggota::firstOrCreate(
            ['email' => $user->email],
            [
                'nama' => $user->name,
                'no_telepon' => '081234567890',
            ]
        );

        // Periksa apakah buku sedang dipinjam dan belum dikembalikan oleh pengguna ini
        $sedangDipinjam = Peminjaman::where('anggota_id', $anggota->id)
            ->where('buku_id', $request->buku_id)
            ->whereNull('tanggal_kembali')
            ->exists();

        if ($sedangDipinjam) {
            return back()->with('error', 'Anda masih meminjam buku ini dan belum mengembalikannya.');
        }

        Peminjaman::create([
            'anggota_id' => $anggota->id,
            'buku_id' => $request->buku_id,
            'tanggal_pinjam' => now()->toDateString(),
            'tanggal_kembali' => null,
        ]);

        return redirect()->route('peminjaman.riwayat')->with('success', 'Buku berhasil dipinjam! Silakan periksa di riwayat peminjaman Anda.');
    }

    /**
     * Menampilkan riwayat peminjaman milik user yang sedang login (Siswa/Admin/Petugas).
     */
    public function riwayatSaya(): View
    {
        $user = auth()->user();

        // Temukan anggota yang sesuai dengan user login
        $anggota = Anggota::where('email', $user->email)->first();

        $riwayat = $anggota
            ? Peminjaman::with('buku')
                ->where('anggota_id', $anggota->id)
                ->latest('id')
                ->get()
            : collect();

        return view('peminjaman.riwayat', compact('riwayat'));
    }

    /**
     * Memproses pengembalian buku oleh Petugas atau Admin.
     */
    public function proses(Request $request, $id): RedirectResponse
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->tanggal_kembali !== null) {
            return back()->with('error', 'Buku ini sudah tercatat telah dikembalikan sebelumnya.');
        }

        $peminjaman->update([
            'tanggal_kembali' => now()->toDateString(),
        ]);

        return back()->with('success', "Buku {$peminjaman->buku->judul} berhasil diproses pengembaliannya.");
    }
}
