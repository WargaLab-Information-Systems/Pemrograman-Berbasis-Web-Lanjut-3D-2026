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
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id();
            // Menyebutkan nama tabel tujuan secara eksplisit pada constrained()[cite: 2]
            $table->foreignId('anggota_id')->constrained('anggotas');
            $table->foreignId('buku_id')->constrained('bukus');
            $table->date('tanggal_pinjam');
            // Kolom tanggal_kembali dibuat nullable()[cite: 2]
            $table->date('tanggal_kembali')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};