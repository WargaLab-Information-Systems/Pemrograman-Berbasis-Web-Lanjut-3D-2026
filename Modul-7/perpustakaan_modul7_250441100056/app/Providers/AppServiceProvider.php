<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        
            if ($this->app->environment('production')) {
            URL::forceScheme('https');
            }
        // Soal 3: satu aturan untuk pengelolaan buku
            Gate::define('kelola-buku', function (User $user) {
                return $user->hasAnyRole(['admin', 'petugas']);
            });
    }
}