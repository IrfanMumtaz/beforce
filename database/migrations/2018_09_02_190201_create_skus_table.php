<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skus', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id')->unsigned()->index();       
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->string('name', 191)->nullable();
            $table->bigInteger('Price')->nullable();
            $table->integer('ItemPerCarton')->nullable();
            $table->string('SKUImage', 191)->nullable();              
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
        Schema::dropIfExists('skus');
    }
}
