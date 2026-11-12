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
        /**
         * Soal 3: Gate "kelola-buku" memusatkan aturan siapa yang boleh
         * menambah, mengubah, dan menghapus buku di SATU tempat. Sebelum
         * ini, aturannya tersebar sebagai middleware permission:kelola
         * data buku di routes/api.php DAN sebagai @can('kelola data buku')
         * di view -- dua tempat yang bisa saja lupa disamakan. Sekarang
         * keduanya memanggil Gate yang sama.
         */
        Gate::define('kelola-buku', function (User $user) {
            return $user->hasAnyRole(['admin', 'petugas']);
        });
    }
}
