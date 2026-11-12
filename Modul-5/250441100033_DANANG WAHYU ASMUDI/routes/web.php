<?php

use App\Models\Kategori;
use App\Models\Buku;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('home');
})->name('home');

// Halaman Buku (Didaftarkan agar route('buku.index') ditemukan)
Route::get('/buku', function () {
    $kategoris = Kategori::all();
    return view('buku.index', compact('kategoris'));
})->name('buku.index');

Route::get('/buku/{id}', function ($id) {
    $buku = Buku::with('kategori')->find($id);
    return view('buku.show', compact('buku'));
})->name('buku.show');

// Rute yang membutuhkan autentikasi
Route::middleware(['auth'])->group(function () {
    // Rute Dashboard Utama
    Route::get('/dashboard', function () {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if ($user && $user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user && $user->hasRole('petugas')) {
            return redirect()->route('petugas.dashboard');
        }
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Mengajukan Peminjaman Buku 
    Route::post('/buku/{id}/pinjam', function ($id) {
        $buku = Buku::findOrFail($id);
        $user = Auth::user();

        // Cari atau buat data anggota berdasarkan akun yang login
        $anggota = \App\Models\Anggota::firstOrCreate(
            ['email' => $user->email],
            ['nama' => $user->name, 'no_telepon' => '08123456789']
        );

        // Cegah peminjaman ganda untuk buku yang sama jika belum dikembalikan/ditolak
        $sedangPinjam = \App\Models\Peminjaman::where('anggota_id', $anggota->id)
            ->where('buku_id', $buku->id)
            ->whereNull('tanggal_kembali')
            ->whereNotIn('status', ['ditolak', 'kembali'])
            ->exists();

        if ($sedangPinjam) {
            return redirect()->back()->with('error', "Anda masih memiliki pengajuan atau peminjaman aktif untuk buku '{$buku->judul}' ini. Tidak dapat meminjam buku yang sama sebelum dikembalikan!");
        }

        $dataPinjam = [
            'anggota_id' => $anggota->id,
            'buku_id' => $buku->id,
            'tanggal_pinjam' => date('Y-m-d'),
            'tanggal_kembali' => null,
        ];
        if (\Illuminate\Support\Facades\Schema::hasColumn('peminjamans', 'status')) {
            $dataPinjam['status'] = 'menunggu';
        }
        \App\Models\Peminjaman::create($dataPinjam);

        return redirect()->route('peminjaman.riwayat')->with('success', "Peminjaman buku '{$buku->judul}' berhasil diajukan! Menunggu validasi / persetujuan dari Admin atau Petugas.");
    })->name('buku.pinjam');

    // Riwayat Peminjaman Pribadi
    Route::get('/riwayat-peminjaman/{id?}', function ($id = null) {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // TOLAK AKSES
        if ($id !== null && $user->hasRole('siswa') && $user->id != $id) {
            abort(403, 'Akses ditolak! Anda tidak diperbolehkan melihat riwayat peminjaman siswa lain.');
        }

        // jika admin/petugas melihat id tertentu, tampilkan id itu. Siswa selalu miliknya sendiri.
        $targetUser = ($id !== null && ($user->hasRole('admin') || $user->hasRole('petugas')))
            ? \App\Models\User::findOrFail($id)
            : $user;

        $anggota = \App\Models\Anggota::where('email', $targetUser->email)->first();
        $peminjamans = $anggota
            ? \App\Models\Peminjaman::with('buku')->where('anggota_id', $anggota->id)->latest()->get()
            : collect();

        return view('peminjaman.riwayat', compact('targetUser', 'peminjamans'));
    })->name('peminjaman.riwayat');

    // Khusus Admin spatie
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');

        // Pengelolaan Akun dan Role Pengguna
        Route::get('/admin/users', function () {
            $users = \App\Models\User::with('roles')->get();
            $roles = \Spatie\Permission\Models\Role::all();
            return view('admin.users', compact('users', 'roles'));
        })->name('admin.users.index');

        Route::post('/admin/users/{user}/role', function (\Illuminate\Http\Request $request, \App\Models\User $user) {
            $request->validate([
                'role' => 'required|exists:roles,name',
            ]);
            $user->syncRoles([$request->role]);
            return redirect()->back()->with('success', "Role pengguna {$user->name} berhasil diperbarui menjadi {$request->role}!");
        })->name('admin.users.updateRole');
    });

    // Wewenang Operasional Khusus Admin & Petugas
    Route::middleware(['role:admin|petugas'])->group(function () {
        Route::get('/petugas/dashboard', function () {
            return view('petugas.dashboard');
        })->name('petugas.dashboard');

        // Data Peminjaman & Pengembalian Buku
        Route::get('/peminjaman', function () {
            $peminjamans = \App\Models\Peminjaman::with(['anggota', 'buku'])->latest('id')->get();
            return view('peminjaman.index', compact('peminjamans'));
        })->name('peminjaman.index');

        // Memproses Persetujuan Peminjaman secara Manual 
        Route::post('/peminjaman/{id}/setujui', function ($id) {
            $peminjaman = \App\Models\Peminjaman::findOrFail($id);
            $update = ['tanggal_pinjam' => date('Y-m-d')];
            if (\Illuminate\Support\Facades\Schema::hasColumn('peminjamans', 'status')) {
                $update['status'] = 'dipinjam';
            }
            $peminjaman->update($update);
            return redirect()->back()->with('success', 'Peminjaman buku berhasil divalidasi dan disetujui!');
        })->name('peminjaman.setujui');

        // Menolak Pengajuan Peminjaman
        Route::post('/peminjaman/{id}/tolak', function ($id) {
            $peminjaman = \App\Models\Peminjaman::findOrFail($id);
            if (\Illuminate\Support\Facades\Schema::hasColumn('peminjamans', 'status')) {
                $peminjaman->update(['status' => 'ditolak']);
            }
            return redirect()->back()->with('success', 'Pengajuan peminjaman telah ditolak.');
        })->name('peminjaman.tolak');

        // Mencatat Pengembalian Buku 
        Route::post('/peminjaman/{id}/kembalikan', function ($id) {
            $peminjaman = \App\Models\Peminjaman::findOrFail($id);
            $update = ['tanggal_kembali' => date('Y-m-d')];
            if (\Illuminate\Support\Facades\Schema::hasColumn('peminjamans', 'status')) {
                $update['status'] = 'kembali';
            }
            $peminjaman->update($update);
            return redirect()->back()->with('success', 'Buku berhasil dicatat telah dikembalikan!');
        })->name('peminjaman.kembalikan');

        // Laporan Perpustakaan 
        Route::get('/laporan', function () {
            $totalBuku = Buku::count();
            $totalPeminjaman = \App\Models\Peminjaman::count();
            $hasStatus = \Illuminate\Support\Facades\Schema::hasColumn('peminjamans', 'status');

            $totalMenunggu = $hasStatus ? \App\Models\Peminjaman::where('status', 'menunggu')->count() : 0;
            $totalDipinjam = $hasStatus
                ? \App\Models\Peminjaman::where('status', 'dipinjam')->count()
                : \App\Models\Peminjaman::whereNull('tanggal_kembali')->count();
            $totalDikembalikan = $hasStatus
                ? \App\Models\Peminjaman::where('status', 'kembali')->count()
                : \App\Models\Peminjaman::whereNotNull('tanggal_kembali')->count();

            $rekapKategori = Kategori::withCount('bukus')->get();
            $rekapBuku = Buku::withCount('peminjamans')->get();

            return view('laporan.index', compact(
                'totalBuku',
                'totalPeminjaman',
                'totalMenunggu',
                'totalDipinjam',
                'totalDikembalikan',
                'rekapKategori',
                'rekapBuku'
            ));
        })->name('laporan.index');
    });
});

require __DIR__ . '/auth.php';