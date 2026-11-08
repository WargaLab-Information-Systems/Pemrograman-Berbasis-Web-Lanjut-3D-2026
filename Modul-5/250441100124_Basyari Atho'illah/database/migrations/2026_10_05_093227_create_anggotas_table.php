<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migrasi untuk membuat tabel 'anggotas'.
     */
    public function up(): void
    {
        Schema::create('anggotas', function (Blueprint $table) {
            $table->id();                               // Primary Key: id (auto-increment)
            $table->string('nama');                     // Nama lengkap anggota
            $table->string('email')->unique();          // Alamat email anggota (unik, tidak boleh kembar)
            $table->string('no_telepon');               // Nomor telepon / WhatsApp anggota
            $table->timestamps();                       // created_at & updated_at
        });
    }

    /**
     * Membatalkan migrasi (menghapus tabel 'anggotas').
     */
    public function down(): void
    {
        Schema::dropIfExists('anggotas');
    }
};
