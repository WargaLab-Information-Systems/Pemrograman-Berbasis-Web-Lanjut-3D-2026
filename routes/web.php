<?php

use Illuminate\Support\Facades\Route;
use App\Models\Kategori;
use App\Models\Buku;
use App\Http\Controllers\PerpustakaanAccessController;

Route::get('/', function () {
    return auth('web')->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::get('/dashboard', function () {
    $user = auth('web')->user();

    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->hasRole('petugas')) {
        return redirect()->route('petugas.dashboard');
    }

    return redirect()->route('siswa.dashboard');
})->middleware(['auth:web'])->name('dashboard');

Route::middleware(['auth:web'])->group(function () {

    // A. Dashboard Masing-Masing Role (Bawaan Modul 5)
    Route::get('/admin/dashboard', [PerpustakaanAccessController::class, 'dashboardAdmin'])
        ->middleware('role_or_permission:akses dashboard admin|admin')
        ->name('admin.dashboard');

    Route::get('/petugas/dashboard', [PerpustakaanAccessController::class, 'dashboardPetugas'])
        ->middleware('role_or_permission:akses dashboard petugas|petugas')
        ->name('petugas.dashboard');

    Route::get('/siswa/dashboard', [PerpustakaanAccessController::class, 'dashboardSiswa'])
        ->middleware('role:siswa')
        ->name('siswa.dashboard');

    // B. Katalog dan Pengelolaan Buku dengan Gate kelola-buku (Soal 3)
    Route::middleware('can:kelola-buku')->group(function () {
        Route::get('/buku/create', [PerpustakaanAccessController::class, 'createBuku'])->name('buku.create');
        Route::post('/buku', [PerpustakaanAccessController::class, 'storeBuku'])->name('buku.store');
        Route::get('/buku/{buku}/edit', [PerpustakaanAccessController::class, 'editBuku'])->name('buku.edit');
        Route::put('/buku/{buku}', [PerpustakaanAccessController::class, 'updateBuku'])->name('buku.update');
        Route::delete('/buku/{buku}', [PerpustakaanAccessController::class, 'destroy'])->name('buku.destroy');
    });

    Route::get('/buku', function () {
        $kategoris = Kategori::all();
        $bukus = Buku::with('kategori')->latest()->get();
        return view('buku.index', compact('kategoris', 'bukus'));
    })->middleware('role_or_permission:lihat daftar buku|siswa|petugas|admin')
      ->name('buku.index');

    Route::get('/buku-cari', [PerpustakaanAccessController::class, 'cariBuku'])
        ->name('buku.cari');

    Route::get('/buku/{id}', function ($id) {
        $buku = Buku::with('kategori')->findOrFail($id);
        return view('buku.show', compact('buku'));
    })->middleware('role_or_permission:lihat daftar buku|siswa|petugas|admin')
      ->name('buku.show');

    // C. Pengajuan Peminjaman Buku (Bawaan Modul 5)
    Route::post('/peminjaman/ajukan', [PerpustakaanAccessController::class, 'ajukanPeminjaman'])
        ->middleware('permission:mengajukan peminjaman buku')
        ->name('peminjaman.ajukan');

    // D. Sirkulasi Peminjaman & Pengembalian
    Route::get('/peminjaman', [PerpustakaanAccessController::class, 'kelolaPeminjaman'])
        ->middleware('role_or_permission:kelola peminjaman|admin|petugas')
        ->name('peminjaman.index');

    // [SOAL 1 - CELAH A4] Route detail peminjaman tanpa cek pemilik (rentan IDOR)
    Route::get('/peminjaman/{id}', [PerpustakaanAccessController::class, 'show'])
        ->name('peminjaman.show');

    Route::patch('/peminjaman/{id}/setujui', [PerpustakaanAccessController::class, 'setujuiPeminjaman'])
        ->middleware('permission:memproses persetujuan peminjaman')
        ->name('peminjaman.setujui');

    Route::patch('/peminjaman/{id}/tolak', [PerpustakaanAccessController::class, 'tolakPeminjaman'])
        ->middleware('permission:memproses persetujuan peminjaman')
        ->name('peminjaman.tolak');

    Route::patch('/peminjaman/{id}/kembalikan', [PerpustakaanAccessController::class, 'kembalikanBuku'])
        ->middleware('permission:mencatat pengembalian buku')
        ->name('peminjaman.kembalikan');

    // E. Manajemen Pengguna (Bawaan Modul 5)
    Route::get('/admin/users', [PerpustakaanAccessController::class, 'indexUsers'])
        ->middleware('role_or_permission:kelola data pengguna|admin')
        ->name('admin.users');

    Route::post('/admin/users', [PerpustakaanAccessController::class, 'storeUser'])
        ->middleware('role_or_permission:kelola data pengguna|admin')
        ->name('admin.users.store');

    Route::put('/admin/users/{user}/role', [PerpustakaanAccessController::class, 'updateUserRole'])
        ->middleware('role_or_permission:kelola role pengguna|admin')
        ->name('admin.users.role');

    Route::delete('/admin/users/{user}', [PerpustakaanAccessController::class, 'destroyUser'])
        ->middleware('role_or_permission:kelola data pengguna|admin')
        ->name('admin.users.destroy');

    // F. Laporan, Riwayat Sendiri, dan Profil Sendiri (Bawaan Modul 5)
    Route::get('/laporan', [PerpustakaanAccessController::class, 'laporanPerpustakaan'])
        ->middleware('role_or_permission:lihat laporan perpustakaan|admin|petugas')
        ->name('laporan.index');

    Route::get('/riwayat-peminjaman', [PerpustakaanAccessController::class, 'riwayatSendiri'])
        ->name('peminjaman.riwayat.sendiri');

    Route::get('/profile/{userId?}', [PerpustakaanAccessController::class, 'profilSendiri'])
        ->name('profile.edit');

    Route::patch('/profile', [PerpustakaanAccessController::class, 'updateProfilSendiri'])
        ->name('profile.update');
});

require __DIR__.'/auth.php';