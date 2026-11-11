<?php

use App\Http\Controllers\Admin\BukuController as AdminBukuController;
use App\Http\Controllers\Admin\PeminjamanController as AdminPeminjamanController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\Petugas\BukuController as PetugasBukuController;
use App\Http\Controllers\Petugas\PeminjamanController as PetugasPeminjamanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Siswa\BukuController as SiswaBukuController;
use App\Http\Controllers\Siswa\PeminjamanController as SiswaPeminjamanController;
use Illuminate\Support\Facades\Route;
use App\Models\Buku;
use Illuminate\Http\Request;


Route::get('/', function () {
    return redirect()->route('login');
});

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

    abort(403);
})->middleware('auth')->name('dashboard');

Route::post('/buku/{buku}/peminjaman', [PeminjamanController::class, 'store'])
    ->middleware(['auth', 'role:admin|petugas|siswa', 'permission:loans.request'])
    ->name('peminjaman.request');

Route::get('/buku-cari', [BukuController::class, 'cari'])
    ->middleware('auth')
    ->name('buku.cari');

Route::get('/buku/{buku}/hapus', function (Request $request, Buku $buku) {
    if ($request->user()->hasRole('petugas')) {
        return app(PetugasBukuController::class)->destroy((string) $buku->id);
    }

    return app(AdminBukuController::class)->destroy((string) $buku->id);
    })->middleware(['auth', 'role:admin|petugas', 'permission:books.manage'])
    ->name('buku.hapus');

Route::get('/peminjaman/{id}', [PeminjamanController::class, 'show'])
    ->middleware('auth')
    ->name('peminjaman.show');


Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->middleware('permission:dashboard.admin')->name('dashboard');

        Route::get('/buku', [AdminBukuController::class, 'index'])
            ->middleware('permission:books.view')
            ->name('buku.index');

        Route::middleware('permission:books.manage')->group(function () {

            Route::get('/buku/create', [AdminBukuController::class, 'create'])
                ->name('buku.create');

            Route::post('/buku', [AdminBukuController::class, 'store'])
                ->name('buku.store');

            Route::get('/buku/{id}/edit', [AdminBukuController::class, 'edit'])
                ->name('buku.edit');

            Route::put('/buku/{id}', [AdminBukuController::class, 'update'])
                ->name('buku.update');

            Route::delete('/buku/{id}', [AdminBukuController::class, 'destroy'])
                ->name('buku.destroy');
        });

        Route::middleware('permission:users.manage')->group(function () {

            Route::get('/users', [UserController::class, 'index'])
                ->name('users.index');

            Route::get('/users/create', [UserController::class, 'create'])
                ->name('users.create');

            Route::post('/users', [UserController::class, 'store'])
                ->middleware('permission:users.assign-roles')
                ->name('users.store');

            Route::get('/users/{user}/edit', [UserController::class, 'edit'])
                ->name('users.edit');

            Route::put('/users/{user}', [UserController::class, 'update'])
                ->middleware('permission:users.assign-roles')
                ->name('users.update');

            Route::delete('/users/{user}', [UserController::class, 'destroy'])
                ->name('users.destroy');
        });

        Route::get('/peminjaman/create', [AdminPeminjamanController::class, 'create'])
            ->middleware('permission:loans.manage')
            ->name('peminjaman.create');

        Route::post('/peminjaman', [AdminPeminjamanController::class, 'store'])
            ->middleware('permission:loans.manage')
            ->name('peminjaman.store');

        Route::post('/peminjaman/{peminjaman}/kembalikan', [AdminPeminjamanController::class, 'kembalikan'])
            ->middleware('permission:returns.record')
            ->name('peminjaman.kembalikan');

        Route::post('/peminjaman/{peminjaman}/proses/{status}', [AdminPeminjamanController::class, 'proses'])
            ->middleware('permission:loans.approve')
            ->name('peminjaman.proses');

        Route::get('/peminjaman', [AdminPeminjamanController::class, 'index'])
            ->middleware('permission:loans.manage')
            ->name('peminjaman.index');

        Route::get('/peminjaman-saya', [PeminjamanController::class, 'riwayat'])
            ->middleware(['permission:loans.view-own', 'permission:loans.history-own'])
            ->name('peminjaman.saya');

        Route::get('/laporan', [LaporanController::class, 'index'])
            ->middleware('permission:reports.view')
            ->name('laporan.index');
    });

