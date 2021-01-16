<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'ratings', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('rate');
                $table->string('opinion')->nullable();
                $table->unsignedInteger('book_id');
                $table->unsignedInteger('reader_id');
                $table->foreign('book_id')->references('id')->on('books');
                $table->foreign('reader_id')->references('id')->on('readers');
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
        Schema::dropIfExists('ratings');
    }
}
