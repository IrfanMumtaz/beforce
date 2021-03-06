<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class Employee extends Model
{
    use SoftDeletes;

    public $table = 'employees';
    
    protected $dates = ['deleted_at'];
	
    public $guarded = ['id'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'Username' => 'string',
        'Password' => 'string',
        'Designation' => 'string',
        'email' => 'string',
        'EmployeeName' => 'string',
        'FatherName' => 'string',
        'SpouseName' => 'string',
        'DOB' => 'string',
        'CNIC' => 'string',
        'FamilyCode' => 'string',
        'Nationality' => 'string',
        'PostalAddress' => 'string',
        'ContactNumber' => 'string',
        'EmployeeNo' => 'string',
        'Gender' => 'string',
        'religion' => 'string',
        'NatureofEmployment' => 'string',
        'DateOfDeployment' => 'string',
        'NTN' => 'string',
        'EOBINO' => 'string',
        'InsuranceEntitlement' => 'string',
        'BankAccountNo' => 'string',
        'BankBranchName' => 'string',
        'BankBranchCity' => 'string',
        'BloodGroup' => 'string',
        'AcademicQalification' => 'string',
        'District' => 'string',
        'Proviance' => 'string',
        'MaritalStatus' => 'string',
        'MonthlySalary' => 'integer',
        'Allowances' => 'integer',
        'ResidentialCity' => 'string',
        'Department' => 'string',
        'UserAvatar' => 'string',
	'CNICIMG' => 'string',
	'SelectBrand' => 'string',
  	'ShopCity' => 'string',
    	'Shop' => 'string',
	'Reporting1' => 'string',
	'Reporting2' => 'string',
	'MailingAddress' => 'string',
	'Country' => 'string',
	'State' => 'string',
	'ZipCode' => 'string',
	'EmployeeStatus' => 'string',
	'AttenStartTime' => 'string',
	'Monday' => 'integer',
	'Tuesday' => 'integer',
      	'Wednesday' => 'integer',
      	'Thursday' => 'integer',
      	'Friday' => 'integer',
      	'Saturday' => 'integer',
      	'Sunday' => 'integer',
	'brandId' => 'integer'
];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
       

	'Username' => 'required|unique:Employee',
        'Password' => 'required',
        'Designation' => 'required',
        'email' => 'required|email',
        'EmployeeName' => 'required',
        'Nationality' => 'required',
        'Gender' => 'required',
        'religion' => 'required',
        'DateOfDeployment' => 'required',
        'District' => 'required',
        'Proviance' => 'required',
        'Department' => 'required'
    ];



}
