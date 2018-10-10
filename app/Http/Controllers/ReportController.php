<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\AppBaseController as InfyOmBaseController;
use URL;
use App\Brand;
use App\Category;
use App\Sku;
use App\city;
use \stdClass;
use App\Shop;
use App\Employee;
use App\Skutarget;
use DB;
use App\country;
use App\role;
use DateTime;

class ReportController extends Controller
{
    public function index()
    {
    	return redirect('/admin');
    }
    // daily sale report
    public function daily_sale_report()
    {
        $brands_result = Brand::where('deleted_at',NULL)->get();
        $brands=array();
        $brands[null]='--All Brands--';
        foreach ($brands_result as $key => $value) {
        	$brands[$value->id]=$value->BrandName;
        }
        return view('admin.reports.daily_sale_report')->with('brands', $brands);
    }
    public function getsale()
    {
        $sales_result= \DB::table('sales')->whereBetween('created_at', ['2018-09-01'.' 00:00:00', '2018-09-30'.' 23:59:59'])->where('empId',47)->where('Location',34)->get();
        echo "<pre>";
        print_r($sales_result);
        echo "</pre>";
        die();
    }

    public function get_daily_sale_report(Request $request)
    {	
    	$request->validate([
            'brands'=> 'required',
            'from'=> 'required',
            'to'=> 'required'
            ]);
    	$results = array();
    	$brands=array();
    	$cities=array();
    	$shops=array();
    	$BA=array();
    	$coversion=array();
    	$interception=array();
    	$ic_achieve=array();
    	$skus=array();
    	$skutargets=array();
    	$skusales=array();
    	$total_targets=array();
    	$total_sales=array();
    	$sku_achieve=array();
    	$overall_total_targets=array();
    	$overall_total_sales=array();
    	$overall_ts_achieve=array();
    	$total_interception=array();
    	$total_coversion=array();
    	$total_ic_achieve=array();
    	$overall_skutargets=array();
    	$overall_skusales=array();
    	$overall_sku_achieve=array();
    	$ts_achieve=array();
    	$grand_total_interception=0;
    	$grand_total_coversion=0;
    	$grand_total_targets=0;
    	$grand_total_sales=0;
    	$grand_skutargets=array();
    	$grand_skusales=array();
    	$grand_sku_achieve=array();
    	$brands_result = Brand::where('deleted_at',NULL)->get();
        $brands[null]='--All Brands--';
        foreach ($brands_result as $key => $value) {
        	$brands[$value->id]=$value->BrandName;
        }
        $category_res=Category::where([['brand_id',$request->brands],['Competition',0]])->get();
        foreach ($category_res as $key => $value) {
        	$skus_resuls=Sku::where('category_id',$value->id)->get();
        	foreach ($skus_resuls as $skus_key => $skus_value) {
        		$skus[]=$skus_value->name;

        	}
        }

    	$brand_cities_result=Brand::find($request->brands)->cities;
        foreach ($brand_cities_result as $key => $value) {
        	$city_result=city::find($value->city_id);
            $cities[]=$city_result->name;
            $overall_total_targets[$city_result->name]=0;
            $overall_total_sales[$city_result->name]=0;
            $total_interception[$city_result->name]=0;
        	$total_coversion[$city_result->name]=0;

        	$shop_result=Shop::where([['city_id',$value->city_id],['brand_id',$request->brands]])->get();
        	foreach ($shop_result as $shop_result_key => $shop_result_value) {
        		$total_targets[$shop_result_value->name]=0;
        		$total_sales[$shop_result_value->name]=0;
        		$shops[]=$shop_result_value->name;
				$brand_emp=Employee::where([['EmployeeStatus','Active'],['Shop',$shop_result_value->id],['Designation',7]])->get();
				foreach ($brand_emp as $brand_emp_key => $brand_emp_value) {
					$BA[$shop_result_value->name]=$brand_emp_value->Username;
				    $sales_result= DB::table('sales')->whereBetween('created_at', [$request->from.' 00:00:00', $request->to.' 23:59:59'])->where('empId',$brand_emp_value->id)->where('Location',$brand_emp_value->Shop)->get();
				    $interception[$shop_result_value->name]=0;
				    $coversion[$shop_result_value->name]=0;
    				$emp_brand_result=Brand::find($brand_emp_value->SelectBrand);
    				$count=0;
    				if(count($sales_result)>0)  {
    				foreach ($sales_result as $sales_key => $sales_value) {
    					$cbrand_res=Category::find(trim($sales_value->cBrand,'"'));
    					$pbrand_res=Category::find(trim($sales_value->pBrand,'"'));
    					// if($cbrand_res->brand_id==$brand_emp_value->SelectBrand) {
    						if(isset($interception[$shop_result_value->name])) {
    						$interception[$shop_result_value->name]+=1;
    						}
    						else
    						{
    							$interception[$shop_result_value->name]=1;
    						}
    						$total_interception[$city_result->name]+=1;
    						$grand_total_interception+=1;
    					if($cbrand_res->id!=$pbrand_res->id && $sales_value->saleStatus==1 && $pbrand_res->Competition==1)
    					{
    						if(isset($coversion[$shop_result_value->name])) {
    						$coversion[$shop_result_value->name]+=1;
    						}
    						else
    						{
    							$coversion[$shop_result_value->name]=1;
    						}
    						$total_coversion[$city_result->name]+=1;
    						$grand_total_coversion+=1;
    					}
    				// }
    				}
				}

				}
        		$category_res=Category::where([['brand_id',$request->brands],['Competition',0]])->get();
		        foreach ($category_res as $cat_key => $cat_value) {
		        	$skus_resuls=Sku::where('category_id',$cat_value->id)->get();
		        	foreach ($skus_resuls as $skus_key => $skus_value) {
		        		$skutargets[$shop_result_value->name][$skus_value->name]=0;
		        		if(!isset($grand_skutargets[$skus_value->name])) {
		        			$grand_skutargets[$skus_value->name]=0;
		        		}
		        		if(!isset($grand_sku_achieve[$skus_value->name])) {
		        			$grand_sku_achieve[$skus_value->name]=0;
		        		}
		        		$skutargets_res=Skutarget::where([['sku_id',$skus_value->id],['shop_id',$shop_result_value->id],['status',1]])->whereBetween('created_at', [$request->from.' 00:00:00', $request->to.' 23:59:59'])->get();
		        		if(count($skutargets_res)<=0)
		        		{
		        		   $skutargets_res=Skutarget::where([['sku_id',$skus_value->id],['shop_id',$shop_result_value->id],['status',0]])->whereBetween('created_at', [$request->from.' 00:00:00', $request->to.' 23:59:59'])->get(); 
		        		}
		        		if(count($skutargets_res)>0) {
		        		foreach ($skutargets_res as $skutargets_res_key => $skutargets_res_value) {
		        			$skutargets[$shop_result_value->name][$skus_value->name]+=$skutargets_res_value->skutargets;
		        			if(isset($grand_skutargets[$skus_value->name])) {
		        			$grand_skutargets[$skus_value->name]+=$skutargets_res_value->skutargets;
		        			}
		        			if(isset($BA[$shop_result_value->name])) {
		        			if(isset($overall_skutargets[$city_result->name][$skus_value->name])){
		        			$overall_skutargets[$city_result->name][$skus_value->name]+=$skutargets_res_value->skutargets;
		        		}
		        		else
		        		{
		        			$overall_skutargets[$city_result->name][$skus_value->name]=$skutargets_res_value->skutargets;

		        		}
		        	}
		        			$total_targets[$shop_result_value->name]+=$skutargets_res_value->skutargets*$skus_value->Price;
		        		}
		        		}
		        		$skusales[$shop_result_value->name][$skus_value->name]=0;
		        		if(!isset($grand_skusales[$skus_value->name]))
		        		{
		        			$grand_skusales[$skus_value->name]=0;
		        		}
		        		$brand_emp=Employee::where([['EmployeeStatus','Active'],['Shop',$shop_result_value->id],['Designation',7]])->get();
						foreach ($brand_emp as $brand_emp_key => $brand_emp_value) {
						$BA[$shop_result_value->name]=$brand_emp_value->Username;
				    	$sales_result= \DB::table('sales')->whereBetween('created_at', [$request->from.' 00:00:00', $request->to.' 23:59:59'])->where('empId',$brand_emp_value->id)->where('Location',$brand_emp_value->Shop)->get();				    	
				    	foreach ($sales_result as $sales_result_key => $sales_result_value) {
				    	$cbrand_res=Category::find(trim($sales_value->cBrand,'"'));
				    	// if($cbrand_res->brand_id==$brand_emp_value->SelectBrand) {
		        		$skusales_res=DB::table('Orders')
		        		->where('storeId','=',$shop_result_value->id)
		        		->where('SKU','=',$skus_value->id)
		        		->where('salesId','=',$sales_result_value->id)
		        		->get();
		        		$noitem=0;
		        		foreach ($skusales_res as $skusales_res_key => $skusales_res_value) {
		        			$noitem+=$skusales_res_value->noItem;
		        		}
		        		$skusales[$shop_result_value->name][$skus_value->name]+=$noitem;
		        		if(isset($grand_skusales[$skus_value->name]))
		        		{
		        			$grand_skusales[$skus_value->name]+=$noitem;		        			
		        		} else { $grand_skusales[$skus_value->name]=$noitem; }
		        		$total_sales[$shop_result_value->name]+=$noitem*$skus_value->Price;
		        		$overall_total_sales[$city_result->name]+=$noitem*$skus_value->Price;
		        		$grand_total_sales+=$noitem*$skus_value->Price;
		        		if(!empty($overall_skusales[$city_result->name][$skus_value->name])){
		        		$overall_skusales[$city_result->name][$skus_value->name]+=$noitem;
		        		}
		        		else {
		        		$overall_skusales[$city_result->name][$skus_value->name]=$noitem;	
		        		}
		        		if(!empty($skusales[$shop_result_value->name][$skus_value->name]) && !empty($skutargets[$shop_result_value->name][$skus_value->name]) && $skutargets[$shop_result_value->name][$skus_value->name]>0) {
		        		$sku_number=($skusales[$shop_result_value->name][$skus_value->name]/$skutargets[$shop_result_value->name][$skus_value->name])*100;
		        		$sku_achieve[$shop_result_value->name][$skus_value->name]=number_format((float)$sku_number, 2, '.', '');
		        	}
		        	else
		        	{
		        		$sku_achieve[$shop_result_value->name][$skus_value->name]=0;
		        	}
		        	if($grand_skutargets[$skus_value->name]>0) {
		        		$sku_number=($grand_skusales[$skus_value->name]/$grand_skutargets[$skus_value->name])*100;
		        		$grand_sku_achieve[$skus_value->name]=number_format((float)$sku_number, 2, '.', '');
		        	}
		        	else { $grand_sku_achieve[$skus_value->name]=0; }
		        	if(isset($overall_skusales[$city_result->name][$skus_value->name]) && ($overall_skusales[$city_result->name][$skus_value->name]!=0) && isset($overall_skutargets[$city_result->name][$skus_value->name]) && ($overall_skutargets[$city_result->name][$skus_value->name]!=0)) {
		        		$sku_number=($overall_skusales[$city_result->name][$skus_value->name]/$overall_skutargets[$city_result->name][$skus_value->name])*100;
		        		$overall_sku_achieve[$city_result->name][$skus_value->name]=number_format((float)$sku_number, 2, '.', '');
		        	}
		        	else
		        	{
		        		$overall_sku_achieve[$city_result->name][$skus_value->name]=0;
		        	}
		        // }
		        }
		        }
		        	}
		        }

				
				if(isset($BA[$shop_result_value->name])) {
					$overall_total_targets[$city_result->name]+=$total_targets[$shop_result_value->name];
					$grand_total_targets+=$total_targets[$shop_result_value->name];
				}
				
				if(!empty($coversion[$shop_result_value->name]) && !empty($interception[$shop_result_value->name]) && $interception[$shop_result_value->name]!=0) {
				$number=($coversion[$shop_result_value->name]/$interception[$shop_result_value->name])*100;
				$ic_achieve[$shop_result_value->name]=number_format((float)$number, 2, '.', '');
			 }
			 else {
				$ic_achieve[$shop_result_value->name]=0;
			 }
			 if($total_targets[$shop_result_value->name]!=0) { 
			 $number=($total_sales[$shop_result_value->name]/$total_targets[$shop_result_value->name])*100;
			 $ts_achieve[$shop_result_value->name]=number_format((float)$number, 2, '.', '');
			}
			else
			{
				$ts_achieve[$shop_result_value->name]=0;
			}
        	}
	        	if($overall_total_targets[$city_result->name]!=0) { 
				 $number=($overall_total_sales[$city_result->name]/$overall_total_targets[$city_result->name])*100;
				 $overall_ts_achieve[$city_result->name]=number_format((float)$number, 2, '.', '');
				}
				else
				{
					$overall_ts_achieve[$city_result->name]=0;
				}
				if(!empty($total_coversion[$city_result->name]) && ($total_coversion[$city_result->name]!=0) && !empty($total_interception[$city_result->name]) && ($total_interception[$city_result->name]!=0)) {
				$number=($total_coversion[$city_result->name]/$total_interception[$city_result->name])*100;
				$total_ic_achieve[$city_result->name]=number_format((float)$number, 2, '.', '');
				 }
				 else {
				 	$total_ic_achieve[$city_result->name]=0;
				 	if(!empty($shop_result_value->name)) {
					$ic_achieve[$shop_result_value->name]=0;
				}
				 }
				if(count($shops)>0) {
        		$data['city']=$city_result->name;
				$data['shops']=$shops;
				$data['BA']=$BA;
				$data['interception']=$interception;
				$data['coversion']=$coversion;
				$data['ic_achieve']=$ic_achieve;
				$data['skus']=$skus;
				$data['skutargets']=$skutargets;
				$data['skusales']=$skusales;
				$data['total_targets']=$total_targets;
				$data['total_sales']=$total_sales;
				$data['ts_achieve']=$ts_achieve;
				$data['sku_achieve']=$sku_achieve;
				$data['overall_total_targets']=$overall_total_targets;
				$data['overall_total_sales']=$overall_total_sales;
				$data['overall_ts_achieve']=$overall_ts_achieve;
				$data['total_interception']=$total_interception;
				$data['total_coversion']=$total_coversion;
				$data['total_ic_achieve']=$total_ic_achieve;
				$data['overall_skutargets']=$overall_skutargets;
				$data['overall_skusales']=$overall_skusales;
				$data['overall_sku_achieve']=$overall_sku_achieve;
			    $results[$key] = $data;
				}
			    $shops=[];
        	
        }
        if($grand_total_interception>0) {
        $number=($grand_total_coversion/$grand_total_interception)*100;
		$grand_ic_achieve=number_format((float)$number, 2, '.', '');
		}
		else
		{
			$grand_ic_achieve=0;
		}
		if($grand_total_targets>0) {
        $number=($grand_total_sales/$grand_total_targets)*100;
		$grand_ts_achieve=number_format((float)$number, 2, '.', '');
		}
		else
		{
			$grand_ts_achieve=0;
		}
        return view('admin.reports.daily_sale_report')->with('brands', $brands)->with('selected_brand',$request->brands)->with('selected_from',$request->from)->with('selected_to',$request->to)->with('results',$results)->with('grand_interception',$grand_total_interception)->with('grand_conversion',$grand_total_coversion)->with('grand_ic_achieve',$grand_ic_achieve)->with('grand_total_targets',$grand_total_targets)->with('grand_total_sales',$grand_total_sales)->with('grand_ts_achieve',$grand_ts_achieve)->with('grand_skutargets',$grand_skutargets)->with('grand_skusales',$grand_skusales)->with('grand_sku_achieve',$grand_sku_achieve);
    }

