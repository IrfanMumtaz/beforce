<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrandEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brand_employees', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('brand_id')->unsigned()->index()->nullable();       
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
            $table->integer('emp_id')->unsigned()->index()->nullable();       
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
        Schema::dropIfExists('brand_employees');
    }
}
