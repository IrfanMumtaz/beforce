<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('AssetName', 191)->nullable();
            $table->integer('shop_id')->unsigned()->index()->nullable();       
            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
            $table->integer('brand_id')->unsigned()->index()->nullable();       
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
            $table->string('Description', 191)->nullable();
            $table->string('QRCode', 191)->nullable();
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
        Schema::dropIfExists('assets');
    }
}
