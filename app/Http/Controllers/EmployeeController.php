<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\CreateEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Repositories\EmployeeRepository;
use App\Http\Controllers\AppBaseController as InfyOmBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use App\Employee;
use App\Models\Stores;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Brand;
use App\role;
use App\country;
use App\state;
use App\Shop;
use App\city;
use File;
use URL;
use Hash;
use App\Role_employee;
class EmployeeController extends InfyOmBaseController
{
    /** @var  EmployeeRepository */
    private $employeeRepository;

    public function __construct(EmployeeRepository $employeeRepo)
    {
        
    }

    /**
     * Display a listing of the Employee.
     *
     * @param Request $request
     * @return Response
     */
    public function index()
    {
        $employees = Employee::where('deleted_at',NULL)->get();
        foreach ($employees as $key => $value) {
            $role=Role::find($value->Designation);
            $employees[$key]->Designation=$role->name;
            if($value->District) {
            $city=city::find($value->District);
            $employees[$key]->District=$city->name;
            }
        }
        return view('admin.employees.index')
            ->with('employees', $employees);
    }

    /**
     * Show the form for creating a new Employee.
     *
     * @return Response
     */
    public function create()
    {

    $allbrands= Brand::where('deleted_at',NULL)->get();
    $brands[null]='-- Select Brand--';
    foreach ($allbrands as $key => $value) {
        $brands[$value->id]=$value->BrandName;
    }
    $designation_result= role::where([['name', '!=', 'Admin'],['name', '!=', 'Employee']])->get();
    $designations[null]='-- Designation--';
    foreach ($designation_result as $key => $value) {
        $designations[$value->id]=$value->name;
    }
    $countries[null]='-- Country--';
    $countries_result=country::all();
    foreach ($countries_result as $key => $value) {
        $countries[$value->id]=$value->name;
    }
    $states_result=state::all();
    foreach ($states_result as $key => $value) {
        $states[$value->id]=$value->name;
    }
    $shops_result=Shop::where('deleted_at',NULL)->get();
    foreach ($shops_result as $key => $value) {
        $shops[$value->id]=$value->name;
    }
    return view('admin.employees.create')->with('brands', $brands)->with('shops', $shops)->with('designations', $designations)->with('countries',$countries)->with('states',$states);

    }

