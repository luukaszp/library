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
        Schema::create(
            'books', function (Blueprint $table) {
                $table->increments('id');
                $table->string('title');
                $table->string('isbn')->unique();
                $table->string('description');
                $table->string('publish_year');
                $table->string('cover');
                $table->unsignedInteger('author_id');
                $table->unsignedInteger('category_id');
                $table->unsignedInteger('publisher_id');
                $table->foreign('author_id')->references('id')->on('authors');
                $table->foreign('category_id')->references('id')->on('categories');
                $table->foreign('publisher_id')->references('id')->on('publishers');
                $table->timestamps();
            }
        );
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
