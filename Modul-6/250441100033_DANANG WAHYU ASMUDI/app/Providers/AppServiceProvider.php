<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

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
        // SOAL 3: Gate 'kelola-buku' (Hanya role admin dan petugas)
        Gate::define('kelola-buku', function (?User $user) {
            return $user ? $user->hasRole(['admin', 'petugas']) : false;
        });

        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('peminjamans') && !\Illuminate\Support\Facades\Schema::hasColumn('peminjamans', 'status')) {
                \Illuminate\Support\Facades\Schema::table('peminjamans', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->string('status', 20)->default('menunggu')->after('buku_id');
                });

                // Update data yang sudah ada sebelumnya
                \Illuminate\Support\Facades\DB::table('peminjamans')
                    ->whereNotNull('tanggal_kembali')
                    ->update(['status' => 'kembali']);

                \Illuminate\Support\Facades\DB::table('peminjamans')
                    ->whereNull('tanggal_kembali')
                    ->update(['status' => 'dipinjam']);
            }
        } catch (\Throwable $e) {
            // Abaikan jika database belum tersambung (misal saat command testing di sandbox)
        }
    }
}
