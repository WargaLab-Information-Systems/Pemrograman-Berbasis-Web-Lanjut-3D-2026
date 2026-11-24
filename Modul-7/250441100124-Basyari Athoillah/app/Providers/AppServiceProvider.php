<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
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
        $this->configureProductionAssets();

        Gate::define('kelola-buku', function (User $user): bool {
            return $user->hasAnyRole(['admin', 'petugas']);
        });
    }

    /**
     * Configure Vite and URL generation for the production deployment.
     */
    private function configureProductionAssets(): void
    {
        if (! $this->app->environment('production')) {
            return;
        }

        // A stale "public/hot" file (a Vite dev-server marker) makes @vite emit
        // http://localhost:5173/... URLs that visitors cannot reach, leaving the
        // deployed page without any styling.
        Vite::useHotFile(storage_path('framework/vite.hot'));

        // Behind a TLS-terminating proxy such as Vercel the request looks like
        // plain HTTP, so generated asset URLs use http:// and browsers block
        // them as mixed content on the https:// page.
        URL::forceScheme('https');
    }
}
