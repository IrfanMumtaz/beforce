<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Brands;
use App\Models\Categories;
use App\Models\Stores;
use App\sales;
use DB;

class ReportController extends Controller
{
    //

    public function brandShare(){
    	$data['brands'] = Brands::all();
    	$categories = new Categories();
    	$data['saleType'] = $categories->categoryStatus();
    	return view('admin.brand-share.index')->with($data);
    }

    public function brandShareAjax(Request $request){
    	switch ($request->request_to) {
    		case 'brand_share':
    				$this->brandShareFilter($request->only('sale_type', 'brand', 'report_from', 'report_to'));
    			break;

    		case 'interception_brand':
    				$this->inteceptionBrandShare($request->only('brand', 'report_from', 'report_to'));
    			break;

    		case 'total_interception':
    				$this->totalInterception($request->only('brand', 'report_from', 'report_to'));
    			break;

            case 'target_sale':
                    $this->totalVsSale($request->only('brand', 'report_from', 'report_to'));
                break;
    		
    		default:
    			# code...
    			break;
    	}
    }

    private function brandShareFilter($req){
    	$date[0] = $req['report_from'] ? $req['report_from']. " 00:00:00" : date('Y-m-d H:i:s');
    	$date[1] = $req['report_to'] ? $req['report_to']. " 23:59:59" : date('Y-m-d H:i:s');

    	$brand = Brands::query();
    	$brand->select('c.Category as name', DB::raw('count(o.id) as y'));
    	$brand->join('categories as c', 'c.brand_id', '=', 'brands.id', 'inner');
    	$brand->join('Orders as o', 'o.skuCategory', '=', 'c.id', 'inner');
    	$brand->where('brands.id', $req['brand']);
    	$brand->where('c.Competition', $req['sale_type']);
    	$brand->whereBetween('o.created_at', $date);
    	$brand->groupBy('o.skuCategory');
    	$result = $brand->get();

    	echo json_encode($result);
    }

    private function inteceptionBrandShare($req){
    	$date[0] = $req['report_from'] ? $req['report_from']. " 00:00:00" : date('Y-m-d H:i:s');
    	$date[1] = $req['report_to'] ? $req['report_to']. " 23:59:59" : date('Y-m-d H:i:s');

    	$brand = Brands::query();
    	$brand->select('s.name as name', DB::raw('count(o.id) as y'));
    	$brand->join('categories as c', 'c.brand_id', '=', 'brands.id', 'inner');
    	$brand->join('skus as s', 's.category_id', '=', 'c.id', 'inner');
    	$brand->join('Orders as o', 'o.SKU', '=', 's.id', 'inner');
    	$brand->where('brands.id', $req['brand']);
    	$brand->whereBetween('o.created_at', $date);
    	$brand->groupBy('o.SKU');
    	$result = $brand->get();

    	echo json_encode($result);
    }

    private function totalInterception($req){
        $date[0] = $req['report_from'] ? $req['report_from']. " 00:00:00" : date('Y-m-d H:i:s');
        $date[1] = $req['report_to'] ? $req['report_to']. " 23:59:59" : date('Y-m-d H:i:s');

        $brand = Brands::query();
        $brand->select('s.name as name', DB::raw('count(o.id) as y'));
        $brand->join('categories as c', 'c.brand_id', '=', 'brands.id', 'inner');
        $brand->join('Orders as o', 'o.skuCategory', '=', 'c.id', 'inner');
        $brand->join('shops as s', 'o.storeId', '=', 's.id', 'inner');
        $brand->where('brands.id', $req['brand']);
        $brand->whereBetween('o.created_at', $date);
        $brand->groupBy('o.storeId');
        $result = $brand->get();

        echo json_encode($result);
    }

    private function totalVsSale($req){
    	$date[0] = $req['report_from'] ? $req['report_from']. " 00:00:00" : date('Y-m-d H:i:s');
    	$date[1] = $req['report_to'] ? $req['report_to']. " 23:59:59" : date('Y-m-d H:i:s');

    	$brand = Brands::query();
    	$brand->select('t.created_at as categories', DB::raw('sum(s.Price * t.skutargets) as sale_target'), DB::raw('sum(s.Price * o.noItem) as sale'));
    	$brand->join('categories as c', 'c.brand_id', '=', 'brands.id', 'inner');
    	$brand->join('skus as s', 's.category_id', '=', 'c.id', 'inner');
        $brand->join('skutargets as t', 's.id', '=', 't.sku_id', 'inner');
    	$brand->join('Orders as o', 'o.SKU', '=', 's.id', 'left');
    	$brand->where('brands.id', $req['brand']);
    	$brand->whereBetween('o.created_at', $date);
    	$brand->groupBy('t.created_at');
    	$result = $brand->get();

    	echo json_encode($result);
    }

    public function genderWise(){
        $data['brands'] = Brands::all();
        $data['shops'] = Stores::all();
        return view('admin.brand-share.gender-report')->with($data);
    }
    