Route::middleware(['auth', 'role:petugas'])
    ->prefix('petugas')
    ->name('petugas.')
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('petugas.dashboard');
        })->middleware('permission:dashboard.petugas')->name('dashboard');

        Route::get('/buku', [PetugasBukuController::class, 'index'])
            ->middleware('permission:books.view')
            ->name('buku.index');

        Route::middleware('permission:books.manage')->group(function () {

            Route::get('/buku/create', [PetugasBukuController::class, 'create'])
                ->name('buku.create');

            Route::post('/buku', [PetugasBukuController::class, 'store'])
                ->name('buku.store');

            Route::get('/buku/{id}/edit', [PetugasBukuController::class, 'edit'])
                ->name('buku.edit');

            Route::put('/buku/{id}', [PetugasBukuController::class, 'update'])
                ->name('buku.update');

            Route::delete('/buku/{id}', [PetugasBukuController::class, 'destroy'])
                ->name('buku.destroy');
        });

        Route::get('/peminjaman/create', [PetugasPeminjamanController::class, 'create'])
            ->middleware('permission:loans.manage')
            ->name('peminjaman.create');

        Route::post('/peminjaman', [PetugasPeminjamanController::class, 'store'])
            ->middleware('permission:loans.manage')
            ->name('peminjaman.store');

        Route::post('/peminjaman/{peminjaman}/kembalikan', [PetugasPeminjamanController::class, 'kembalikan'])
            ->middleware('permission:returns.record')
            ->name('peminjaman.kembalikan');

        Route::post('/peminjaman/{peminjaman}/proses/{status}', [PetugasPeminjamanController::class, 'proses'])
            ->middleware('permission:loans.approve')
            ->name('peminjaman.proses');

        Route::get('/peminjaman', [PetugasPeminjamanController::class, 'index'])
            ->middleware('permission:loans.manage')
            ->name('peminjaman.index');

        Route::get('/peminjaman-saya', [PeminjamanController::class, 'riwayat'])
            ->middleware(['permission:loans.view-own', 'permission:loans.history-own'])
            ->name('peminjaman.saya');

        Route::get('/laporan', [LaporanController::class, 'index'])
            ->middleware('permission:reports.view')
            ->name('laporan.index');
    });

Route::middleware(['auth', 'role:siswa'])
    ->prefix('siswa')
    ->name('siswa.')
    ->group(function () {

        Route::get('/dashboard', function () {
            $user = request()->user();

            $borrowedBooksCount = $user->peminjamans()
                ->whereIn('status', ['disetujui', 'dipinjam'])
                ->whereNull('tanggal_kembali')
                ->count();

            return view('siswa.dashboard', compact('borrowedBooksCount'));
        })->name('dashboard');

        Route::get('/buku', [SiswaBukuController::class, 'index'])
            ->middleware('permission:books.view')
            ->name('buku.index');

        Route::get('/buku/{buku}', [SiswaBukuController::class, 'show'])
            ->middleware('permission:books.view')
            ->name('buku.show');

        Route::get('/peminjaman-saya', [SiswaPeminjamanController::class, 'index'])
            ->middleware(['permission:loans.view-own', 'permission:loans.history-own'])
            ->name('peminjaman.index');
    });

Route::middleware([
    'auth',
    'role:siswa',
    'permission:loans.history-own',
])
    ->prefix('siswa')
    ->name('siswa.')
    ->group(function () {

        Route::get('/riwayat-peminjaman', [PeminjamanController::class, 'riwayat'])
            ->middleware('permission:loans.view-own')
            ->name('riwayat-peminjaman');
    });

Route::middleware(['auth', 'role:admin|petugas|siswa'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->middleware('permission:profile.view-own')
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->middleware('permission:profile.update-own')
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->middleware('permission:profile.update-own')
        ->name('profile.destroy');
});

require __DIR__.'/auth.php';
