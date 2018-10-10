<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEBStagTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('EBStag', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('empId')->nullable();
            $table->integer('brandId')->nullable();
            $table->integer('StoreId')->nullable();
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
        Schema::drop('EBStag');
    }
}
