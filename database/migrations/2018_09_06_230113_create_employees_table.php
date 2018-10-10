<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('Username')->unique()->nullable();
            $table->string('Password')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('Designation')->nullable();
            $table->string('EmployeeName')->nullable();
            $table->string('FatherName')->nullable();
            $table->string('SpouseName')->nullable();
            $table->string('DOB')->nullable();
            $table->string('CNIC')->nullable();
            $table->string('FamilyCode')->nullable();
            $table->string('Nationality')->nullable();
            $table->string('PostalAddress')->nullable();
            $table->string('ContactNumber')->nullable();
            $table->string('EmployeeNo')->nullable();
            $table->string('Gender')->nullable();
            $table->string('religion')->nullable();
            $table->string('NatureofEmployment')->nullable();
            $table->string('DateOfDeployment')->nullable();
            $table->string('NTN')->nullable();
            $table->string('EOBINO')->nullable();
            $table->string('InsuranceEntitlement')->nullable();
            $table->string('BankAccountNo')->nullable();
            $table->string('BankBranchName')->nullable();
            $table->string('BankBranchCity')->nullable();
            $table->string('BloodGroup')->nullable();
            $table->string('AcademicQalification')->nullable();
            $table->string('District')->nullable();
            $table->string('Proviance')->nullable();
            $table->string('MaritalStatus')->nullable();
            $table->integer('MonthlySalary')->nullable();
            $table->integer('Allowances')->nullable();
            $table->string('ResidentialCity')->nullable();
            $table->string('Department')->nullable();
            $table->string('UserAvatar')->nullable();
            $table->string('CNICIMG')->nullable();
            $table->string('SelectBrand')->nullable();
            $table->string('ShopCity')->nullable();
            $table->string('Shop')->nullable();
            $table->string('Reporting1')->nullable();
            $table->string('Reporting2')->nullable();
            $table->string('MailingAddress')->nullable();
            $table->string('Country')->nullable();
            $table->integer('country_id')->unsigned()->index()->nullable();       
            $table->integer('state_id')->unsigned()->index()->nullable();       
            $table->string('ZipCode')->nullable();
            $table->string('EmployeeStatus')->nullable();
            $table->string('AttenStartTime')->nullable();
            $table->string('weekofdays',30)->nullable();
            $table->tinyInteger('Monday')->nullable();
            $table->tinyInteger('Tuesday')->nullable();
            $table->tinyInteger('Wednesday')->nullable();
            $table->tinyInteger('Thursday')->nullable();
            $table->tinyInteger('Friday')->nullable();
            $table->tinyInteger('Saturday')->nullable();
            $table->tinyInteger('Sunday')->nullable();
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
        Schema::dropIfExists('employees');
    }
}
