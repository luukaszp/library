<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthorReaderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'author_reader', function (Blueprint $table) {
                $table->primary(['author_id','reader_id']);
                $table->unsignedInteger('author_id');
                $table->foreign('author_id')
                    ->references('id')
                    ->on('authors')
                    ->onDelete('cascade');
                $table->unsignedInteger('reader_id');
                $table->foreign('reader_id')
                    ->references('id')
                    ->on('readers')
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
        Schema::dropIfExists('author_reader');
    }
}
