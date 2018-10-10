<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
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
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
class UsersExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
    	$results = (object) array();
		$results = new stdClass();
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

    	$brands_result = Brand::where('deleted_at',NULL)->get();
        $brands[]='--All Brands--';
        foreach ($brands_result as $key => $value) {
        	$brands[$value->id]=$value->BrandName;
        }
        $category_res=Category::where([['brand_id',1],['Competition',0]])->get();
        foreach ($category_res as $key => $value) {
        	$skus_resuls=Sku::where('category_id',$value->id)->get();
        	foreach ($skus_resuls as $skus_key => $skus_value) {
        		$skus[]=$skus_value->name;

        	}
        }

    	$brand_cities_result=Brand::find(1)->cities;
        foreach ($brand_cities_result as $key => $value) {
        	$city_result=city::find($value->city_id);
            $cities[]=$city_result->name;
            $overall_total_targets[$city_result->name]=0;
            $overall_total_sales[$city_result->name]=0;
        	$shop_result=Shop::where([['city_id',$value->city_id],['brand_id',1]])->get();
        	foreach ($shop_result as $shop_result_key => $shop_result_value) {
        		$total_targets[$shop_result_value->name]=0;
        		$total_sales[$shop_result_value->name]=0;
        		$total_interception[$city_result->name]=0;
        		$total_coversion[$city_result->name]=0;

        		$category_res=Category::where([['brand_id',1],['Competition',0]])->get();
		        foreach ($category_res as $cat_key => $cat_value) {
		        	$skus_resuls=Sku::where('category_id',$cat_value->id)->get();
		        	foreach ($skus_resuls as $skus_key => $skus_value) {
		        		$skutargets_res=Skutarget::where([['sku_id',$skus_value->id],['shop_id',$shop_result_value->id]])->get();
		        		if(count($skutargets_res)>0) {
		        		foreach ($skutargets_res as $skutargets_res_key => $skutargets_res_value) {
		        			$skutargets[$shop_result_value->name][$skus_value->name]=$skutargets_res_value->skutargets;
		        			if(isset($overall_skutargets[$city_result->name][$skus_value->name])){
		        			$overall_skutargets[$city_result->name][$skus_value->name]+=$skutargets_res_value->skutargets;
		        		}
		        		else
		        		{
		        			$overall_skutargets[$city_result->name][$skus_value->name]=$skutargets_res_value->skutargets;

		        		}
		        			$total_targets[$shop_result_value->name]+=$skutargets_res_value->skutargets*$skus_value->Price;
		        			$overall_total_targets[$city_result->name]+=$skutargets_res_value->skutargets*$skus_value->Price;
		        		}
		        		}

		        		$skusales_res=DB::table('orders')
		        		->where('storeId','=',$shop_result_value->id)
		        		->where('SKU','=',$skus_value->id)
		        		->get();
		        		$noitem=0;
		        		foreach ($skusales_res as $skusales_res_key => $skusales_res_value) {
		        			$noitem=$skusales_res_value->noItem;
		        		}
		        		$skusales[$shop_result_value->name][$skus_value->name]=$noitem;
		        		$total_sales[$shop_result_value->name]+=$noitem*$skus_value->Price;
		        		$overall_total_sales[$city_result->name]+=$noitem*$skus_value->Price;
		        		if(isset($overall_skusales[$city_result->name][$skus_value->name])){
		        		$overall_skusales[$city_result->name][$skus_value->name]+=$noitem;
		        		}
		        		else {
		        		$overall_skusales[$city_result->name][$skus_value->name]=$noitem;	
		        		}
		        		if(isset($skusales[$shop_result_value->name][$skus_value->name]) && isset($skutargets[$shop_result_value->name][$skus_value->name])) {
		        		$sku_number=($skusales[$shop_result_value->name][$skus_value->name]/$skutargets[$shop_result_value->name][$skus_value->name])*100;
		        		$sku_achieve[$shop_result_value->name][$skus_value->name]=number_format((float)$sku_number, 2, '.', '');
		        	}
		        	else
		        	{
		        		$sku_achieve[$shop_result_value->name][$skus_value->name]=0;
		        	}
		        	if(($overall_skusales[$city_result->name][$skus_value->name]!=0) && ($overall_skutargets[$city_result->name][$skus_value->name]!=0)) {
		        		$sku_number=($overall_skusales[$city_result->name][$skus_value->name]/$overall_skutargets[$city_result->name][$skus_value->name])*100;
		        		$overall_sku_achieve[$city_result->name][$skus_value->name]=number_format((float)$sku_number, 2, '.', '');
		        	}
		        	else
		        	{
		        		$overall_sku_achieve[$city_result->name][$skus_value->name]=0;
		        	}
		        	}
		        }

				$shops[]=$shop_result_value->name;
				$brand_emp=Employee::where([['Shop',$shop_result_value->id],['Designation',7]])->get();
				foreach ($brand_emp as $brand_emp_key => $brand_emp_value) {
					$BA[$shop_result_value->name]=$brand_emp_value->Username;
				    $sales_result= \DB::table('sales')->whereBetween('created_at', ['2018-09-19'.' 00:00:00', '2018-09-23'.' 23:59:59'])->where('empId',$brand_emp_value->id)->get();
					$interception[$shop_result_value->name]=count($sales_result);
					$total_interception[$city_result->name]+=count($sales_result);
    				$emp_brand_result=Brand::find($brand_emp_value->SelectBrand);
    				$count=0;
    				foreach ($sales_result as $sales_key => $sales_value) {
    					$brand_res=Category::find(trim($sales_value->cBrand,'"'));
    					if($brand_emp_value->SelectBrand==$brand_res->brand_id && $sales_value->saleStatus==1)
    					{

    						$coversion[$shop_result_value->name]=++$count;
    						$total_coversion[$city_result->name]+=1;
    					}
    				}

				}
				if(isset($coversion[$shop_result_value->name]) && isset($interception[$shop_result_value->name])) {
				$ic_achieve[$shop_result_value->name]=($coversion[$shop_result_value->name]/$interception[$shop_result_value->name])*100;
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
				if(isset($total_coversion[$city_result->name]) && ($total_coversion[$city_result->name]!=0) && isset($total_interception[$city_result->name]) && ($total_interception[$city_result->name]!=0)) {
				$total_ic_achieve[$city_result->name]=($total_coversion[$city_result->name]/$total_interception[$city_result->name])*100;
				 }
				 else {
				 	if(isset($shop_result_value->name)) {
					$ic_achieve[$shop_result_value->name]=0;
				}
				 }
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
			    $results->$key = $data;
			    $shops=[];
        	
        }
        
        foreach ($results as $key => $value) {
        return $value->toArray();        	
        }
        return $results[0];
    }
}
