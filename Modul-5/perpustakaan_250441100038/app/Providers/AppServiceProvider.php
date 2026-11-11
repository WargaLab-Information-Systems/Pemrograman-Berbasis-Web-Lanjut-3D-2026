<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Riwayat peminjaman: pemilik data boleh melihat miliknya sendiri (jika punya permission),
        // sedangkan data milik orang lain hanya untuk yang berhak mengelola peminjaman (admin/petugas).
        Gate::define('lihat-riwayat', function (User $pengguna, User $target) {
            if ($pengguna->is($target)) {
                return $pengguna->can('melihat riwayat peminjaman sendiri');
            }

            return $pengguna->can('kelola peminjaman');
        });
    }
}
