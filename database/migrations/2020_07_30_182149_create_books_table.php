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
                $table->text('description');
                $table->string('publish_year');
                $table->string('cover');
                $table->boolean('is_available')->default(1);
                $table->unsignedInteger('category_id');
                $table->unsignedInteger('publisher_id');
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
