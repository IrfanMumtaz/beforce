<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\CreateBrandsRequest;
use App\Http\Requests\UpdateBrandsRequest;
use App\Repositories\BrandsRepository;
use App\Http\Controllers\AppBaseController as InfyOmBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use App\Models\Brands;
use App\country;
use App\Brand_city;
use App\Brand_employee;
use App\Brand;
use App\city;
use App\role;
use App\Employee;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use Carbon\Carbon;

class BrandsController extends InfyOmBaseController
{
    /** @var  BrandsRepository */
    private $brandsRepository;

    public function __construct(BrandsRepository $brandsRepo)
    {
        
	
    }

    /**
     * Display a listing of the Brands.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {

        $brands = Brand::where('deleted_at',NULL)->get();
        
        return view('admin.brands.index')
            ->with('brands', $brands);
    }

    /**
     * Show the form for creating a new Brands.
     *
     * @return Response
     */
    public function create()
    {
        $manager_result=Role::where('slug','Managers')->get();
        foreach ($manager_result as $key => $value) {
         $manger_role_id=$value->id;
        }
        $Supervisors_result=Role::where('slug','Supervisors')->get();
        foreach ($Supervisors_result as $key => $value) {
         $supervisors_role_id=$value->id;
        }
        $Merchandisers_result=Role::where('slug','Merchandisers')->get();
        foreach ($Merchandisers_result as $key => $value) {
         $merchandisers_role_id=$value->id;
        }
        $DamageVerification_result=Role::where('slug','DamageVerification')->get();
        foreach ($DamageVerification_result as $key => $value) {
         $damageVerification_role_id=$value->id;
        }
        $Emloyee_result=Role::where('slug','Employee')->get();
        foreach ($Emloyee_result as $key => $value) {
         $emloyee_role_id=$value->id;
        }
        $emp=array();
        $employees_data=Employee::where([['Designation',$emloyee_role_id],['EmployeeStatus','Active']])->get();
        foreach ($employees_data as $key => $value) {
            $emp[$value->id]=$value->EmployeeName;
        }
        $sup=array();
        $supervisor_data=Employee::where([['Designation',$supervisors_role_id],['EmployeeStatus','Active']])->get();
        foreach ($supervisor_data as $key => $value) {
            $sup[$value->id]=$value->EmployeeName;
        }
        $manager=array();
        $managers_data=Employee::where([['Designation',$manger_role_id],['EmployeeStatus','Active']])->get();
        foreach ($managers_data as $key => $value) {
            $manager[$value->id]=$value->EmployeeName;
        }
        $merchandiser=array();
        $merchandiser_data=Employee::where([['Designation',$merchandisers_role_id],['EmployeeStatus','Active']])->get();
        foreach ($merchandiser_data as $key => $value) {
            $merchandiser[$value->id]=$value->EmployeeName;
        }
        $damage=array();
        $damage_data=Employee::where([['Designation',$damageVerification_role_id],['EmployeeStatus','Active']])->get();
        foreach ($damage_data as $key => $value) {
            $damage[$value->id]=$value->EmployeeName;
        }

        $cities_result=country::find(1)->cities;
        foreach ($cities_result as $key => $value) {
            $cities[$value->id]=$value->name;
        }

	
  return view('admin.brands.create')->with('emp',$emp)->with('damage',$damage)->with('merchandiser',$merchandiser)->with('manager',$manager)->with('supervisor',$sup)->with('cities',$cities);

    }

