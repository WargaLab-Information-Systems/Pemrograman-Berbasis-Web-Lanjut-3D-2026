<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PeminjamanController extends Controller
{
    // Menampilkan peminjaman berdasarkan hak akses.
    public function index(Request $request): View
    {
        $user = $request->user();

        abort_unless(
            $user->can('kelola peminjaman') ||
            $user->can('lihat riwayat peminjaman sendiri'),
            403
        );

        $query = Peminjaman::with(['anggota', 'buku'])
            ->orderByDesc('id');

        // Siswa hanya dapat melihat peminjaman miliknya sendiri.
        if (!$user->can('kelola peminjaman')) {
            $query->whereHas('anggota', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        $peminjamans = $query->paginate(10);

        $bukus = $user->can('ajukan peminjaman buku')
            ? Buku::orderBy('judul')->get()
            : collect();

        $anggota = Anggota::where('user_id', $user->id)->first();

        return view('peminjaman.index', compact(
            'peminjamans',
            'bukus',
            'anggota'
        ));
    }

    // Siswa mengajukan peminjaman buku.
    public function store(Request $request): RedirectResponse
    {
        abort_unless(
            $request->user()->can('ajukan peminjaman buku'),
            403
        );

        $user = $request->user();

        $anggota = Anggota::where('user_id', $user->id)->first();

        $rules = [
            'buku_id' => ['required', 'integer', 'exists:bukus,id'],
        ];

        if (!$anggota) {
            $rules['no_telepon'] = [
                'required',
                'string',
                'max:255',
            ];
        }

        $data = $request->validate($rules);

        $hasil = DB::transaction(function () use ($user, $anggota, $data) {
            if (!$anggota) {
                $anggota = Anggota::create([
                    'user_id' => $user->id,
                    'nama' => $user->name,
                    'email' => $user->email,
                    'no_telepon' => $data['no_telepon'],
                ]);
            }

            $sudahMeminjam = Peminjaman::where(
                'anggota_id',
                $anggota->id
            )
                ->where('buku_id', $data['buku_id'])
                ->whereIn('status', [
                    'menunggu',
                    'disetujui',
                    'dipinjam',
                ])
                ->exists();

            if ($sudahMeminjam) {
                return false;
            }

            Peminjaman::create([
                'anggota_id' => $anggota->id,
                'buku_id' => $data['buku_id'],
                'tanggal_pinjam' => now()->toDateString(),
                'tanggal_kembali' => null,
                'status' => 'menunggu',
            ]);

            return true;
        });

        if (!$hasil) {
            return back()->with(
                'error',
                'Kamu masih memiliki peminjaman aktif untuk buku ini.'
            );
        }

        return back()->with(
            'success',
            'Pengajuan peminjaman berhasil dikirim.'
        );
    }

    // Admin/Petugas menyetujui atau menolak peminjaman.
    public function updateStatus(
        Request $request,
        Peminjaman $peminjaman
    ): RedirectResponse {
        abort_unless(
            $request->user()->can('proses persetujuan peminjaman'),
            403
        );

        $data = $request->validate([
            'status' => [
                'required',
                'in:disetujui,ditolak',
            ],
        ]);

        if ($peminjaman->status !== 'menunggu') {
            return back()->with(
                'error',
                'Hanya pengajuan berstatus menunggu yang bisa diproses.'
            );
        }

        $peminjaman->update([
            'status' => $data['status'],
        ]);

        return back()->with(
            'success',
            'Status peminjaman berhasil diperbarui.'
        );
    }

    // Admin/Petugas mencatat pengembalian buku.
    public function kembalikan(
        Request $request,
        Peminjaman $peminjaman
    ): RedirectResponse {
        abort_unless(
            $request->user()->can('catat pengembalian buku'),
            403
        );

        if (!in_array(
            $peminjaman->status,
            ['disetujui', 'dipinjam'],
            true
        )) {
            return back()->with(
                'error',
                'Hanya buku yang sedang dipinjam yang dapat dikembalikan.'
            );
        }

        $peminjaman->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => now()->toDateString(),
        ]);

        return back()->with(
            'success',
            'Pengembalian buku berhasil dicatat.'
        );
    }
}