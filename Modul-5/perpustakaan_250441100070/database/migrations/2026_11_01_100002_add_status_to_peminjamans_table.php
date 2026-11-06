<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Alur peminjaman: diajukan -> dipinjam (disetujui) -> dikembalikan,
     * atau diajukan -> ditolak.
     */
    public function up(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->string('status', 20)->default('diajukan')->after('tanggal_kembali');

            $table->foreignId('diproses_oleh')
                ->nullable()
                ->after('status')
                ->constrained('users')
                ->nullOnDelete();

            // Pengajuan belum punya tanggal pinjam (diisi saat disetujui).
            $table->date('tanggal_pinjam')->nullable()->change();
        });

        // Data lama (Modul 2) disesuaikan dengan status yang baru.
        DB::table('peminjamans')->whereNotNull('tanggal_kembali')->update(['status' => 'dikembalikan']);
        DB::table('peminjamans')->whereNull('tanggal_kembali')->update(['status' => 'dipinjam']);
    }

    public function down(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->dropConstrainedForeignId('diproses_oleh');
            $table->dropColumn('status');
        });
    }
};
