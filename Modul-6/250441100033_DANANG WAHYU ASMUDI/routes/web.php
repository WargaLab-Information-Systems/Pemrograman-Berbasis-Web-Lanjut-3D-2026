<?php

use App\Models\Kategori;
use App\Models\Buku;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('home');
})->name('home');


Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
Route::get('/buku/{id}', [BukuController::class, 'show'])->name('buku.show')->where('id', '[0-9]+');

Route::middleware(['auth'])->group(function () {
    
    // Dashboard Utama
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

    
    // =========================================================================
    // SOAL 1 (A2) & SOAL 2 (b): Rute /buku-cari di balik auth
    // =========================================================================
    Route::get('/buku-cari', [BukuController::class, 'cari'])->name('buku.cari');

    // =========================================================================
    // SOAL 3: Rute Pengelolaan Buku dengan Gate 'can:kelola-buku'
    // Menggantikan pemeriksaan role sebelumnya, hanya Admin & Petugas yang diizinkan.
    // Jika Siswa mengetik URL /buku/create langsung -> Respons 403 Forbidden!
    // =========================================================================
    Route::middleware(['can:kelola-buku'])->group(function () {
        Route::get('/buku/create', [BukuController::class, 'create'])->name('buku.create');
        Route::post('/buku', [BukuController::class, 'store'])->name('buku.store');
        Route::get('/buku/{id}/edit', [BukuController::class, 'edit'])->name('buku.edit');
        Route::put('/buku/{id}', [BukuController::class, 'update'])->name('buku.update');

        // SOAL 2 (c): Penghapusan buku wajib lewat method DELETE (CSRF protected)
        Route::delete('/buku/{id}', [BukuController::class, 'destroy'])->name('buku.destroy');
    });

    // =========================================================================
    // SOAL 4: Rute GET /peminjaman/{id} memanggil method show pada PeminjamanController
    // Celah A4 (IDOR) ditutup dengan otorisasi Policy (PeminjamanPolicy@view)
    // Siswa pemilik -> 200, Siswa lain -> 403 Forbidden, Petugas/Admin -> 200
    // =========================================================================
    Route::get('/peminjaman/{id}', [PeminjamanController::class, 'show'])
        ->name('peminjaman.detail')
        ->where('id', '[0-9]+');

    // Mengajukan Peminjaman Buku
    Route::post('/buku/{id}/pinjam', function ($id) {
        $buku = Buku::findOrFail($id);
        $user = Auth::user();

        $anggota = \App\Models\Anggota::firstOrCreate(
            ['email' => $user->email],
            ['nama' => $user->name, 'no_telepon' => '08123456789']
        );

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

        return redirect()->to('/riwayat-peminjaman/' . $user->id)->with('success', "Peminjaman buku '{$buku->judul}' berhasil diajukan! Menunggu validasi / persetujuan dari Admin atau Petugas.");
    })->name('buku.pinjam');

    // Riwayat Peminjaman (URL otomatis membawa ID user, contoh: /riwayat-peminjaman/8)
    // Sesuai SOAL 1 (A4) [RENTAN IDOR]: Tanpa pemeriksaan apakah {id} milik user login atau siswa lain
    Route::get('/riwayat-peminjaman/{id?}', function ($id = null) {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Jika URL dibuka tanpa ID, otomatis redirect membawa ID user yang sedang login
        if ($id === null) {
            return redirect()->to('/riwayat-peminjaman/' . $user->id);
        }

        // Ambil data user target berdasarkan ID di URL
        $targetUser = \App\Models\User::findOrFail($id);

        $anggota = \App\Models\Anggota::where('email', $targetUser->email)->first();
        $peminjamans = $anggota
            ? \App\Models\Peminjaman::with('buku')->where('anggota_id', $anggota->id)->latest()->get()
            : collect();

        return view('peminjaman.riwayat', compact('targetUser', 'peminjamans'));
    })->name('peminjaman.riwayat');

    // Khusus Admin
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');

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

        Route::get('/peminjaman', function () {
            $peminjamans = \App\Models\Peminjaman::with(['anggota', 'buku'])->latest('id')->get();
            return view('peminjaman.index', compact('peminjamans'));
        })->name('peminjaman.index');

        Route::post('/peminjaman/{id}/setujui', function ($id) {
            $peminjaman = \App\Models\Peminjaman::findOrFail($id);
            $update = ['tanggal_pinjam' => date('Y-m-d')];
            if (\Illuminate\Support\Facades\Schema::hasColumn('peminjamans', 'status')) {
                $update['status'] = 'dipinjam';
            }
            $peminjaman->update($update);
            return redirect()->back()->with('success', 'Peminjaman buku berhasil divalidasi dan disetujui!');
        })->name('peminjaman.setujui');

        Route::post('/peminjaman/{id}/tolak', function ($id) {
            $peminjaman = \App\Models\Peminjaman::findOrFail($id);
            if (\Illuminate\Support\Facades\Schema::hasColumn('peminjamans', 'status')) {
                $peminjaman->update(['status' => 'ditolak']);
            }
            return redirect()->back()->with('success', 'Pengajuan peminjaman telah ditolak.');
        })->name('peminjaman.tolak');

        Route::post('/peminjaman/{id}/kembalikan', function ($id) {
            $peminjaman = \App\Models\Peminjaman::findOrFail($id);
            $update = ['tanggal_kembali' => date('Y-m-d')];
            if (\Illuminate\Support\Facades\Schema::hasColumn('peminjamans', 'status')) {
                $update['status'] = 'kembali';
            }
            $peminjaman->update($update);
            return redirect()->back()->with('success', 'Buku berhasil dicatat telah dikembalikan!');
        })->name('peminjaman.kembalikan');

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