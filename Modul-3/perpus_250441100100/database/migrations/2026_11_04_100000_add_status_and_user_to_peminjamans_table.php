<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kepemilikan peminjaman ke akun pengguna (users) dan status
     * peminjaman. Kolom anggota_id lama dipertahankan agar data modul
     * sebelumnya tetap utuh, tetapi dibuat nullable karena peminjaman yang
     * diajukan siswa dari akun login tidak selalu memiliki data anggota.
     */
    public function up(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->nullable()
                ->after('id')
                ->constrained('users')
                ->nullOnDelete();

            // diajukan | dipinjam | ditolak | dikembalikan
            $table->string('status', 20)->default('diajukan')->after('tanggal_kembali')->index();

            $table->foreignId('anggota_id')->nullable()->change();
        });

        // Data lama: sudah ada tanggal kembali berarti sudah dikembalikan.
        DB::table('peminjamans')->whereNotNull('tanggal_kembali')->update(['status' => 'dikembalikan']);
        DB::table('peminjamans')->whereNull('tanggal_kembali')->update(['status' => 'dipinjam']);

        // Hubungkan data lama ke akun user berdasarkan kesamaan email anggota.
        $anggotas = DB::table('anggotas')->get(['id', 'email']);
        foreach ($anggotas as $anggota) {
            $userId = DB::table('users')->where('email', $anggota->email)->value('id');
            if ($userId) {
                DB::table('peminjamans')->where('anggota_id', $anggota->id)->update(['user_id' => $userId]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->dropIndex(['status']);
            $table->dropColumn('status');
        });
    }
};
