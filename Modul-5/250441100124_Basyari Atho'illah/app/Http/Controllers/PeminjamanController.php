<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PeminjamanController extends Controller
{
    /**
     * Menampilkan seluruh data peminjaman buku (Khusus Admin dan Petugas).
     */
    public function index(Request $request): View
    {
        $query = Peminjaman::with(['user', 'buku', 'anggota'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('buku', function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%");
            })->orWhereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $peminjamans = $query->paginate(10)->withQueryString();

        return view('peminjaman.index', compact('peminjamans'));
    }

    /**
     * Siswa mengajukan peminjaman buku.
     */
    public function ajukanPinjam(Request $request, int $bukuId): RedirectResponse
    {
        $user = Auth::user();
        $buku = Buku::findOrFail($bukuId);

        // Cari atau buat profil anggota untuk user ini
        $anggota = Anggota::firstOrCreate(
            ['user_id' => $user->id],
            [
                'nama' => $user->name,
                'email' => $user->email,
                'no_telepon' => '-',
            ]
        );

        // Cek apakah siswa masih memiliki peminjaman aktif/menunggu untuk buku yang sama
        $existing = Peminjaman::where('user_id', $user->id)
            ->where('buku_id', $buku->id)
            ->whereIn('status', ['menunggu', 'dipinjam'])
            ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah memiliki permohonan atau peminjaman aktif untuk buku ini.');
        }

        Peminjaman::create([
            'user_id' => $user->id,
            'anggota_id' => $anggota->id,
            'buku_id' => $buku->id,
            'tanggal_pinjam' => now()->toDateString(),
            'tanggal_kembali' => null,
            'status' => 'menunggu',
            'catatan' => 'Pengajuan mandiri oleh siswa: '.$user->name,
        ]);

        return redirect()->route('siswa.peminjaman.riwayat')
            ->with('success', "Pengajuan peminjaman buku '{$buku->judul}' berhasil dikirim. Menunggu verifikasi petugas.");
    }

    /**
     * Menampilkan riwayat peminjaman siswa yang sedang login.
     */
    public function riwayatSaya(): View
    {
        $user = Auth::user();

        $peminjamans = Peminjaman::with('buku')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('peminjaman.riwayat', compact('peminjamans'));
    }

    /**
     * Detail peminjaman buku dengan pengecekan kepemilikan data (Data Isolation).
     */
    public function show(Peminjaman $peminjaman): View
    {
        $user = Auth::user();

        // Pemeriksaan Hak Akses Berdasarkan Identitas (Skenario 14)
        if ($user->hasRole('siswa') && $peminjaman->user_id !== $user->id) {
            abort(403, 'Akses Ditolak: Anda tidak memiliki izin untuk melihat riwayat peminjaman milik pengguna lain.');
        }

        $peminjaman->load(['user', 'buku', 'anggota']);

        return view('peminjaman.show', compact('peminjaman'));
    }

    /**
     * Petugas/Admin menyetujui pengajuan peminjaman buku.
     */
    public function setujui(Peminjaman $peminjaman): RedirectResponse
    {
        $peminjaman->update([
            'status' => 'dipinjam',
            'tanggal_pinjam' => now()->toDateString(),
            'catatan' => 'Disetujui oleh '.Auth::user()->name.' pada '.now()->format('d/m/Y H:i'),
        ]);

        return back()->with('success', 'Peminjaman buku berhasil disetujui.');
    }

    /**
     * Petugas/Admin menolak pengajuan peminjaman buku.
     */
    public function tolak(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        $alasan = $request->input('alasan', 'Ditolak oleh petugas perpustakaan.');

        $peminjaman->update([
            'status' => 'ditolak',
            'catatan' => $alasan,
        ]);

        return back()->with('success', 'Pengajuan peminjaman berhasil ditolak.');
    }

    /**
     * Petugas/Admin mencatat pengembalian buku.
     */
    public function kembalikan(Peminjaman $peminjaman): RedirectResponse
    {
        $peminjaman->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => now()->toDateString(),
            'catatan' => 'Buku telah dikembalikan dan diverifikasi oleh '.Auth::user()->name,
        ]);

        return back()->with('success', 'Pengembalian buku berhasil dicatat.');
    }
}