    public function attendance_report()
    {
    	$brands_result = Brand::where('deleted_at',NULL)->get();
        $brands=array();
        $brands[null]='--All Brands--';
        foreach ($brands_result as $key => $value) {
        	$brands[$value->id]=$value->BrandName;
        }
        $cities_result=country::find(1)->cities;
        $cities[null]='--Select City--';
        foreach ($cities_result as $key => $value) {
            $cities[$value->id]=$value->name;
        }
        $roles_result=role::where('slug','!=','admin')->get();
        $roles=array();
        $roles[null]='--User Role--';
        foreach ($roles_result as $key => $value) {
        	$roles[$value->id]=$value->name;
        }
        $departments[null]='--Select Department--';
        $departments['Corporate']='Corporate';
        $departments['Field']='Field';
        function distance($lat1, $lon1, $lat2, $lon2, $unit) {

				  $theta = $lon1 - $lon2;
				  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
				  $dist = acos($dist);
				  $dist = rad2deg($dist);
				  $miles = $dist * 60 * 1.1515;
				  $unit = strtoupper($unit);

				  if ($unit == "K") {
				    return ($miles * 1.609344);
				  } else if ($unit == "N") {
				      return ($miles * 0.8684);
				    } else {
				        return $miles;
				      }
				}
        $date=date("Y-m-d");
        $marked=0;
        $notmarked=0;
        $attendance_report_result=array();
		$all_employees=Employee::where('EmployeeStatus','Active')->get();
		foreach ($all_employees as $all_employees_key => $all_employees_value) {
			$attendane_temp=array();
			$attendane_temp['name']=$all_employees_value->EmployeeName;
			$attendane_temp['employee_name']=$all_employees_value->EmployeeName;
			$city=city::find($all_employees_value->ShopCity);
			$attendane_temp['city']=$city->name;
			$role=role::find($all_employees_value->Designation);
			$attendane_temp['role']=$role->name;
			$shop=Shop::find($all_employees_value->Shop);
			if(!empty($shop)) {
			$attendane_temp['location']=$shop->name;
			}
			else
			{
			$attendane_temp['location']='N/A';
			}
			$brand=Brand::find($all_employees_value->SelectBrand);
			$attendane_temp['brand']=$brand->BrandName;
			$attendane_temp['DateOfDeployment']=$all_employees_value->DateOfDeployment;
			$attendane_temp['UserAvatar']=URL::to('/storage/UserAvatar/').'/'.$all_employees_value->UserAvatar;
			$attendane_temp['attendance']='Absent';					
			$attendane_temp['marker']=URL::to('/images/').'/'."red.png";					
			$attendane_temp['startTime']='N/A';
			$attendane_temp['endTime']='N/A';
			$attendane_temp['StartImage']='N/A';
			$attendane_temp['EndImage']='N/A';
			$attendane_temp['attendance_color']='red';
			$attendane_temp['distance']=0;
			if(isset($shop->latitude) && isset($shop->longitude)) {
			$attendane_temp['latitude']=$shop->latitude;
			$attendane_temp['longitude']=$shop->longitude;
			}
        $attendance_result=DB::table('attendance')->whereBetween('created_at', [$date.' 00:00:00', $date.' 23:59:59'])->where('empid',$all_employees_value->id)->get();
         $emp_entry_count=count($attendance_result);
        if($emp_entry_count>1) { $marked++; }
		foreach ($attendance_result as $key => $value) {
			$employee=Employee::where([['id',$value->empid],['EmployeeStatus','Active']])->get();
			foreach ($employee as $employee_key => $employee_value) {				
				$attendane_temp['attendance']='Present';
				$attendane_temp['marker']=URL::to('/images/').'/'."green.png";
				if($emp_entry_count>1)
		        {
		        	$attendane_temp['startTime']=trim($attendance_result[0]->startTime,'"');
					$attendane_temp['endTime']=trim($attendance_result[$emp_entry_count-1]->endTime,'"');
		        } else if($emp_entry_count==1) {
		        	$marked++;
		        	$attendane_temp['startTime']=trim($value->startTime,'"');
					$attendane_temp['endTime']=trim($value->endTime,'"');
		        }
		        $attendane_temp['UserAvatar']=URL::to('/uploadimages/').'/'.$value->StartImage;
		        $attendane_temp['attendance_color']='green';
				$attendane_temp['StartImage']=$value->StartImage;
				$attendane_temp['EndImage']=$value->EndImage;
				if(isset($value->latitude) && isset($value->longitude)) {
				$attendane_temp['latitude']=$value->latitude;
				$attendane_temp['longitude']=$value->longitude;
				}
								
				if(isset($shop->latitude) && isset($shop->longitude) && !empty($value->latitude) && !empty($value->longitude)) {
				$attendane_temp['distance']=distance($value->latitude,$value->longitude,$shop->latitude,$shop->longitude, "K")*1000;
				}
				else
				{
					$attendane_temp['marker']=URL::to('/images/').'/'."green.png";
				}
				if(isset($attendane_temp['distance'])) {
				if($attendane_temp['distance']>50)
				{
					$attendane_temp['marker']=URL::to('/images/').'/'."redgreen.png";
				}
				}
				$emp_start_time=$employee_value->AttenStartTime;
				$emp_time=date('h:i:s a', strtotime($emp_start_time));
				$atten_start_time=trim($value->startTime,'"');
				$time1 = new DateTime($emp_time);
				$time2 = new DateTime($atten_start_time);
				$interval = $time1->diff($time2);
				if($interval->format('%h')==0 && $interval->format('%i')<=15)
				{
					$attendane_temp['time_color']='black';
				}
				else {
					$attendane_temp['time_color']='red';
				}
			}
		}
		$attendance_report_result[$all_employees_key]=$attendane_temp;
		}
			$total_employees=count(Employee::where('EmployeeStatus','Active')->get());
			$notmarked=$total_employees-$marked;
			if($marked>0) {
			$attendance_percentage=number_format((float)($marked/$total_employees)*100, 2, '.', '');
			}
			else {
			$attendance_percentage=0;				
			}
			$jsondata=array();
			$map_attendance_data=array();
			foreach ($attendance_report_result as $key => $value) {
				if(!empty($value['latitude']) && !empty($value['longitude']))
				{
					$map_attendance_data[]=$attendance_report_result[$key];
				}
			}
			$jsondata['count']=count($map_attendance_data);
			$jsondata['photos']=$map_attendance_data;
			$current_date=date('Y-m-d');
			$previous_date=date('Y-m-d',strtotime('-1 month -1 day'));
			$date1 = new DateTime($current_date);
			$date2 = new DateTime($previous_date);
			$diff = $date1->diff($date2);
			$xaxis_labels=array();
			$yaxis_labels=array();
			$avg=0;
			for ($i=0; $i < $diff->days; $i++) { 
				$previous_date=date('Y-m-d',strtotime('+1 day',strtotime($previous_date)));
				$xaxis_labels[]=$previous_date;
			$attendance_result=DB::table('attendance')->whereBetween('created_at', [$previous_date.' 00:00:00', $previous_date.' 23:59:59'])->distinct()->get(['empid']);
			$yaxis_labels[]=count($attendance_result);
			$avg+=count($attendance_result);
			}
			$avg=$avg/$diff->days;
			$average=array_fill(0,count($xaxis_labels),$avg);
			$average=json_encode($average);
			$yaxis=json_encode($yaxis_labels);
			$xaxis=json_encode($xaxis_labels);
        return view('admin.reports.attendance')->with('brands', $brands)->with('cities', $cities)->with('roles', $roles)->with('departments', $departments)->with('attendance_report_result', $attendance_report_result)->with('total_employees', $total_employees)->with('marked', $marked)->with('notmarked', $notmarked)->with('attendance_percentage', $attendance_percentage)->with('map_data',$jsondata)->with('to',date('d/m/Y'))->with('from',date('d/m/Y',strtotime('-1 month')))->with('xaxis',$xaxis)->with('yaxis',$yaxis)->with('average',$average);
    }

