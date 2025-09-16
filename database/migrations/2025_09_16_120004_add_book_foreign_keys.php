<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->foreignId('publisher_id')->nullable()->after('publisher_bn')->constrained('publishers')->nullOnDelete();
            $table->foreignId('language_id')->nullable()->after('language_primary')->constrained('languages')->nullOnDelete();
            $table->foreignId('primary_author_id')->nullable()->after('author_bn')->constrained('authors')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropConstrainedForeignId('publisher_id');
            $table->dropConstrainedForeignId('language_id');
            $table->dropConstrainedForeignId('primary_author_id');
        });
    }
};


