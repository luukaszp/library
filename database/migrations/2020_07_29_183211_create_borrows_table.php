<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBorrowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'borrows', function (Blueprint $table) {
                $table->increments('id');
                $table->string('borrows_date');
                $table->string('returns_date');
                $table->boolean('is_returned')->default(0);
                $table->unsignedInteger('user_id');
                $table->unsignedInteger('book_id');
                $table->foreign('user_id')->references('id')->on('users');
                $table->foreign('book_id')->references('id')->on('books');
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
        Schema::dropIfExists('borrows');
    }
}
