<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\User;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    // Tampilkan Halaman Kelola Peminjaman
    public function index()
    {
        $peminjamans = Peminjaman::with(['user', 'buku'])->latest()->get();
        $bukus = Buku::all();

        $siswas = User::whereHas('roles', function ($query) {
            $query->where('name', 'siswa');
        })->get();

        return view('peminjaman.index', compact('peminjamans', 'bukus', 'siswas'));
    }

    // Simpan Transaksi Peminjaman Manual (Admin / Petugas)
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'buku_id' => 'required|exists:bukus,id',
            'tanggal_pinjam' => 'required|date',
        ]);

        Peminjaman::create([
            'user_id' => $request->user_id,
            'buku_id' => $request->buku_id,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'status' => 'Dipinjam',
        ]);

        return redirect()->back()->with('success', 'Data peminjaman berhasil ditambahkan.');
    }

    // ACC Pengembalian Buku (Ubah Status ke "Dikembalikan")
    public function update(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        $peminjaman->update([
            'status' => 'Dikembalikan',
            'tanggal_kembali' => now()->toDateString(),
        ]);

        return redirect()->back()->with('success', 'Buku berhasil di-ACC dan dikembalikan!');
    }

    // Siswa Mengajukan Peminjaman
    public function ajukan($buku_id)
    {
        $buku = Buku::findOrFail($buku_id);
        $userId = auth()->id();

        $peminjamanAktif = Peminjaman::where('user_id', $userId)
            ->where('buku_id', $buku_id)
            ->where('status', 'Dipinjam')
            ->first();

        if ($peminjamanAktif) {
            return redirect()->back()->with('error', 'Anda masih meminjam buku "' . $buku->judul . '".');
        }

        Peminjaman::create([
            'user_id' => $userId,
            'buku_id' => $buku->id,
            'tanggal_pinjam' => now()->toDateString(),
            'status' => 'Dipinjam',
        ]);

        return redirect()->route('peminjaman.riwayat')->with('success', 'Peminjaman buku "' . $buku->judul . '" berhasil diajukan!');
    }

    // Riwayat Peminjaman Siswa
    public function riwayat()
    {
        $peminjamans = Peminjaman::with('buku')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('peminjaman.riwayat', compact('peminjamans'));
    }
}