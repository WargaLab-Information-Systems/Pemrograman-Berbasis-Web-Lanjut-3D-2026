<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use App\Models\User;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Memaksa penggunaan HTTPS untuk Vercel
        URL::forceScheme('https');

        // Memaksa Vercel memuat alamat utama (mengatasi error tombol Visit)
        URL::forceRootUrl('https://perpustakaan-modul7.vercel.app');

        // --- SOAL 3: Membuat Gate kelola-buku ---
        Gate::define('kelola-buku', function (User $user) {
            return $user->hasAnyRole(['admin', 'petugas']);
        });
    }
}