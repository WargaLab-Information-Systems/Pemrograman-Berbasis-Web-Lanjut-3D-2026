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
     * Daftar seluruh peminjaman (Khusus Admin dan Petugas)
     */
    public function index(Request $request): View
    {
        $query = Peminjaman::with(['buku', 'user', 'anggota']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $peminjamans = $query->latest()->paginate(10);

        return view('peminjaman.index', [
            'peminjamans' => $peminjamans,
        ]);
    }

    /**
     * Riwayat peminjaman milik sendiri (Untuk Siswa, Petugas, dan Admin)
     */
    public function riwayatSaya(Request $request): View
    {
        $user = $request->user();

        $peminjamans = Peminjaman::with('buku')
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id);
                if ($user->anggota) {
                    $query->orWhere('anggota_id', $user->anggota->id);
                }
            })
            ->latest()
            ->paginate(10);

        return view('peminjaman.riwayat', [
            'peminjamans' => $peminjamans,
        ]);
    }

    /**
     * Form pengajuan peminjaman buku
     */
    public function create(Request $request): View
    {
        $bukuId = $request->query('buku_id');
        $bukus  = Buku::all();

        return view('peminjaman.create', [
            'bukus'  => $bukus,
            'bukuId' => $bukuId,
        ]);
    }

    /**
     * Simpan pengajuan peminjaman
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'buku_id'        => ['required', 'exists:bukus,id'],
            'tanggal_pinjam' => ['required', 'date'],
        ]);

        $user = $request->user();

        // Cari atau buatkan data anggota jika belum ada
        $anggota = $user->anggota;
        if (!$anggota) {
            $anggota = Anggota::firstOrCreate(
                ['email' => $user->email],
                [
                    'user_id'    => $user->id,
                    'nama'       => $user->name,
                    'no_telepon' => '-',
                ]
            );
        }

        Peminjaman::create([
            'user_id'        => $user->id,
            'anggota_id'     => $anggota->id,
            'buku_id'        => $request->buku_id,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'status'         => 'menunggu', // default menunggu persetujuan
        ]);

        return redirect()->route('peminjaman.riwayat')->with('success', 'Pengajuan peminjaman buku berhasil dikirim dan sedang menunggu persetujuan.');
    }

    /**
     * Detail peminjaman dengan pembatasan hak akses berbasis identitas
     */
    public function show(Request $request, int $id): View
    {
        $peminjaman = Peminjaman::with(['buku', 'user', 'anggota'])->findOrFail($id);
        $user       = $request->user();

        // Jika bukan admin/petugas yang punya izin Kelola peminjaman, periksa kepemilikan data pribadi
        if (!$user->can('Kelola peminjaman')) {
            $isOwner = ($peminjaman->user_id === $user->id) ||
                       ($user->anggota && $peminjaman->anggota_id === $user->anggota->id);

            if (!$isOwner) {
                abort(403, 'Akses ditolak. Anda tidak diperbolehkan melihat data riwayat peminjaman milik pengguna lain.');
            }
        }

        return view('peminjaman.show', [
            'peminjaman' => $peminjaman,
        ]);
    }

    /**
     * Memproses persetujuan peminjaman (Khusus Admin & Petugas)
     */
    public function setujui(int $id): RedirectResponse
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update([
            'status' => 'disetujui',
        ]);

        return back()->with('success', 'Peminjaman buku berhasil disetujui.');
    }

    /**
     * Tolak peminjaman (Khusus Admin & Petugas)
     */
    public function tolak(int $id): RedirectResponse
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update([
            'status' => 'ditolak',
        ]);

        return back()->with('success', 'Pengajuan peminjaman telah ditolak.');
    }

    /**
     * Mencatat pengembalian buku (Khusus Admin & Petugas)
     */
    public function kembalikan(Request $request, int $id): RedirectResponse
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update([
            'tanggal_kembali' => now()->toDateString(),
            'status'          => 'dikembalikan',
        ]);

        return back()->with('success', 'Buku telah berhasil dicatat pengembaliannya.');
    }
}

