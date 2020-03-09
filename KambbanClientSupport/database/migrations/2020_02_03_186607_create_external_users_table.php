<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExternalUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('external_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('status');
            $table->bigInteger('external_user_type_id')->unsigned();
            $table->bigInteger('external_client_id')->unsigned();
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('external_user_type_id')->references('id')->on('external_user_types');
            $table->foreign('external_client_id')->references('id')->on('external_clients');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('external_users');
    }
}
