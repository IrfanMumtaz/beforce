<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkutargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skutargets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shop_id')->unsigned()->index()->nullable();       
            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
            $table->string('Location');
            $table->string('City', 191);
            $table->integer('sku_id')->unsigned()->index()->nullable();       
            $table->foreign('sku_id')->references('id')->on('skus')->onDelete('cascade');
            $table->string('skutargets', 191)->nullable();
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
        Schema::dropIfExists('skutargets');
    }
}
