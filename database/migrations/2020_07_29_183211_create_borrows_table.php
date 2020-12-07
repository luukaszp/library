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
                $table->string('when_returned')->nullable();
                $table->integer('delay')->nullable();
                $table->integer('penalty')->nullable();
                $table->unsignedInteger('reader_id');
                $table->unsignedInteger('book_id');
                $table->unsignedInteger('worker_id');
                $table->foreign('reader_id')->references('id')->on('readers');
                $table->foreign('book_id')->references('id')->on('books');
                $table->foreign('worker_id')->references('id')->on('workers');
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
