<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        /**
         * Modul 7: di Vercel, koneksi HTTPS diterima di lapisan depan lalu
         * diteruskan ke aplikasi sebagai request biasa, sehingga Laravel bisa
         * mengira dirinya diakses lewat HTTP dan menghasilkan tautan http://.
         * Browser memblokir CSS/JS http:// pada halaman https:// (mixed
         * content), jadi di production semua URL dipaksa memakai https.
         */
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        /**
         * Modul 6 Soal 3: Gate "kelola-buku" memusatkan aturan siapa yang
         * boleh menambah, mengubah, dan menghapus buku di SATU tempat.
         * (Tidak berubah dari Modul 6.)
         */
        Gate::define('kelola-buku', function (User $user) {
            return $user->hasAnyRole(['admin', 'petugas']);
        });
    }
}
