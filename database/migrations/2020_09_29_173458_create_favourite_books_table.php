<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFavouriteBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'favourite_books', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('book_id');
                $table->unsignedInteger('user_id');
                $table->foreign('book_id')->references('id')->on('books')
                    ->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')
                    ->onDelete('cascade');
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
        Schema::dropIfExists('favourite_books');
    }
}