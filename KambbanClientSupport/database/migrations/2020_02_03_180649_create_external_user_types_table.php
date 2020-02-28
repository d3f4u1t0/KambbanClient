<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExternalUserTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('external_user_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('external_user_type');
            $table->string('status');
            $table->string('attrs')->nullable();
            $table->bigIncrements('permission_id')->unsigned();
            $table->timestamps();

            $table->foreign('permission_id')->references('id')->on('external_user_permissions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('external_user_types');
    }
}
