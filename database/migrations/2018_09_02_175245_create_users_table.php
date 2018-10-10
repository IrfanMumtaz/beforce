<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email')->unique();
            $table->string('password');
            $table->text('permissions')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->string('first_name', 191)->nullable();
            $table->string('last_name', 191)->nullable();
            $table->text('bio')->nullable();
            $table->string('gender', 191)->nullable();
            $table->date('dob')->nullable();
            $table->string('pic', 191)->nullable();
            $table->integer('country_id')->unsigned()->index()->nullable();       
            $table->foreign('country_id')->references('id')->on('countries');
            $table->integer('state_id')->unsigned()->index()->nullable();       
            $table->foreign('state_id')->references('id')->on('states');
            $table->integer('city_id')->unsigned()->index()->nullable();       
            $table->foreign('city_id')->references('id')->on('cities');
            $table->string('address', 191)->nullable();
            $table->string('postal', 191)->nullable();
            $table->string('provider', 191)->nullable();
            $table->string('provider_id', 191)->nullable();
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
        Schema::dropIfExists('users');
    }
}
