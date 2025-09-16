<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title_en');
            $table->string('title_bn');
            $table->string('title_bn_translit')->nullable();
            $table->string('author_en')->nullable();
            $table->string('author_bn')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('publisher_en')->nullable();
            $table->string('publisher_bn')->nullable();
            $table->string('isbn', 20)->nullable()->unique();
            $table->string('barcode')->nullable()->unique();
            $table->year('publication_year')->nullable();
            $table->integer('pages')->nullable();
            $table->string('language_primary', 10)->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_bn')->nullable();
            $table->string('cover_path')->nullable();
            $table->string('pdf_path')->nullable();
            $table->unsignedInteger('available_copies')->default(1);
            $table->unsignedInteger('total_copies')->default(1);
            $table->timestamps();
            $table->index(['title_en']);
            $table->index(['title_bn']);
            $table->index(['author_en']);
            $table->index(['author_bn']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('books');
    }
}
