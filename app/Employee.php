<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = 'employees';

    protected $fillable = [
    	'Username',
    	'Password',
    	'email',
    	'EmployeeName',
    	'FatherName',
    	'SpouseName',
    	'DOB',
    	'CNIC',
    	'FamilyCode',
    	'Nationality',
    	'PostalAddress',
    	'ContactNumber',
    	'EmployeeNo',
    	'Gender',
    	'religion',
    	'NatureofEmployment',
    	'DateOfDeployment',
    	'NTN',
    	'EOBINO',
    	'InsuranceEntitlement',
    	'BankAccountNo',
    	'BankBranchName',
    	'BankBranchCity',
    	'BloodGroup',
    	'AcademicQalification',
    	'District',
    	'Proviance',
    	'MaritalStatus',
    	'MonthlySalary',
    	'Allowances',
    	'ResidentialCity',
    	'Department',
    	'UserAvatar',
    	'CNICIMG',
    	'SelectBrand',
    	'ShopCity',
    	'Shop',
    	'Reporting1',
    	'Reporting2',
    	'MailingAddress',
    	'Country',
    	'country_id',
    	'state_id',
    	'ZipCode',
    	'Designation',
    	'EmployeeStatus',
    	'AttenStartTime',
    	'weekofdays',
    	'Monday',
    	'Tuesday',
    	'Wednesday',
    	'Thursday',
    	'Friday',
    	'Saturday',
    	'Sunday',
    ];
    public function employee_role()
    {
        return $this->hasManyThrough('App\role', 'App\Role_employee', 'emp_id', 'id','id','role_id');
    }
}
