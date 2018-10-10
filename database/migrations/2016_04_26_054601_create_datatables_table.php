<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatatablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datatables', function (Blueprint $table) {
            $table->increments('id');
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('email')->nullable();
            $table->string('points')->nullable();
            $table->string('notes')->nullable();
            $table->integer('age')->nullable();
            $table->string('job')->nullable();
            $table->string('gender')->nullable();
            $table->integer('country_id')->unsigned()->index()->nullable();       
            // $table->foreign('country_id')->references('id')->on('countries');
            $table->string('sale_date')->nullable();
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
        Schema::drop('datatables');
    }
}
