<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migrasi untuk membuat tabel 'peminjamans'.
     */
    public function up(): void
    {
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id();                               // Primary Key: id (auto-increment)
            $table->foreignId('anggota_id')             // Foreign Key ke tabel anggotas
                ->constrained('anggotas')
                ->cascadeOnDelete();
            $table->foreignId('buku_id')                // Foreign Key ke tabel bukus
                ->constrained('bukus')
                ->cascadeOnDelete();
            $table->date('tanggal_pinjam');             // Tanggal buku dipinjam
            $table->date('tanggal_kembali')->nullable(); // Tanggal buku dikembalikan (bisa bernilai NULL jika belum dikembalikan)
            $table->timestamps();                       // created_at & updated_at
        });
    }

    /**
     * Membatalkan migrasi (menghapus tabel 'peminjamans').
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};
