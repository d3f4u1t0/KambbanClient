<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesExternalCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories_external_customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('external_customer_id')->unsigned();
            $table->bigInteger('category_id')->unsigned();


            $table->foreign('external_customer_id')->references('id')->on('external_customers');
            $table->foreign('category_id')->references('id')->on('categories');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies_external_customers');
    }
}
