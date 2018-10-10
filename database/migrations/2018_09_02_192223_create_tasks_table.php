<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('emp_id')->unsigned()->index()->nullable();       
            $table->foreign('emp_id')->references('id')->on('employees')->onDelete('cascade');
            $table->integer('assign_by')->unsigned();       
            $table->string('Tasktype', 191)->nullable();
            $table->date('StartDate')->nullable();
            $table->date('EndDate')->nullable(); 
            $table->text('description'); 
            $table->string('Status', 20)->default('Pending');
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
        Schema::dropIfExists('tasks');
    }
}