    /**
     * Store a newly created Employee in storage.
     *
     * @param CreateEmployeeRequest $request
     *
     * @return Response
     */
    public function store(CreateEmployeeRequest $request)
    {
        if($request->hasFile('UserAvatar')) {
            $imgfile = $request->file('UserAvatar');
            $imgpath = 'storage/UserAvatar';
            File::makeDirectory($imgpath, $mode = 0777, true, true);
            $imgDestinationPath = $imgpath.'/';
            $name = time()."_".$imgfile->getClientOriginalName();
            $request->UserAvatar=$name;
            $filename1 = $name;
            $usuccess = $imgfile->move($imgDestinationPath, $filename1);
        }
        if($request->hasFile('CNICIMG')) {
            $imgfile = $request->file('CNICIMG');
            $imgpath = 'storage/CNICIMG';
            File::makeDirectory($imgpath, $mode = 0777, true, true);
            $imgDestinationPath = $imgpath.'/';
            $name = time()."_".$imgfile->getClientOriginalName();
            $request->CNICIMG=$name;
            $filename1 = $name;
            $usuccess = $imgfile->move($imgDestinationPath, $filename1);
        }
        $request->country_id=$request->Country;
        $request->state_id=$request->State;
        $input['Username']=$request->Username;
        $input['Password']=Hash::make($request->Password);
        $input['Designation']=$request->Designation;
        $input['email']=$request->email;
        $input['EmployeeName']=$request->EmployeeName;
        $input['FatherName']=$request->FatherName;
        $input['SpouseName']=$request->SpouseName;
        $input['DOB']=$request->DOB;
        $input['CNIC']=$request->CNIC;
        $input['FamilyCode']=$request->FamilyCode;
        $input['Nationality']=$request->Nationality;
        $input['PostalAddress']=$request->PostalAddress;
        $input['ContactNumber']=$request->ContactNumber;
        $input['EmployeeNo']=$request->EmployeeNo;
        $input['Gender']=$request->Gender;
        $input['religion']=$request->religion;
        $input['NatureofEmployment']=$request->NatureofEmployment;
        $input['DateOfDeployment']=$request->DateOfDeployment;
        $input['NTN']=$request->NTN;
        $input['EOBINO']=$request->EOBINO;
        $input['InsuranceEntitlement']=$request->InsuranceEntitlement;
        $input['BankAccountNo']=$request->BankAccountNo;
        $input['BankBranchName']=$request->BankBranchName;
        $input['BankBranchCity']=$request->BankBranchCity;
        $input['BloodGroup']=$request->BloodGroup;
        $input['AcademicQalification']=$request->AcademicQalification;
        $input['Proviance']=$request->Proviance;
        $input['District']=$request->District;
        $input['MaritalStatus']=$request->MaritalStatus;
        $input['MonthlySalary']=$request->MonthlySalary;
        $input['Allowances']=$request->Allowances;
        $input['ResidentialCity']=$request->ResidentialCity;
        $input['Department']=$request->Department;
        $input['UserAvatar']=$request->UserAvatar;
        $input['CNICIMG']=$request->CNICIMG;
        $input['SelectBrand']=$request->SelectBrand;
        $input['ShopCity']=$request->ShopCity;
        $input['Shop']=$request->Shop;
        $input['Reporting1']=$request->Reporting1;
        $input['Reporting2']=$request->Reporting2;
        $input['MailingAddress']=$request->MailingAddress;
        $input['Country']=$request->Country;
        $input['country_id']=$request->country_id;
        $input['state_id']=$request->state_id;
        $input['State']=$request->State;
        $input['ZipCode']=$request->ZipCode;
        $input['EmployeeStatus']=$request->EmployeeStatus;
        $input['AttenStartTime']=$request->AttenStartTime;
        $input['Monday']=$request->Monday;
        $input['Tuesday']=$request->Tuesday;
        $input['Wednesday']=$request->Wednesday;
        $input['Thursday']=$request->Thursday;
        $input['Friday']=$request->Friday;
        $input['Saturday']=$request->Saturday;
        $input['Sunday']=$request->Sunday;

       $employee = Employee::create($input);
       $employee_role_data['emp_id']=$employee['id'];
       $employee_role_data['role_id']=$request->Designation;
       $employee_role=Role_employee::create($employee_role_data);
        Flash::success('Employee saved successfully.');
        
        return redirect(route('admin.employees.create'));
    }

