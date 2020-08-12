<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublisherBook extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('publisher_book', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('publisher_id');
            $table->unsignedInteger('book_id');
            $table->timestamps();
            $table->foreign('publisher_id')->references('id')->on('publishers');
            $table->foreign('book_id')->references('id')->on('books');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('publisher_book');
    }
}
