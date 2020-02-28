<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_type');
            $table->string('status');
            $table->string('attrs')->nullable();
            $table->bigIncrements('permission_id')->unsigned();
            $table->timestamps();

            $table->foreign('permission_id')->references('id')->on('user_permissions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_types');
    }
}
