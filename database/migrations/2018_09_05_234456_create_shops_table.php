<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 191)->nullable();
            $table->string('Ownername', 191)->nullable();
            $table->string('Contactperson', 191)->nullable();
            $table->string('Contactnumber', 191)->nullable();
            $table->string('latitude', 191)->nullable();
            $table->string('longitude', 191)->nullable();
            $table->string('Storesize', 191)->nullable();
            $table->integer('brand_id')->unsigned()->index()->nullable();       
            // $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
            $table->integer('city_id')->unsigned()->index()->nullable();       
            // $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shops');
    }
}
