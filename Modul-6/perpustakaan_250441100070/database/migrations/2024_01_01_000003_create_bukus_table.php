<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bukus', function (Blueprint $table) {
            $table->id();

            // Nama kolom FK berbahasa Indonesia (kategori_id) tidak otomatis
            // dikenali Laravel sebagai tabel "kategoris", jadi tabel tujuan
            // harus disebutkan secara eksplisit lewat constrained('kategoris').
            $table->foreignId('kategori_id')
                ->constrained('kategoris')
                ->onDelete('cascade');

            $table->string('judul');
            $table->string('penulis');
            $table->year('tahun_terbit');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bukus');
    }
};
