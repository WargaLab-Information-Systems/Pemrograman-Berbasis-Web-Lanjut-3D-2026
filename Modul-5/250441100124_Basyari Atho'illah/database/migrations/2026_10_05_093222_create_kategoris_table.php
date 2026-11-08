<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migrasi untuk membuat tabel 'kategoris'.
     */
    public function up(): void
    {
        Schema::create('kategoris', function (Blueprint $table) {
            $table->id();                               // Primary Key: id (auto-increment)
            $table->string('nama');                     // Nama kategori buku (misal: Novel, Sains, dsb.)
            $table->timestamps();                       // created_at & updated_at
        });
    }

    /**
     * Membatalkan migrasi (menghapus tabel 'kategoris').
     */
    public function down(): void
    {
        Schema::dropIfExists('kategoris');
    }
};
