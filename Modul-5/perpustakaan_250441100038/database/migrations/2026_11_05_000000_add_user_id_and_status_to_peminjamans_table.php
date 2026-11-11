<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Siswa mengajukan peminjaman lewat akun (users), bukan tabel anggotas.
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->foreignId('anggota_id')->nullable()->change();
        });

        Schema::table('peminjamans', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('anggota_id')
                ->constrained('users')->nullOnDelete();
            $table->string('status')->default('dipinjam')->after('tanggal_kembali');
        });

        // Data lama yang sudah punya tanggal_kembali dianggap sudah dikembalikan.
        DB::table('peminjamans')->whereNotNull('tanggal_kembali')->update(['status' => 'dikembalikan']);
    }

    public function down(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn('status');
        });
    }
};