    public function get_attendance_report(Request $request)
    {
    	$brands_result = Brand::where('deleted_at',NULL)->get();
        $brands=array();
        $brands[null]='--All Brands--';
        foreach ($brands_result as $key => $value) {
        	$brands[$value->id]=$value->BrandName;
        }
        
        if(!empty($request->brands)) {
        $brand_cities_result=Brand::find($request->brands)->cities;
        foreach ($brand_cities_result as $key => $value) {
        $city_ids[]=$value->city_id;
        }
        $cities_result=city::find($city_ids);
	        $cities[null]='--Select City--';
        foreach ($cities_result as $key => $value) {
	            $cities[$value->id]=$value->name;        	
        }
    	}
    	else
    	{
    		$cities_result=country::find(1)->cities;
	        $cities[null]='--Select City--';
	        foreach ($cities_result as $key => $value) {
	            $cities[$value->id]=$value->name;
	        }
    	}
        $roles_result=role::where('slug','!=','admin')->get();
        $roles=array();
        $roles[null]='--User Role--';
        foreach ($roles_result as $key => $value) {
        	$roles[$value->id]=$value->name;
        }
        $departments[null]='--Select Department--';
        $departments['Corporate']='Corporate';
        $departments['Field']='Field';
        function distance($lat1, $lon1, $lat2, $lon2, $unit) {

				  $theta = $lon1 - $lon2;
				  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
				  $dist = acos($dist);
				  $dist = rad2deg($dist);
				  $miles = $dist * 60 * 1.1515;
				  $unit = strtoupper($unit);

				  if ($unit == "K") {
				    return ($miles * 1.609344);
				  } else if ($unit == "N") {
				      return ($miles * 0.8684);
				    } else {
				        return $miles;
				      }
				}
		if(!empty($request->date)) {
        $date=$request->date;
    	} else {$date=date("Y-m-d"); }
        $marked=0;
        $notmarked=0;
        $attendance_report_result=array();
        $all_employees=Employee::where('EmployeeStatus','Active')->when(!empty($request->brands) , function ($query) use($request){
				return $query->where('SelectBrand',$request->brands);
			})->when(!empty($request->city) , function ($query) use($request){
				return $query->where('District',$request->city);
			})->when(!empty($request->role) , function ($query) use($request){
				return $query->where('Designation',$request->role);
			})->when(!empty($request->department) , function ($query) use($request){
				return $query->where('Department',$request->department);
			})->get();
		foreach ($all_employees as $all_employees_key => $all_employees_value) {
			$attendane_temp=array();
			$attendane_temp['name']=$all_employees_value->EmployeeName;
			$attendane_temp['employee_name']=$all_employees_value->EmployeeName;
			$city=city::find($all_employees_value->ShopCity);
			$attendane_temp['city']=$city->name;
			$role=role::find($all_employees_value->Designation);
			$attendane_temp['role']=$role->name;
			$shop=Shop::find($all_employees_value->Shop);
			if(!empty($shop)) {
			$attendane_temp['location']=$shop->name;
			}
			else
			{
			$attendane_temp['location']='N/A';
			}
			$brand=Brand::find($all_employees_value->SelectBrand);
			$attendane_temp['brand']=$brand->BrandName;
			$attendane_temp['DateOfDeployment']=$all_employees_value->DateOfDeployment;
			$attendane_temp['UserAvatar']=URL::to('/storage/UserAvatar/').'/'.$all_employees_value->UserAvatar;
			$attendane_temp['attendance']='Absent';	
			$attendane_temp['marker']=URL::to('/images/').'/'."red.png";				
			$attendane_temp['startTime']='N/A';
			$attendane_temp['endTime']='N/A';
			$attendane_temp['StartImage']='N/A';
			$attendane_temp['EndImage']='N/A';
			$attendane_temp['attendance_color']='red';
			$attendane_temp['distance']=0;
			if(isset($shop->latitude) && isset($shop->longitude)) {
			$attendane_temp['latitude']=$shop->latitude;
			$attendane_temp['longitude']=$shop->longitude;
		 }
        $attendance_result=DB::table('attendance')->whereBetween('created_at', [$date.' 00:00:00', $date.' 23:59:59'])->where('empid',$all_employees_value->id)->get();
        $emp_entry_count=count($attendance_result);
        if($emp_entry_count>1) { $marked++; }
		foreach ($attendance_result as $key => $value) {
			$employee=Employee::where([['id',$value->empid],['EmployeeStatus','Active']])->get();
			foreach ($employee as $employee_key => $employee_value) {							
				$attendane_temp['attendance']='Present';
				$attendane_temp['UserAvatar']=URL::to('/uploadimages/').'/'.$value->StartImage;
				$attendane_temp['marker']=URL::to('/images/').'/'."green.png";
				if($emp_entry_count>1)
		        {
		        	$attendane_temp['startTime']=trim($attendance_result[0]->startTime,'"');
					$attendane_temp['endTime']=trim($attendance_result[$emp_entry_count-1]->endTime,'"');
		        } else if($emp_entry_count==1) {
		        	$marked++;
		        	$attendane_temp['startTime']=trim($value->startTime,'"');
					$attendane_temp['endTime']=trim($value->endTime,'"');
		        }
		        $attendane_temp['UserAvatar']=URL::to('/uploadimages/').'/'.$value->StartImage;
		        $attendane_temp['attendance_color']='green';
				$attendane_temp['StartImage']=$value->StartImage;
				$attendane_temp['EndImage']=$value->EndImage;
				if(isset($value->latitude) && isset($value->longitude)) {
				$attendane_temp['latitude']=$value->latitude;
				$attendane_temp['longitude']=$value->longitude;
				}
				if(isset($shop->latitude) && isset($shop->longitude) && !empty($value->latitude) && !empty($value->longitude)) {
				$attendane_temp['distance']=distance($value->latitude,$value->longitude,$shop->latitude,$shop->longitude, "K")*1000;
				}
				else
				{
					$attendane_temp['marker']=URL::to('/images/').'/'."green.png";
				}
				if(isset($attendane_temp['distance'])) {
				if($attendane_temp['distance']>50)
				{
					$attendane_temp['marker']=URL::to('/images/').'/'."redgreen.png";
				}
				}
				$emp_start_time=$employee_value->AttenStartTime;
				$emp_time=date('h:i:s a', strtotime($emp_start_time));
				$atten_start_time=trim($value->startTime,'"');
				$time1 = new DateTime($emp_time);
				$time2 = new DateTime($atten_start_time);
				$interval = $time1->diff($time2);
				if($interval->format('%h')==0 && $interval->format('%i')<=15)
				{
					$attendane_temp['time_color']='black';
				}
				else {
					$attendane_temp['time_color']='red';
				}
			}
		}
		$attendance_report_result[$all_employees_key]=$attendane_temp;
		}
			$total_employees=count($all_employees);
			$notmarked=$total_employees-$marked;
			if($marked>0) {
			$attendance_percentage=number_format((float)($marked/$total_employees)*100, 2, '.', '');
			}
			else {
			$attendance_percentage=0;				
			}
			$jsondata=array();
			$map_attendance_data=array();
			foreach ($attendance_report_result as $key => $value) {
				if(!empty($value['latitude']) && !empty($value['longitude']))
				{
					$map_attendance_data[]=$attendance_report_result[$key];
				}
			}
			$jsondata['count']=count($map_attendance_data);
			$jsondata['photos']=$map_attendance_data;
			if(!empty($request->attendance_from_date) && !empty($request->attendance_to_date)) {
			$current_date=$request->attendance_to_date;
			$previous_date=date('Y-m-d',strtotime($request->attendance_from_date));
			$pdate=date('Y-m-d',strtotime($request->attendance_from_date));
			}
			else
			{
			$current_date=date('Y-m-d');
			$previous_date=date('Y-m-d',strtotime('-1 month -1 day'));
			$pdate=date('Y-m-d',strtotime('-1 month -1 day'));
			}
			$date1 = new DateTime($current_date);
			$date2 = new DateTime($previous_date);
			$diff = $date1->diff($date2);
			$xaxis_labels=array();
			$yaxis_labels=array();
			$avg=0;
			for ($i=0; $i < $diff->days; $i++) { 
				$previous_date=date('Y-m-d',strtotime('+1 day',strtotime($previous_date)));
				$xaxis_labels[]=$previous_date;
			$attendance_result=DB::table('attendance')->whereBetween('created_at', [$previous_date.' 00:00:00', $previous_date.' 23:59:59'])->distinct()->get(['empid']);
			$yaxis_labels[]=count($attendance_result);
			$avg+=count($attendance_result);
			}
			$avg=$avg/$diff->days;
			$average=array_fill(0,count($xaxis_labels),$avg);
			$average=json_encode($average);
			$yaxis=json_encode($yaxis_labels);
			$xaxis=json_encode($xaxis_labels);
        return view('admin.reports.attendance')->with('brands', $brands)->with('cities', $cities)->with('roles', $roles)->with('departments', $departments)->with('attendance_report_result', $attendance_report_result)->with('total_employees', $total_employees)->with('marked', $marked)->with('notmarked', $notmarked)->with('attendance_percentage', $attendance_percentage)->with('selected_date',$date)->with('selected_city',$request->city)->with('map_data',$jsondata)->with('to',date('d/m/Y',strtotime($current_date)))->with('from',date('d/m/Y',strtotime($pdate)))->with('xaxis',$xaxis)->with('yaxis',$yaxis)->with('average',$average)->with('selected_attendance_from_date',$pdate)->with('selected_attendance_to_date',$current_date);
    }

}
