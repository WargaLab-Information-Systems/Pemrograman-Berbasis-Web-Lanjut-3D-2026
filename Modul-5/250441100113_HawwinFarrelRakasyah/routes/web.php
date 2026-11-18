<?php

use Illuminate\Support\Facades\Route;
use App\Models\Kategori;
use App\Models\Buku;
use App\Http\Controllers\PerpustakaanAccessController;

// 1. Rute Beranda
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

// 3. Grup Rute Terproteksi Sesi Web
Route::middleware(['auth:web'])->group(function () {

    // A. Dashboard
    Route::get('/siswa/dashboard', [PerpustakaanAccessController::class, 'dashboardSiswa'])
        ->middleware('role:siswa')
        ->name('siswa.dashboard');

    Route::get('/admin/dashboard', [PerpustakaanAccessController::class, 'dashboardAdmin'])
        ->middleware('role_or_permission:akses dashboard admin|admin')
        ->name('admin.dashboard');

    Route::get('/petugas/dashboard', [PerpustakaanAccessController::class, 'dashboardPetugas'])
        ->middleware('role_or_permission:akses dashboard petugas|petugas')
        ->name('petugas.dashboard');

    // B. Katalog dan Detail Buku
    Route::get('/buku', function () {
        $kategoris = Kategori::all();
        return view('buku.index', compact('kategoris'));
    })->middleware('role_or_permission:lihat daftar buku|siswa|petugas|admin')->name('buku.index');

    Route::get('/buku/{id}', function ($id) {
        $buku = Buku::with('kategori')->findOrFail($id);
        return view('buku.show', compact('buku'));
    })->middleware('role_or_permission:lihat daftar buku|siswa|petugas|admin')->name('buku.show');

    // C. Pengajuan Peminjaman Buku (Siswa, Petugas, Admin)
    Route::post('/peminjaman/ajukan', [PerpustakaanAccessController::class, 'ajukanPeminjaman'])
        ->middleware('permission:mengajukan peminjaman buku')
        ->name('peminjaman.ajukan');

    // D. Sirkulasi Peminjaman & Pengembalian (Admin & Petugas)
    Route::get('/peminjaman', [PerpustakaanAccessController::class, 'kelolaPeminjaman'])
        ->middleware('role_or_permission:kelola peminjaman|admin|petugas')
        ->name('peminjaman.index');

    Route::patch('/peminjaman/{id}/setujui', [PerpustakaanAccessController::class, 'setujuiPeminjaman'])
        ->middleware('permission:memproses persetujuan peminjaman')
        ->name('peminjaman.setujui');

    Route::patch('/peminjaman/{id}/tolak', [PerpustakaanAccessController::class, 'tolakPeminjaman'])
        ->middleware('permission:memproses persetujuan peminjaman')
        ->name('peminjaman.tolak');

    Route::patch('/peminjaman/{id}/kembalikan', [PerpustakaanAccessController::class, 'kembalikanBuku'])
        ->middleware('permission:mencatat pengembalian buku')
        ->name('peminjaman.kembalikan');

    // E. Manajemen Pengguna & Penetapan Role (Khusus Admin)
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

    // F. Laporan Perpustakaan (Admin & Petugas)
    Route::get('/laporan', [PerpustakaanAccessController::class, 'laporanPerpustakaan'])
        ->middleware('role_or_permission:lihat laporan perpustakaan|admin|petugas')
        ->name('laporan.index');

    // G. Riwayat & Status Peminjaman Sendiri (Pemeriksaan Ownership)
    Route::get('/riwayat-peminjaman/{userId?}', [PerpustakaanAccessController::class, 'riwayatSendiri'])
        ->middleware('role_or_permission:melihat riwayat peminjaman sendiri|siswa|admin|petugas')
        ->name('peminjaman.riwayat.sendiri');

    // H. Profil Sendiri (Pemeriksaan Ownership)
    Route::get('/profile/{userId?}', [PerpustakaanAccessController::class, 'profilSendiri'])
        ->middleware('role_or_permission:lihat profil sendiri|siswa|admin|petugas')
        ->name('profile.edit');

    Route::patch('/profile', [PerpustakaanAccessController::class, 'updateProfilSendiri'])
        ->middleware('role_or_permission:ubah profil sendiri|siswa|admin|petugas')
        ->name('profile.update');
});

require __DIR__.'/auth.php';