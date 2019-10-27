<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 125);
            $table->string('url', 255)->nullable();
            $table->string('username', 125);
            $table->string('password', 255);
            $table->string('console_url', 255)->nullable();
            $table->string('console_username', 125)->nullable();
            $table->string('console_password', 255)->nullable();
            $table->string('hostname', 125)->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('servers');
    }
}
