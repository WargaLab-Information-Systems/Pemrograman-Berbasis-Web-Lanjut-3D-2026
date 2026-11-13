<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('anggotas', function (Blueprint $table) {
            if (!Schema::hasColumn('anggotas', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            }
        });

        Schema::table('peminjamans', function (Blueprint $table) {
            if (!Schema::hasColumn('peminjamans', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('peminjamans', 'status')) {
                $table->string('status')->default('disetujui')->after('tanggal_kembali'); 
                // status: 'menunggu', 'disetujui', 'ditolak', 'dikembalikan'
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            if (Schema::hasColumn('peminjamans', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
            if (Schema::hasColumn('peminjamans', 'status')) {
                $table->dropColumn('status');
            }
        });

        Schema::table('anggotas', function (Blueprint $table) {
            if (Schema::hasColumn('anggotas', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });
    }
};

