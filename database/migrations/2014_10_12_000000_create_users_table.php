<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('surname');
            $table->string('email')->unique()->nullable();
            $table->string('card_number')->unique()->nullable();
            $table->string('id_number')->unique()->nullable();
            $table->boolean('is_admin')->default(0);
            $table->boolean('is_worker')->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('activation_token')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
