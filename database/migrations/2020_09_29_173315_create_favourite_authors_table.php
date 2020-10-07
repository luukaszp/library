<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFavouriteAuthorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'favourite_authors', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('author_id');
                $table->unsignedInteger('user_id');
                $table->foreign('author_id')->references('id')->on('authors')
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
        Schema::dropIfExists('favourite_authors');
    }
}