    /**
     * Display the specified Employee.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $employee = Employee::find($id);
        $country=country::find($employee->Nationality);
        $employee->Nationality=$country->name;
        $role=Role::find($employee->Designation);
        $employee->Designation=$role->name;
        $city=city::find($employee->District);
        $employee->District=$city->name;
        $state=state::find($employee->Proviance);
        $employee->Proviance=$state->name;
        $employee->UserAvatar=URL::to('/storage/UserAvatar/').'/'.$employee->UserAvatar;
        if (empty($employee)) {
            Flash::error('Employee not found');

            return redirect(route('employees.index'));
        }

        return view('admin.employees.show')->with('employee', $employee);
    }

    /**
     * Show the form for editing the specified Employee.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
         $employee = Employee::find($id);

        if (empty($employee)) {
            Flash::error('Employee not found');

            return redirect(route('employees.index'));
        }
        $city=city::find($employee->ShopCity);
            $storeCity[$city->id]=$city->name;
            $cityid=$city->id;
            $shops=array();
            $shops[null]="Select Shop";
        $shops_resuls=Shop::where([['city_id',$employee->ShopCity],['brand_id',$employee->SelectBrand]])->get();
        foreach ($shops_resuls as $key => $value) {
            $shops[$value->id]=$value->name;
        }
        if($employee->Shop) {
            $employeeshop=$employee->Shop;
        }
        else
        {
            $employeeshop='';
        }
        $reporting1=array();
        if(!empty($employee->Reporting1)) {
        $reporter1=Employee::find($employee->Reporting1);
            $reporting1[$reporter1->id]=$reporter1->name;;
        }
        $reporting2=array();
        if(!empty($employee->Reporting2)) {
        $reporter2=Employee::find($employee->Reporting2);
            $reporting2[$reporter2->id]=$reporter2->name;;
        }
        $allbrands= Brand::where('deleted_at',NULL)->get();
        $brands[null]='-- Select Brand--';
        foreach ($allbrands as $key => $value) {
            $brands[$value->id]=$value->BrandName;
        }
        $designation_result= role::where([['name', '!=', 'Admin'],['name', '!=', 'Employee']])->get();
        $designations[null]='-- Designation--';
        foreach ($designation_result as $key => $value) {
            $designations[$value->id]=$value->name;
        }
        $countries[null]='-- Country--';
        $countries_result=country::all();
        foreach ($countries_result as $key => $value) {
            $countries[$value->id]=$value->name;
        }
        $selected_country=array();
        $country_id='';
        $selected_nationality='';
        if(!empty($employee->Country)) {
        $country=country::find($employee->Country);
            $selected_country=$country->id;
            $country_id=$country->id;
            $selected_nationality=$employee->Nationality;
        }
        $states_result=state::all();
        foreach ($states_result as $key => $value) {
            $states[$value->id]=$value->name;
        }
        $selected_state='';
        if(!empty($employee->state_id)) {
        $state=state::find($employee->state_id);
            $selected_state=$state->id;
        }
        $states_result=state::where('country_id',$selected_country)->get();
        $country_states[null]='--Select Province--';
        foreach ($states_result as $key => $value) {
            $country_states[$value->id]=$value->name;
        }
        $district_result=city::where('state_id',$selected_state)->get();
        $states_cities[null]='--Select District--';
        foreach ($district_result as $key => $value) {
            $states_cities[$value->id]=$value->name;
        }
        $city=city::find($employee->ShopCity);
            $selected_city=$city->id;
        if(!empty($employee->SelectBrand)) {
        $brand_cities_result=Brand::find($employee->SelectBrand)->cities;
        foreach ($brand_cities_result as $key => $value) {
        $city_ids[]=$value->city_id;
        }
        }
        $cities_result=city::whereIn('id',$city_ids)->get();
        $brandcities[null]='--Select Brand City--';
        foreach ($cities_result as $key => $value) {
            $brandcities[$value->id]=$value->name;
        }
        $brand_employees_result=Brand::find($employee->SelectBrand)->employee;
        $brand_sup[null]='--Select--';
        foreach ($brand_employees_result as $key => $value) {
        $employee_role_result=Employee::find($value->id)->employee_role;
        foreach ($employee_role_result as $key1 => $value1) {
            if($value1->name=='Supervisors')
            {
            $brand_sup[$value->id]=$value->EmployeeName;
            }
        }
        }
        $selected_reporting1='';
        if(!empty($employee->Reporting1)) {
        $reporting1_result=Employee::find($employee->Reporting1);
            $selected_reporting1=$reporting1_result->id;
        }
        $selected_reporting2='';
        if(!empty($employee->Reporting2)) {
        $reporting2_result=Employee::find($employee->Reporting2);
            $selected_reporting2=$reporting2_result->id;
        }
        $employee->UserAvatar=URL::to('/storage/UserAvatar/').'/'.$employee->UserAvatar;        
        $employee->CNICIMG=URL::to('/storage/CNICIMG/').'/'.$employee->CNICIMG;        
        return view('admin.employees.edit')->with('employee', $employee)->with('brands', $brands)->with('shops', $shops)->with('designations', $designations)->with('countries',$countries)->with('countrystates',$country_states)->with('selected_province',$selected_state)->with('states',$states)->with('storeCity',$storeCity)->with('selected_brand',$employee->SelectBrand)->with('shop',$employeeshop)->with('reporting1',$brand_sup)->with('reporting2',$brand_sup)->with('selectcountry',$selected_country)->with('selectnationality',$selected_nationality)->with('selectstate',$selected_state)->with('districts',$states_cities)->with('selected_district',$selected_city)->with('shop_cities',$brandcities)->with('selected_brand_city',$employee->ShopCity)->with('selected_reporting1',$selected_reporting1)->with('selected_reporting2',$selected_reporting2)->with('selected_shop',$employeeshop);

    }

    /**
     * Update the specified Employee in storage.
     *
     * @param  int              $id
     * @param UpdateEmployeeRequest $request
     *
     * @return Response
     */
    public function update(Request $request,$id)
    {
        $employee = Employee::find($id);
        if($employee->email==$request->email) {
            $request->validate([
            'Designation' => 'required',
            'EmployeeName' => 'required|max:120',
            'Gender' => 'required',
            'DateOfDeployment' => 'required',
            'SelectBrand' => 'required',
            'ShopCity' => 'required',
            'EmployeeStatus' => 'required',
            'AttenStartTime' => 'required',
            'Department' => 'required'
        ]);
        }
        else {
            $request->validate([
            'Designation' => 'required',
            'EmployeeName' => 'required|max:120',
            'Gender' => 'required',
            'DateOfDeployment' => 'required',
            'SelectBrand' => 'required',
            'ShopCity' => 'required',
            'EmployeeStatus' => 'required',
            'AttenStartTime' => 'required',
            'Department' => 'required'
        ]);
        }
        
        if($request->hasFile('UserAvatar')) {
            $imgfile = $request->file('UserAvatar');
            $imgpath = 'storage/UserAvatar';
            File::makeDirectory($imgpath, $mode = 0777, true, true);
            $imgDestinationPath = $imgpath.'/';
            $name = time()."_".$imgfile->getClientOriginalName();
            $request->UserAvatar=$name;
            $filename1 = $name;
            $usuccess = $imgfile->move($imgDestinationPath, $filename1);
            $old_image=$imgDestinationPath.$employee->UserAvatar;
            if (File::exists($old_image)) {
            File::delete($old_image);
            }
            $input['UserAvatar']=$request->UserAvatar;
        }
        if($request->hasFile('CNICIMG')) {
            $imgfile = $request->file('CNICIMG');
            $imgpath = 'storage/CNICIMG';
            File::makeDirectory($imgpath, $mode = 0777, true, true);
            $imgDestinationPath = $imgpath.'/';
            $name = time()."_".$imgfile->getClientOriginalName();
            $request->CNICIMG=$name;
            $filename1 = $name;
            $usuccess = $imgfile->move($imgDestinationPath, $filename1);
            $old_image=$imgDestinationPath.$employee->CNICIMG;
            if (File::exists($old_image)) {
            File::delete($old_image);
            }
            $input['CNICIMG']=$request->CNICIMG;
        }
        $request->country_id.=$request->Country;
        $request->state_id.=$request->State;
        if(!empty($request->Password)) {
        $input['Password']=Hash::make($request->Password);
        }
        $input['Designation']=$request->Designation;
        $input['email']=$request->email;
        $input['EmployeeName']=$request->EmployeeName;
        $input['FatherName']=$request->FatherName;
        $input['SpouseName']=$request->SpouseName;
        $input['DOB']=$request->DOB;
        $input['CNIC']=$request->CNIC;
        $input['FamilyCode']=$request->FamilyCode;
        $input['Nationality']=$request->Nationality;
        $input['PostalAddress']=$request->PostalAddress;
        $input['ContactNumber']=$request->ContactNumber;
        $input['EmployeeNo']=$request->EmployeeNo;
        $input['Gender']=$request->Gender;
        $input['religion']=$request->religion;
        $input['NatureofEmployment']=$request->NatureofEmployment;
        $input['DateOfDeployment']=$request->DateOfDeployment;
        $input['NTN']=$request->NTN;
        $input['EOBINO']=$request->EOBINO;
        $input['InsuranceEntitlement']=$request->InsuranceEntitlement;
        $input['BankAccountNo']=$request->BankAccountNo;
        $input['BankBranchName']=$request->BankBranchName;
        $input['BankBranchCity']=$request->BankBranchCity;
        $input['BloodGroup']=$request->BloodGroup;
        $input['AcademicQalification']=$request->AcademicQalification;
        $input['Proviance']=$request->Proviance;
        $input['District']=$request->District;
        $input['MaritalStatus']=$request->MaritalStatus;
        $input['MonthlySalary']=$request->MonthlySalary;
        $input['Allowances']=$request->Allowances;
        $input['ResidentialCity']=$request->ResidentialCity;
        $input['Department']=$request->Department;
        $input['SelectBrand']=$request->SelectBrand;
        $input['ShopCity']=$request->ShopCity;
        $input['Shop']=$request->Shop;
        $input['Reporting1']=$request->Reporting1;
        $input['Reporting2']=$request->Reporting2;
        $input['MailingAddress']=$request->MailingAddress;
        $input['Country']=$request->Country;
        $input['country_id']=$request->country_id;
        $input['state_id']=$request->state_id;
        // $input['State']=$request->State;
        $input['ZipCode']=$request->ZipCode;
        $input['EmployeeStatus']=$request->EmployeeStatus;
        $input['AttenStartTime']=$request->AttenStartTime;
        $input['Monday']=$request->Monday;
        $input['Tuesday']=$request->Tuesday;
        $input['Wednesday']=$request->Wednesday;
        $input['Thursday']=$request->Thursday;
        $input['Friday']=$request->Friday;
        $input['Saturday']=$request->Saturday;
        $input['Sunday']=$request->Sunday;
        if (empty($employee)) {
            Flash::error('Employee not found');

            return redirect(route('employees.index'));
        }

    $employee=Employee::whereId($id)->update($input);
    $emp_role=Role_employee::where('emp_id',$id)->get();
    if(count($emp_role)>0) {
        $employee_role_data['role_id']=$request->Designation;
        $employee_role=Role_employee::where('emp_id',$id)->update($employee_role_data);
    }
    else {
        $employee_role_data['emp_id']=$id;
       $employee_role_data['role_id']=$request->Designation;
       $employee_role=Role_employee::create($employee_role_data);
    }
    

        Flash::success('Employee updated successfully.');
        // $redirectUrl='/admin/employees/'.$id.'/edit';
        return redirect(route('admin.employees.edit',['employees'=>$id]));
    }

