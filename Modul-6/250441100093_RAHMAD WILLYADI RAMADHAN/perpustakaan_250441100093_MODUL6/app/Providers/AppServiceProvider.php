<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
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
        // --- SOAL 3: Membuat Gate kelola-buku ---
        Gate::define('kelola-buku', function (User $user) {
            return $user->hasAnyRole(['admin', 'petugas']);
        });
    }
}
