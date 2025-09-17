<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('books', function (Blueprint $table) {
            // Critical indexes for filtering and sorting
            $table->index(['available_copies', 'created_at'], 'idx_available_created');
            $table->index(['available_copies', 'title_en'], 'idx_available_title');
            $table->index(['available_copies', 'author_en'], 'idx_available_author');
            $table->index(['available_copies', 'publication_year'], 'idx_available_year');
            
            // Foreign key indexes for joins
            $table->index(['category_id', 'available_copies'], 'idx_category_available');
            $table->index(['language_id', 'available_copies'], 'idx_language_available');
            $table->index(['primary_author_id', 'available_copies'], 'idx_author_available');
            $table->index(['publisher_id', 'available_copies'], 'idx_publisher_available');
            
            // Search optimization indexes
            $table->index(['title_en', 'available_copies'], 'idx_title_en_available');
            $table->index(['title_bn', 'available_copies'], 'idx_title_bn_available');
            $table->index(['author_en', 'available_copies'], 'idx_author_en_available');
            $table->index(['author_bn', 'available_copies'], 'idx_author_bn_available');
            $table->index(['isbn', 'available_copies'], 'idx_isbn_available');
            $table->index(['publication_year', 'available_copies'], 'idx_year_available');
            
            // Composite index for "All Books" sorting
            $table->index(['available_copies', 'created_at', 'title_en'], 'idx_all_books_sort');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropIndex('idx_available_created');
            $table->dropIndex('idx_available_title');
            $table->dropIndex('idx_available_author');
            $table->dropIndex('idx_available_year');
            $table->dropIndex('idx_category_available');
            $table->dropIndex('idx_language_available');
            $table->dropIndex('idx_author_available');
            $table->dropIndex('idx_publisher_available');
            $table->dropIndex('idx_title_en_available');
            $table->dropIndex('idx_title_bn_available');
            $table->dropIndex('idx_author_en_available');
            $table->dropIndex('idx_author_bn_available');
            $table->dropIndex('idx_isbn_available');
            $table->dropIndex('idx_year_available');
            $table->dropIndex('idx_all_books_sort');
        });
    }
};