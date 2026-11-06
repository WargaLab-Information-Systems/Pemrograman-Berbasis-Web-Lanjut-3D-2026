<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const FOREIGN_KEY = 'bukus_kategori_id_legacy_foreign';

    public function up(): void
    {
        $orphanedCategoryIds = DB::table('bukus as books')
            ->leftJoin('categorys as categories', 'categories.id', '=', 'books.kategori_id')
            ->whereNull('categories.id')
            ->distinct()
            ->pluck('books.kategori_id');

        foreach ($orphanedCategoryIds as $categoryId) {
            DB::table('categorys')->insert([
                'id' => $categoryId,
                'nama' => "Kategori lama #{$categoryId}",
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $hasCategoryForeignKey = collect(Schema::getForeignKeys('bukus'))->contains(
            fn (array $foreignKey): bool => $foreignKey['columns'] === ['kategori_id']
                && $foreignKey['foreign_table'] === 'categorys'
        );

        if (! $hasCategoryForeignKey) {
            Schema::table('bukus', function (Blueprint $table): void {
                $table->foreign('kategori_id', self::FOREIGN_KEY)
                    ->references('id')
                    ->on('categorys')
                    ->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        $hasLegacyForeignKey = collect(Schema::getForeignKeys('bukus'))->contains(
            fn (array $foreignKey): bool => $foreignKey['name'] === self::FOREIGN_KEY
        );

        if ($hasLegacyForeignKey) {
            Schema::table('bukus', function (Blueprint $table): void {
                $table->dropForeign(self::FOREIGN_KEY);
            });
        }
    }
};