    /**
     * Store a newly created Brands in storage.
     *
     * @param CreateBrandsRequest $request
     *
     * @return Response
     */
    public function store(CreateBrandsRequest $request)
    {

        if (empty($brands)) {
            Flash::error('Brands not found');
	}
    $employee_ids=array();
    $data['BrandName']=$request->BrandName;
    $data['Description']=$request->Description;
	$brand =Brands::create($data);
    if(!empty($request->BrandCities)) {
    $brand_cities_data['brand_id']=$brand['id'];
    foreach ($request->BrandCities as $key => $value) {
        $brand_cities_data['city_id']=$value;
        $brand_cities =Brand_city::create($brand_cities_data);
    }
    }
    
    if(!empty($request->SelectEmloyee)) {
    $brand_employees_data['brand_id']=$brand['id'];
	foreach ($request->SelectEmloyee as $key => $value) {
        $brand_employees_data['emp_id']=$value;
        $employee_ids[]=$value;
        $brand_employees =Brand_employee::create($brand_employees_data);
    }
    }

    $brand_DamageVerification_data['brand_id']=$brand['id'];
    if(!empty($request->SelectDamageVerification)){
    foreach ($request->SelectDamageVerification as $key => $value) {
        $brand_DamageVerification_data['emp_id']=$value;
        $employee_ids[]=$value;
        $brand_DamageVerification =Brand_employee::create($brand_DamageVerification_data);
    }
    }
    
    if(!empty($request->SelectMerchandisers)) {
    $brand_Merchandisers_data['brand_id']=$brand['id'];
    foreach ($request->SelectMerchandisers as $key => $value) {
        $brand_Merchandisers_data['emp_id']=$value;
        $employee_ids[]=$value;
        $brand_Merchandisers =Brand_employee::create($brand_Merchandisers_data);
    }
    }
    
    if(!empty($request->SelectSupervisors)) {
    $brand_Supervisors_data['brand_id']=$brand['id'];
    foreach ($request->SelectSupervisors as $key => $value) {
        $brand_Supervisors_data['emp_id']=$value;
        $employee_ids[]=$value;
        $brand_Supervisors =Brand_employee::create($brand_Supervisors_data);
    }
    }
    
    if(!empty($request->SelectManagers)) {
    $brand_Managers_data['brand_id']=$brand['id'];
    foreach ($request->SelectManagers as $key => $value) {
        $brand_Managers_data['emp_id']=$value;
        $employee_ids[]=$value;
        $brand_Managers =Brand_employee::create($brand_Managers_data);
    }
    }
//     $input['SelectBrand']=$brand['id'];		
// 	for($i=0; $i<count($employee_ids); $i++)
// 	{	
//     $employee=Employee::whereId($employee_ids[$i])->update($input);
// 	}


	

	        // $brands = $this->brandsRepository->create($data);

        Flash::success('Brands saved successfully.'.$brand->BrandName);
        return redirect(route('admin.brands.create'));
    }

