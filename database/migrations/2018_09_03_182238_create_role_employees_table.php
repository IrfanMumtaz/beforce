<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoleEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_employees', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('emp_id')->unsigned()->index()->nullable();       
            $table->foreign('emp_id')->references('id')->on('employees')->onDelete('cascade');
            $table->integer('role_id')->unsigned()->index()->nullable();       
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
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
        Schema::dropIfExists('role_employees');
    }
}
