<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migrasi untuk membuat tabel 'petugas'.
     */
    public function up(): void
    {
        Schema::create('petugas', function (Blueprint $table) {
            $table->id();                               // Primary Key: id (auto-increment)
            $table->string('nip', 10)->unique();        // NIP petugas perpustakaan (unik, maks 10 karakter)
            $table->string('nama');                     // Nama lengkap petugas
            $table->timestamps();                       // created_at & updated_at
        });
    }

    /**
     * Membatalkan migrasi (menghapus tabel 'petugas').
     */
    public function down(): void
    {
        Schema::dropIfExists('petugas');
    }
};
