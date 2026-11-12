<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\BukuCariController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RiwayatController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| middleware 'auth'        : hanya untuk pengguna yang sudah login
| middleware 'permission'  : pembatasan hak akses (Spatie Laravel Permission)
| middleware 'guest'       : ada di routes/auth.php (login & registrasi)
*/

// Halaman publik (Modul 1)
Route::get('/', function () {
    return view('home');
})->name('home');

Route::middleware('auth')->group(function () {

    // /dashboard: mengarahkan pengguna ke halaman yang sesuai rolenya
    Route::get('/dashboard', [DashboardController::class, 'arahkan'])->name('dashboard');

    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
        ->middleware('permission:akses dashboard admin')
        ->name('admin.dashboard');

    Route::get('/petugas/dashboard', [DashboardController::class, 'petugas'])
        ->middleware('permission:akses dashboard petugas')
        ->name('petugas.dashboard');

    /**
     * Soal 3: /buku/create dijaga Gate "kelola-buku", BUKAN middleware
     * permission seperti route lain. Form tambah buku yang sesungguhnya
     * ada inline di halaman /buku (lihat @can('kelola-buku') di
     * index.blade.php); route ini membuat URL /buku/create ikut diperiksa
     * Gate yang sama ketika diketik langsung oleh siswa (403 Forbidden),
     * lalu mengarahkan admin/petugas kembali ke /buku.
     *
     * PENTING: route ini HARUS didaftarkan SEBELUM /buku/{id} -- Laravel
     * mencocokkan route sesuai URUTAN DIDAFTARKAN (bukan urutan group),
     * dan /buku/{id} akan menganggap "create" sebagai nilai {id} kalau
     * didaftarkan lebih dulu, sehingga /buku/create tidak akan pernah
     * tercapai.
     */
    Route::get('/buku/create', fn () => redirect()->route('buku.index'))
        ->middleware('can:kelola-buku')
        ->name('buku.create');

    // Buku (Modul 1-4)
    Route::middleware('permission:lihat daftar buku')->group(function () {
        Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
        Route::get('/buku/{id}', [BukuController::class, 'show'])->name('buku.show');
    });

    /**
     * SOAL 2 (b): route TIDAK berubah -- perbaikannya ada di
     * BukuCariController (parameter binding), bukan di sini.
     */
    Route::get('/buku-cari', [BukuCariController::class, 'cari'])->name('buku.cari');

    /**
     * SOAL 2 (c): route GET /buku/{id}/hapus milik Soal 1 (A3) DIHAPUS dari
     * sini. Penghapusan buku satu-satunya sekarang lewat tombol Hapus yang
     * sudah ada sejak Modul 4/5 (DELETE /api/buku/{id} via Fetch, dengan
     * header X-CSRF-TOKEN yang divalidasi middleware VerifyCsrfToken) --
     * setara dengan <form> ber-@csrf dan @method('DELETE'): sama-sama
     * memakai verb DELETE dan sama-sama membawa token CSRF yang diperiksa.
     * Tautan di jebakan.html yang masih menunjuk ke /buku/{id}/hapus kini
     * menghasilkan 404 (route tidak ada), dan Postman yang mengirim DELETE
     * ke /api/buku/{id} tanpa token menghasilkan 419.
     */

    // Kelola pengguna dan role (khusus admin)
    Route::middleware('permission:kelola data pengguna')
        ->prefix('admin/users')
        ->name('admin.users.')
        ->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [UserController::class, 'update'])->name('update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        });

    // Peminjaman: pengajuan oleh pengguna
    Route::post('/buku/{id}/ajukan', [PeminjamanController::class, 'ajukan'])
        ->middleware('permission:mengajukan peminjaman buku')
        ->name('peminjaman.ajukan');

    // Peminjaman: dikelola admin/petugas
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])
        ->middleware('permission:kelola peminjaman')
        ->name('peminjaman.index');

    /**
     * SOAL 4: parameter route diubah dari {id} menjadi {peminjaman} supaya
     * cocok dengan implicit model binding di PeminjamanController@show.
     * Pemeriksaan kepemilikan dilakukan DI DALAM controller lewat
     * Gate::authorize + PeminjamanPolicy, bukan di sini, karena otorisasi
     * ini butuh membandingkan data pemilik peminjaman (bukan sekadar
     * role/permission).
     */
    Route::get('/peminjaman/{peminjaman}', [PeminjamanController::class, 'show'])->name('peminjaman.show');

    Route::post('/peminjaman/{peminjaman}/setujui', [PeminjamanController::class, 'setujui'])
        ->middleware('permission:memproses persetujuan peminjaman')
        ->name('peminjaman.setujui');

    Route::post('/peminjaman/{peminjaman}/tolak', [PeminjamanController::class, 'tolak'])
        ->middleware('permission:memproses persetujuan peminjaman')
        ->name('peminjaman.tolak');

    // Pengembalian
    Route::get('/pengembalian', [PeminjamanController::class, 'pengembalian'])
        ->middleware('permission:kelola pengembalian')
        ->name('pengembalian.index');

    Route::post('/peminjaman/{peminjaman}/kembali', [PeminjamanController::class, 'kembali'])
        ->middleware('permission:mencatat pengembalian buku')
        ->name('peminjaman.kembali');

    // Laporan
    Route::get('/laporan', LaporanController::class)
        ->middleware('permission:lihat laporan perpustakaan')
        ->name('laporan.index');

    // Status & riwayat peminjaman. Selain permission, akses dicek terhadap
    // identitas pengguna (UserPolicy@viewRiwayat) di RiwayatController.
    Route::get('/riwayat/{user}', [RiwayatController::class, 'show'])
        ->middleware('permission:melihat status peminjaman sendiri|melihat riwayat peminjaman sendiri')
        ->name('riwayat.show');

    // Profil sendiri (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->middleware('permission:lihat profil sendiri')
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->middleware('permission:ubah profil sendiri')
        ->name('profile.update');
});

// Route login, registrasi, logout, dan reset password dari Laravel Breeze
require __DIR__.'/auth.php';


