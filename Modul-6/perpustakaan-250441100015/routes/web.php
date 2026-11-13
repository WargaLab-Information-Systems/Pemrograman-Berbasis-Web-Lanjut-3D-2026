<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BukuController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\RiwayatPeminjamanController;
use App\Http\Controllers\ManajemenPeminjamanController;
use App\Http\Controllers\LaporanPerpustakaanController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {
    $user = request()->user();
    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }
    if ($user->hasRole('petugas')) {
        return redirect()->route('petugas.dashboard');
    }
    if ($user->hasRole('siswa')) {
        return redirect()->route('siswa.dashboard');
    }
    abort(403, 'Anda tidak memiliki akses dashboard.');
})->middleware('auth')->name('dashboard');

Route::middleware(['auth', 'permission:view books'])->group(function () {
    Route::get('/buku', [BukuController::class, 'index'])
        ->name('buku.index');
});

Route::middleware(['auth', 'can:kelola-buku'])->group(function () {
    Route::get('/buku/create', [BukuController::class, 'create'])->name('buku.create');
    Route::post('/buku', [BukuController::class, 'store'])->name('buku.store');
    Route::delete('/buku/{buku}', [BukuController::class, 'destroy'])->name('buku.destroy');
});

Route::middleware(['auth', 'permission:view books'])->group(function () {
    Route::get('/buku/{id}', [BukuController::class, 'show'])
        ->name('buku.show');
});

// modul 6
Route::middleware('auth')->group(function () {
    Route::get('/buku-cari', [BukuController::class, 'cari'])->name('buku.cari');
});
// hapus route, route jebakan buat hapus data buku
// Route::middleware('auth')->group(function () {
//     Route::get('/buku/{buku}/hapus', [BukuController::class, 'destroy'])->name('buku.hapus.get');
// });
// menggunakan route ini
Route::middleware('auth')->group(function () {
    Route::delete('/buku/{buku}', [BukuController::class, 'destroy'])->name('buku.destroy');
});
// dengan menggunakan route delete
Route::middleware('auth')->group(function () {
    Route::get('/peminjaman/{id}', [PeminjamanController::class, 'show'])->name('peminjaman.show');
});
// Route pengelolaan buku diproteksi oleh Gate 'kelola-buku'
Route::middleware(['auth', 'can:kelola-buku'])->group(function () {
    Route::get('/buku/create', [BukuController::class, 'create'])->name('buku.create');
    Route::post('/buku', [BukuController::class, 'store'])->name('buku.store');
    Route::delete('/buku/{buku}', [BukuController::class, 'destroy'])->name('buku.destroy');
});
Route::get('/test-error', function () {
    throw new \RuntimeException('Uji coba error keamanan');
});
// end modul 6
// modul 1
// Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
// Route::get('/buku/{id}', [BukuController::class, 'show'])->name('buku.show');

// role buat admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/users', [UserManagementController::class, 'index'])
        ->name('admin.users.index');

    Route::put('/admin/users/{user}/role', [UserManagementController::class, 'updateRole'])
        ->name('admin.users.updateRole');
});
// end role buat admin
Route::middleware([
    'auth',
    'permission:manage loans',
])->group(function () {
    Route::get('/manajemen-peminjaman', [
        ManajemenPeminjamanController::class,
        'index'
    ])->name('peminjaman.index');

    Route::patch('/manajemen-peminjaman/{peminjaman}/setujui', [
        ManajemenPeminjamanController::class,
        'approve'
    ])->name('peminjaman.approve');

    Route::patch('/manajemen-peminjaman/{peminjaman}/tolak', [
        ManajemenPeminjamanController::class,
        'reject'
    ])->name('peminjaman.reject');
});
Route::middleware([
    'auth',
    'permission:manage returns',
])->group(function () {
    Route::patch(
        '/manajemen-peminjaman/{peminjaman}/kembalikan',
        [ManajemenPeminjamanController::class, 'recordReturn']
    )->name('peminjaman.return');
});
Route::middleware([
    'auth',
    'permission:view library reports',
])->group(function () {
    Route::get('/laporan-perpustakaan', [
        LaporanPerpustakaanController::class,
        'index',
    ])->name('laporan-perpustakaan.index');
});
// role buat petugas
Route::middleware(['auth', 'role:petugas'])->group(function () {
    Route::get('/petugas/dashboard', function () {
        return view('petugas.dashboard');
    })->name('petugas.dashboard');
});
// end role buat petugas

// role buat siswa
Route::middleware(['auth', 'role:siswa'])->group(function () {
    Route::get('/siswa/dashboard', function () {
        return view('siswa.dashboard');
    })->name('siswa.dashboard');
});
Route::middleware(['auth', 'role:siswa'])->group(function () {
    Route::post('/peminjaman/{buku}', [PeminjamanController::class, 'store'])
        ->name('peminjaman.store');
});
Route::middleware(['auth', 'role:siswa'])->group(function () {
    Route::get('/siswa/riwayat-peminjaman', [
        RiwayatPeminjamanController::class,
        'index'
    ])->middleware('permission:view own loan history')
        ->name('siswa.riwayat-peminjaman');
});
// end role buat siswa

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