    /**
     * Display the specified Brands.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $Supervisors=array();
        $Managers=array();
        $Merchandisers=array();
        $DamageVerification=array();
        $Emloyee=array();
        $brands = Brand::find($id);
        $brand_cities_result=Brand::find($id)->cities;
        foreach ($brand_cities_result as $key => $value) {
        $city_ids[]=$value->city_id;
        }
        $cities_result=city::find($city_ids);
        foreach ($cities_result as $key => $value) {
            $cities[]=$value->name;
        }
        $brand_employees_result=Brand::find($id)->employee;
        foreach ($brand_employees_result as $key => $value) {
        $employee_role_result=Employee::find($value->id)->employee_role;
        foreach ($employee_role_result as $key1 => $value1) {
            if($value1->name=='Supervisors')
            {
                $Supervisors[$value->id]=$value->EmployeeName;
            }
            else if($value1->name=='Managers')
            {
                $Managers[$value->id]=$value->EmployeeName;
            }
            else if($value1->name=='Merchandisers')
            {
                $Merchandisers[$value->id]=$value->EmployeeName;
            }
            else if($value1->name=='DamageVerification')
            {
                $DamageVerification[$value->id]=$value->EmployeeName;
            }
            else if($value1->name=='Emloyee')
            {
                $Emloyee[$value->id]=$value->EmployeeName;
            }
        }
        }
        $brands['SelectSupervisors'].=implode(",", $Supervisors);
        $brands['SelectManagers'].=implode(",", $Managers);
        $brands['SelectMerchandisers'].=implode(",", $Merchandisers);
        $brands['SelectDamageVerification'].=implode(",", $DamageVerification);
        $brands['SelectEmloyee'].=implode(",", $Emloyee);
        $brands['BrandCities'].=implode(",", $cities);

        if (empty($brands)) {
            Flash::error('Brands not found');

            return redirect(route('brands.index'));
        }

        return view('admin.brands.show')->with('brands', $brands);
    }

    /**
     * Show the form for editing the specified Brands.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {

	$brands = Brand::find($id);

        if (empty($brands)) {
            Flash::error('Brands not found');


        return redirect(route('brands.index'));
        }
        
        $brand_cities_result=Brand::find($id)->cities;
        foreach ($brand_cities_result as $key => $value) {
        $city_ids[]=$value->city_id;
        }
        $cities_result=city::find($city_ids);
        foreach ($cities_result as $key => $value) {
            $cities[]=$value->name;
        }
        $selected_supervisor=array();
        $selected_manager=array();
        $selected_merchant=array();
        $selected_damage=array();
        $selected_employee=array();
        $brand_employees_result=Brand::find($id)->employee;
        foreach ($brand_employees_result as $key => $value) {
        $employee_role_result=Employee::find($value->id)->employee_role;
        foreach ($employee_role_result as $key1 => $value1) {
            if($value1->name=='Supervisors')
            {
                $selected_supervisor[]=$value->id;
            }
            else if($value1->name=='Managers')
            {
                $selected_manager[]=$value->id;
            }
            else if($value1->name=='Merchandisers')
            {
                $selected_merchant[]=$value->id;
            }
            else if($value1->name=='DamageVerification')
            {
                $selected_damage[]=$value->id;
            }
            else if($value1->name=='Emloyee')
            {
                $selected_employee[]=$value->id;
            }
        }
        }
        $manager_result=Role::where('slug','Managers')->get();
        foreach ($manager_result as $key => $value) {
         $manger_role_id=$value->id;
        }
        $Supervisors_result=Role::where('slug','Supervisors')->get();
        foreach ($Supervisors_result as $key => $value) {
         $supervisors_role_id=$value->id;
        }
        $Merchandisers_result=Role::where('slug','Merchandisers')->get();
        foreach ($Merchandisers_result as $key => $value) {
         $merchandisers_role_id=$value->id;
        }
        $DamageVerification_result=Role::where('slug','DamageVerification')->get();
        foreach ($DamageVerification_result as $key => $value) {
         $damageVerification_role_id=$value->id;
        }
        $Emloyee_result=Role::where('slug','Emloyee')->get();
        foreach ($Emloyee_result as $key => $value) {
         $emloyee_role_id=$value->id;
        }
        $emp=array();
        if(!empty($emloyee_role_id)) {
        $employees_data=Employee::where([['Designation',$emloyee_role_id],['EmployeeStatus','Active']])->get();
        foreach ($employees_data as $key => $value) {
            $emp[$value->id]=$value->EmployeeName;
        }
        }
        $sup=array();
        if(!empty($supervisors_role_id)) {
        $supervisor_data=Employee::where([['Designation',$supervisors_role_id],['EmployeeStatus','Active']])->get();
        foreach ($supervisor_data as $key => $value) {
            $sup[$value->id]=$value->EmployeeName;
        }
        }
        $manager=array();
        if(!empty($manger_role_id)) {
        $managers_data=Employee::where([['Designation',$manger_role_id],['EmployeeStatus','Active']])->get();
        foreach ($managers_data as $key => $value) {
            $manager[$value->id]=$value->EmployeeName;
        }
        }
        $merchandiser=array();
        if(!empty($merchandisers_role_id)){
        $merchandiser_data=Employee::where([['Designation',$merchandisers_role_id],['EmployeeStatus','Active']])->get();
        foreach ($merchandiser_data as $key => $value) {
            $merchandiser[$value->id]=$value->EmployeeName;
        }
        }
        $damage=array();
        if(!empty($damageVerification_role_id)) {
        $damage_data=Employee::where([['Designation',$damageVerification_role_id],['EmployeeStatus','Active']])->get();
        foreach ($damage_data as $key => $value) {
            $damage[$value->id]=$value->EmployeeName;
        }
        }

         $cities_result=country::find(1)->cities;
         foreach ($cities_result as $key => $value) {
            $cities[$value->id]=$value->name;
         }
         $cities_result=Brand::find($id)->cities;
         foreach ($cities_result as $key => $value) {
            $selected_city[]=$value->city_id;
         }

	        return view('admin.brands.edit')->with('emp',$emp)->with('damage',$damage)->with('merchandiser',$merchandiser)->with('manager',$manager)->with('supervisor',$sup)->with('brands', $brands)->with('cities',$cities)->with('selectmanager',$selected_manager)->with('selectsupervisor',$selected_supervisor)->with('selectmerchant',$selected_merchant)->with('selectdamage',$selected_damage)->with('selectemployee',$selected_employee)->with('selectcity',$selected_city);

    }

    /**
     * Update the specified Brands in storage.
     *
     * @param  int              $id
     * @param UpdateBrandsRequest $request
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        $request->validate([
            'BrandName'=> 'required',
            'BrandCities'=> 'required'
            ]);
    $employee_ids=array();
    $data['BrandName']=$request->BrandName;
    $data['Description']=$request->Description;
    $brand =Brands::whereId($id)->update($data);

    $brand_cities =Brand_city::where('brand_id', $id)->delete();
    $brand_cities_data['brand_id']=$id;
    foreach ($request->BrandCities as $key => $value) {
        $brand_cities_data['city_id']=$value;
        $brand_cities =Brand_city::create($brand_cities_data);
    }    

    Brand_employee::where('brand_id', $id)->delete();
    $brand_employees_data['brand_id']=$id;
    if(!empty($request->SelectEmloyee)) {
    foreach ($request->SelectEmloyee as $key => $value) {
        $brand_employees_data['emp_id']=$value;
        $employee_ids[]=$value;
        $brand_employees =Brand_employee::create($brand_employees_data);
    }
    }

    $brand_DamageVerification_data['brand_id']=$id;
    if(!empty($request->SelectDamageVerification)) {
    foreach ($request->SelectDamageVerification as $key => $value) {
        $brand_DamageVerification_data['emp_id']=$value;
        $employee_ids[]=$value;
        $brand_DamageVerification =Brand_employee::create($brand_DamageVerification_data);
    }
    }

    $brand_Merchandisers_data['brand_id']=$id;
    if(!empty($request->SelectMerchandisers)) {
    foreach ($request->SelectMerchandisers as $key => $value) {
        $brand_Merchandisers_data['emp_id']=$value;
        $employee_ids[]=$value;
        $brand_Merchandisers =Brand_employee::create($brand_Merchandisers_data);
    }
    }

    $brand_Supervisors_data['brand_id']=$id;
    if(!empty($request->SelectSupervisors)) {
    foreach ($request->SelectSupervisors as $key => $value) {
        $brand_Supervisors_data['emp_id']=$value;
        $employee_ids[]=$value;
        $brand_Supervisors =Brand_employee::create($brand_Supervisors_data);
    }
    }

    $brand_Managers_data['brand_id']=$id;
    if(!empty($request->SelectManagers)) {
    foreach ($request->SelectManagers as $key => $value) {
        $brand_Managers_data['emp_id']=$value;
        $employee_ids[]=$value;
        $brand_Managers =Brand_employee::create($brand_Managers_data);
    }
    }
    // $input['SelectBrand']=$id;      
    // for($i=0; $i<count($employee_ids); $i++)
    // {   
    // $employee=Employee::whereId($employee_ids[$i])->update($input);
    // }

        
                        if($request->has('BrandCities')){
	                    $request->merge(['BrandCities' => 1]);
	                    }
                        else{
                        $request->merge(['BrandCities' => 0]);
                         }

        if (empty($request->BrandName)) {
            Flash::error('Brands not found');

            return redirect(route('brands.index'));
        }

        Flash::success('Brands updated successfully.');

        return redirect(route('admin.brands.edit',['brands'=>$id]));
    }

    /**
     * Remove the specified Brands from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
      public function getModalDelete($id = null)
      {
          $error = '';
          $model = '';
          $confirm_route =  route('admin.brands.delete',['id'=>$id]);
          return View('admin.layouts/modal_confirmation', compact('error','model', 'confirm_route'));

      }

       public function getDelete($id = null)
       {
           $sample = Brands::destroy($id);

           // Redirect to the group management page
           return redirect(route('admin.brands.index'))->with('success', Lang::get('message.success.delete'));

       }
       public function cities($id)
       {
        $brand_cities_result=Brand::find($id)->cities;
        foreach ($brand_cities_result as $key => $value) {
        $city_ids[]=$value->city_id;
        }
        $cities_result=city::find($city_ids);
        echo '<option value="">--Select City--</option>';
        foreach ($cities_result as $key => $value) {
            echo '<option value="'.$value->id.'">'.$value->name.'</option>';
            // $cities[$value->id]=$value->name;
        }

        // return $cities;
       }
       public function getdata(Request $request) {
        $from=$request->dateFrom;
        $to=$request->dateTo;
        if(!empty($from) && !empty($to)) {
        $brands=Brand::whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59'])->where('deleted_at',NULL)->get();
        }
        else {
            $brands = Brand::where('deleted_at',NULL)->get();
        }
        return view('admin.brands.index')
            ->with('brands', $brands)->with('from',$from)->with('to',$to);
       }

}
