<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\Petugas\DashboardController as PetugasDashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Modul 5: Autentikasi & Role-Based Access Control (RBAC)
|--------------------------------------------------------------------------
*/

// =========================================================================
// 1. ROUTE PUBLIK (Dapat diakses oleh siapa saja / Tamu & Pengguna Login)
// =========================================================================
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
Route::get('/buku/{id}', [BukuController::class, 'show'])->name('buku.show');

// Route Autentikasi Laravel Breeze (Login, Register, Password Reset)
require __DIR__.'/auth.php';

// =========================================================================
// 2. ROUTE TERPROTEKSI AUTENTIKASI (Middleware: auth)
// Skenario 1 & 15: Pengguna yang belum login / setelah logout akan dialihkan ke login
// =========================================================================
Route::middleware(['auth'])->group(function () {

    /**
     * Dashboard Dispatcher:
     * - Admin diarahkan ke dashboard admin
     * - Petugas diarahkan ke dashboard petugas
     * - Siswa membuka dashboard siswa
     */
    Route::get('/dashboard', function () {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('petugas')) {
            return redirect()->route('petugas.dashboard');
        }

        return view('dashboard');
    })->name('dashboard');

    // Manajemen Profil Pengguna (Skenario 11, 15, 16: Semua role berhak mengubah profil milik sendiri)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Detail Peminjaman (Dengan Proteksi Kepemilikan Data - Skenario 14)
    Route::get('/peminjaman/{peminjaman}', [PeminjamanController::class, 'show'])->name('peminjaman.show');

    // =====================================================================
    // 3. FITUR KHUSUS SISWA (Middleware: role:siswa)
    // Skenario 10, 11, 12, 14
    // =====================================================================
    Route::middleware(['role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
        // Mengajukan peminjaman buku
        Route::post('/buku/{id}/pinjam', [PeminjamanController::class, 'ajukanPinjam'])->name('pinjam.ajukan');

        // Melihat status & riwayat peminjaman sendiri
        Route::get('/riwayat-pinjam', [PeminjamanController::class, 'riwayatSaya'])->name('peminjaman.riwayat');
    });

    // =====================================================================
    // 4. FITUR OPERASIONAL BERSAMA (Admin & Petugas)
    // Middleware: role:admin|petugas
    // Skenario 3, 5, 7, 8, 13
    // =====================================================================
    Route::middleware(['role:admin|petugas'])->group(function () {
        // Halaman Pengelolaan Buku (Siswa yang mencoba membuka route ini akan ditolak 403 - Skenario 13)
        Route::get('/kelola-buku', function () {
            return redirect()->route('buku.index');
        })->name('buku.kelola');

        // Kelola Sirkulasi Peminjaman & Pengembalian
        Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
        Route::patch('/peminjaman/{peminjaman}/setujui', [PeminjamanController::class, 'setujui'])->name('peminjaman.setujui');
        Route::patch('/peminjaman/{peminjaman}/tolak', [PeminjamanController::class, 'tolak'])->name('peminjaman.tolak');
        Route::patch('/peminjaman/{peminjaman}/kembalikan', [PeminjamanController::class, 'kembalikan'])->name('peminjaman.kembalikan');

        // Laporan Perpustakaan
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    });

    // =====================================================================
    // 5. FITUR KHUSUS PETUGAS (Middleware: role:petugas)
    // Skenario 6, 7, 8
    // =====================================================================
    Route::middleware(['role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
        Route::get('/dashboard', [PetugasDashboardController::class, 'index'])->name('dashboard');
    });

    // =====================================================================
    // 6. FITUR KHUSUS ADMINISTRATOR (Middleware: role:admin)
    // Skenario 2, 4, 9, 13 (Petugas/Siswa yang mencoba akses ditolak 403 Forbidden)
    // =====================================================================
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Kelola Akun dan Penetapan Role Pengguna (Skenario 4 & 9)
        Route::resource('users', AdminUserController::class);
    });
});
