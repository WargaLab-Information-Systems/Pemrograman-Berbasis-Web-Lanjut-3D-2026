<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PeminjamanController extends Controller
{
    public function store(Request $request, Buku $buku): RedirectResponse
    {
        $user = $request->user();
        $anggota = $user->anggota()->firstOrCreate([], [
            'nama' => $user->name,
            'email' => $user->email,
            'no_telepon' => '-',
        ]);

        $alreadyRequested = $anggota->peminjamans()
            ->where('buku_id', $buku->id)
            ->whereIn('status', ['menunggu', 'disetujui', 'dipinjam'])
            ->exists();

        if ($alreadyRequested) {
            return back()->with('error', 'Anda masih memiliki pengajuan atau peminjaman aktif untuk buku ini.');
        }

        $anggota->peminjamans()->create([
            'buku_id' => $buku->id,
            'tanggal_pinjam' => now()->toDateString(),
            'status' => 'menunggu',
        ]);

        if ($user->hasRole('siswa')) {
            return redirect()
                ->route('siswa.peminjaman.index')
                ->with('success', 'Pengajuan peminjaman berhasil dikirim dan menunggu persetujuan petugas.');
        }

        return back()->with('success', 'Pengajuan peminjaman berhasil dikirim dan menunggu persetujuan petugas.');
    }

    public function riwayat(Request $request): View
    {
        $anggota = $request->user()->anggota()->firstOrCreate([], [
            'nama' => $request->user()->name,
            'email' => $request->user()->email,
            'no_telepon' => '-',
        ]);

        $peminjamans = Peminjaman::with('buku')
            ->where('anggota_id', $anggota->id)
            ->latest()
            ->get();

        $dashboardRoute = match (true) {
            $request->user()->hasRole('admin') => 'admin.dashboard',
            $request->user()->hasRole('petugas') => 'petugas.dashboard',
            default => 'siswa.dashboard',
        };

        return view('siswa.peminjaman.index', compact('dashboardRoute', 'peminjamans'));
    }

    public function show(string $id): View
    {
        $peminjaman = Peminjaman::findOrFail($id);

        return view('siswa.peminjaman.show', compact('peminjaman'));
    }
}
