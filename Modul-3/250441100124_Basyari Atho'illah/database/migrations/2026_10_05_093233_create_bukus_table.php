<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migrasi untuk membuat tabel 'bukus'.
     */
    public function up(): void
    {
        Schema::create('bukus', function (Blueprint $table) {
            $table->id();                               // Primary Key: id (auto-increment)
            $table->foreignId('kategori_id')            // Foreign Key ke tabel kategoris
                ->constrained('kategoris')
                ->cascadeOnDelete();
            $table->string('judul');                    // Judul buku
            $table->string('penulis');                  // Nama penulis / pengarang buku
            $table->year('tahun_terbit');               // Tahun terbit buku (format YYYY)
            $table->timestamps();                       // created_at & updated_at
        });
    }

    /**
     * Membatalkan migrasi (menghapus tabel 'bukus').
     */
    public function down(): void
    {
        Schema::dropIfExists('bukus');
    }
};
