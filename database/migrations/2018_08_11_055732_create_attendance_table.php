<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAttendanceTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date')->nullable();
            $table->integer('empid')->nullable();
            $table->date('startTime')->nullable();
            $table->string('StartImage')->nullable();
            $table->date('endTime')->nullable();
            $table->string('EndImage')->nullable();
            $table->string('break')->nullable();
            $table->string('namaz')->nullable();
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
        Schema::drop('attendance');
    }
}
