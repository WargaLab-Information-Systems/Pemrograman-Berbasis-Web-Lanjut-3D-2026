<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Hubungkan data anggota dengan akun pengguna (users),
     * supaya riwayat peminjaman bisa dibatasi per pengguna yang login.
     */
    public function up(): void
    {
        Schema::table('anggotas', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->nullable()
                ->after('id')
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('anggotas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