    /**
     * Remove the specified Employee from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
      public function getModalDelete($id = null)
      {
          $error = '';
          $model = '';
          $confirm_route =  route('admin.employees.delete',['id'=>$id]);
          return View('admin.layouts/modal_confirmation', compact('error','model', 'confirm_route'));

      }

       public function getDelete($id = null)
       {
           $sample = Employee::destroy($id);

           // Redirect to the group management page
           return redirect(route('admin.employees.index'))->with('success', Lang::get('message.success.delete'));

       }

       public function getcities($brand){
        $cities = Brands::where('id',$brand)->get();
        return $cities;
       }

       public function getstores($city){
           $stores = Stores::where('city_id',$city)->get();
        return $stores;
       }

       public function getreporters($city){
           $reporter = Employee::where('District',$city)->get();
        return $reporter;
       }
       public function states($id)
       {
        $states_result=state::where('country_id',$id)->get();
        echo '<option value="">Select Province</option>';
        foreach ($states_result as $key => $value) {
            echo '<option value="'.$value->id.'">'.$value->name.'</option>';
        }

       }
       public function countrystates($id)
       {
        $states_result=state::where('country_id',$id)->get();
        echo '<option value="">Select State</option>';
        foreach ($states_result as $key => $value) {
            echo '<option value="'.$value->id.'">'.$value->name.'</option>';
        }

       }
       public function districts($id)
       {
        $districts_result=city::where('state_id',$id)->get();
        echo '<option value="">Select District</option>';
        foreach ($districts_result as $key => $value) {
            echo '<option value="'.$value->id.'">'.$value->name.'</option>';
        }

       }
       public function BrandCities($id)
       {
        $brand_cities_result=Brand::find($id)->cities;
        foreach ($brand_cities_result as $key => $value) {
        $city_ids[]=$value->city_id;
        }
        $cities_result=city::find($city_ids);
        echo '<option value="">Select Brand City</option>';
        foreach ($cities_result as $key => $value) {
            echo '<option value="'.$value->id.'">'.$value->name.'</option>';
        }

       }
       public function Brandstores($id,$brandId)
       {
        $shops_result=Shop::where([['city_id',$id],['brand_id',$brandId]])->get();
        echo '<option value="">Select Shop</option>';
        foreach ($shops_result as $key => $value) {
            echo '<option value="'.$value->id.'">'.$value->name.'</option>';
        }
       }
       public function Brandsupervisors($id)
       {
        $brand_employees_result=Brand::find($id)->employee;
        echo '<option value="">--Select--</option>';
        foreach ($brand_employees_result as $key => $value) {
        $employee_role_result=Employee::find($value->id)->employee_role;
        foreach ($employee_role_result as $key1 => $value1) {
            if($value1->name=='Supervisors')
            {
            echo '<option value="'.$value->id.'">'.$value->EmployeeName.'</option>';                
            }
        }
        }
       }
       public function getdata(Request $request) {
        $from=$request->dateFrom;
        $to=$request->dateTo;
        if(!empty($from) && !empty($to)) {
        $employees=Employee::whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59'])->where('deleted_at',NULL)->get();
        }
        else
        {
            $employees = Employee::where('deleted_at',NULL)->get();
        }
        foreach ($employees as $key => $value) {
            $role=Role::find($value->Designation);
            $employees[$key]->Designation=$role->name;
            $city=city::find($value->District);
            $employees[$key]->District=$city->name;
        }
        return view('admin.employees.index')
            ->with('employees', $employees)->with('from',$from)->with('to',$to);
       }

}
