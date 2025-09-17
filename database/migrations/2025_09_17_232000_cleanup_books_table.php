<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Make title_bn nullable if present
        if (Schema::hasColumn('books', 'title_bn')) {
            Schema::table('books', function (Blueprint $table) {
                $table->string('title_bn')->nullable()->change();
            });
        }

        // Drop author indexes if they exist to avoid MySQL errors when dropping columns
        try { DB::statement('ALTER TABLE `books` DROP INDEX `books_author_en_index`'); } catch (\Throwable $e) {}
        try { DB::statement('ALTER TABLE `books` DROP INDEX `books_author_bn_index`'); } catch (\Throwable $e) {}

        // Drop columns individually if they exist
        $columns = [
            'title_bn_translit', 'author_en', 'author_bn', 'publisher_en', 'publisher_bn', 'language_primary', 'barcode', 'pdf_path'
        ];
        foreach ($columns as $col) {
            if (Schema::hasColumn('books', $col)) {
                Schema::table('books', function (Blueprint $table) use ($col) {
                    $table->dropColumn($col);
                });
            }
        }
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            // Restore title_bn as required
            $table->string('title_bn')->nullable(false)->change();
            
            // Add back removed columns
            $table->string('title_bn_translit')->nullable();
            $table->string('author_en')->nullable();
            $table->string('author_bn')->nullable();
            $table->string('publisher_en')->nullable();
            $table->string('publisher_bn')->nullable();
            $table->string('language_primary', 10)->nullable();
            $table->string('barcode')->nullable()->unique();
            $table->string('pdf_path')->nullable();
            
            // Restore indexes
            $table->index(['author_en']);
            $table->index(['author_bn']);
        });
    }
};