    public function genderWiseAjax(Request $request){
        switch ($request->request_to) {
            case 'gender_wise':
                    $this->genderWiseReport($request->only('shops', 'brand', 'report_from', 'report_to'));
                break;
            
            case 'gender_wise_ratio':
                    $this->genderWiseReportRatio($request->only('shops', 'brand', 'report_from', 'report_to'));
                break;
            
            default:
                # code...
                break;
        }
    }

    private function genderWiseReport($request){
        $date[0] = $request['report_from'] ? $request['report_from']. " 00:00:00" : date('Y-m-d H:i:s');
        $date[1] = $request['report_to'] ? $request['report_to']. " 23:59:59" : date('Y-m-d H:i:s');
        $categories = Brands::select('categories.id', 'categories.Category')->join('categories', 'categories.brand_id', '=', 'brands.id', 'inner')->where('brands.id', $request['brand'])->get();
        $sales = Sales::Query();
        $sales->whereBetween('created_at', $date);  
        if($request['shops'] != -1){
            $sales->where("location", $request['shops']);
        }
        $sales = $sales->get();

        $intercept = array('male' => 0, 'female' => 0);
        $sale = array('male' => 0, 'female' => 0);
        $productivity = array('male' => 0, 'female' => 0);

        foreach($categories as $c){
            $cat[] = $c->id;
        }

        foreach($sales as $s){
            $s->gender = str_replace('"', '', $s->gender);
            if($s->gender == "Male"){
                $s->pBrand = str_replace('"', '', $s->pBrand);
                if(in_array((int)$s->pBrand, $cat)){
                    $intercept['male'] += 1;
                    if($s->saleStatus == 1){
                        $sale['male'] += 1;
                        $s->cBrand = str_replace('"', '', $s->cBrand);
                        if($s->pBrand !== $s->cBrand){
                            $productivity['male'] += 1;
                        }
                    }
                }
            }
            else{
                $s->pBrand = str_replace('"', '', $s->pBrand);
                if(in_array((int)$s->pBrand, $cat)){
                    $intercept['female'] += 1;
                    if($s->saleStatus == 1){
                        $sale['female'] += 1;
                        $s->cBrand = str_replace('"', '', $s->cBrand);
                        if($s->pBrand !== $s->cBrand){
                            $productivity['female'] += 1;
                        }
                    }
                }
            }
        }
        echo json_encode(array($intercept, $sale, $productivity));
    }

    private function genderWiseReportRatio($request){
        $date[0] = $request['report_from'] ? $request['report_from']. " 00:00:00" : date('Y-m-d H:i:s');
        $date[1] = $request['report_to'] ? $request['report_to']. " 23:59:59" : date('Y-m-d H:i:s');
        $categories = Brands::select('categories.id', 'categories.Category')->join('categories', 'categories.brand_id', '=', 'brands.id', 'inner')->where('brands.id', $request['brand'])->get();
        $sales = Sales::Query();
        $sales->whereBetween('created_at', $date);  
        if($request['shops'] != -1){
            $sales->where("location", $request['shops']);
        }
        $sales = $sales->get();

        $intercept = array('male' => 0, 'female' => 0);
        $sale = array('male' => 0, 'female' => 0);
        $productivity = array('male' => 0, 'female' => 0);

        foreach($categories as $c){
            $cat[] = $c->id;
        }

        foreach($sales as $s){
            $s->gender = str_replace('"', '', $s->gender);
            if($s->gender == "Male"){
                $s->pBrand = str_replace('"', '', $s->pBrand);
                if(in_array((int)$s->pBrand, $cat)){
                    $intercept['male'] += 1;
                    if($s->saleStatus == 1){
                        $sale['male'] += 1;
                        $s->cBrand = str_replace('"', '', $s->cBrand);
                        if($s->pBrand !== $s->cBrand){
                            $productivity['male'] += 1;
                        }
                    }
                }
            }
            else{
                $s->pBrand = str_replace('"', '', $s->pBrand);
                if(in_array((int)$s->pBrand, $cat)){
                    $intercept['female'] += 1;
                    if($s->saleStatus == 1){
                        $sale['female'] += 1;
                        $s->cBrand = str_replace('"', '', $s->cBrand);
                        if($s->pBrand !== $s->cBrand){
                            $productivity['female'] += 1;
                        }
                    }
                }
            }
        }
        $_intercept['male'] = round(($intercept['male']/($intercept['male'] + $intercept['female']))*100);
        $_intercept['female'] = round(($intercept['female']/($intercept['male'] + $intercept['female']))*100);
        $_sale['male'] = round(($sale['male']/($sale['male'] + $sale['female']))*100);
        $_sale['female'] = round(($sale['female']/($sale['male'] + $sale['female']))*100);
        $_prod['male'] = round(($productivity['male']/($productivity['male'] + $productivity['female']))*100);
        $_prod['female'] = round(($productivity['female']/($productivity['male'] + $productivity['female']))*100);
        echo json_encode(array($_intercept, $_sale, $_prod));
    }


}
