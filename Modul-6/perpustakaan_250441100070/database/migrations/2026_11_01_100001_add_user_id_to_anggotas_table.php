<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menghubungkan akun login (users) dengan data keanggotaan (anggotas),
     * supaya riwayat peminjaman bisa ditelusuri dari identitas pengguna.
     */
    public function up(): void
    {
        Schema::table('anggotas', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->nullable()
                ->unique()
                ->after('id')
                ->constrained('users')
                ->nullOnDelete();

            // Akun yang mendaftar sendiri belum tentu mengisi nomor telepon.
            $table->string('no_telepon')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('anggotas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
