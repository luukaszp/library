<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpinionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'opinions', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedInteger('book_id');
                $table->unsignedInteger('user_id');
                $table->unsignedInteger('rating_id');
                $table->foreign('rating_id')->references('id')->on('ratings')
                    ->onDelete('cascade');
                $table->foreign('book_id')->references('id')->on('books');
                $table->foreign('user_id')->references('id')->on('users');
                $table->string('opinion')->nullable();
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
        Schema::dropIfExists('opinions');
    }
}
