<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('description');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->bigInteger('external_user_id')->unsigned();
            $table->bigInteger('category_id')->unsigned();
            $table->bigInteger('request_type_id')->unsigned();
            $table->string('status');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('external_user_id')->references('id')->on('external_users');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('request_type_id')->references('id')->on('request_types');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('request');
    }
}
