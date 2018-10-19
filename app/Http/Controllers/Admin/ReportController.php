<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Brands;
use App\Models\Categories;
use App\Models\Stores;
use App\Employee;
use App\Order;
use App\sales;
use App\Breaks;
use App\city;
use DB;

class ReportController extends Controller
{
    //

    public function brandShare(){
        $data['brands'] = Brands::all();
    	$data['shops'] = Stores::all();
    	$categories = new Categories();
    	$data['saleType'] = $categories->categoryStatus();
    	return view('admin.brand-share.index')->with($data);
    }

    public function brandShareAjax(Request $request){
    	switch ($request->request_to) {
    		case 'brand_share':
    				$this->brandShareFilter($request->only('sale_type', 'brand', 'shops', 'report_from', 'report_to'));
    			break;

    		case 'interception_brand':
    				$this->inteceptionBrandShare($request->only('brand', 'shops', 'report_from', 'report_to'));
    			break;

    		case 'total_interception':
    				$this->totalInterception($request->only('brand', 'shops', 'report_from', 'report_to'));
    			break;

            case 'target_sale':
                    $this->totalVsSale($request->only('brand', 'shops', 'report_from', 'report_to'));
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
        if($req['brand'] != -1){
            $brand->where('brands.id', $req['brand']);
        }
        if($req['shops'] != -1){
            $brand->where('o.storeId', $req['shops']);
        }
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
        if($req['brand'] != -1){
            $brand->where('brands.id', $req['brand']);
        }
        if($req['shops'] != -1){
            $brand->where('o.storeId', $req['shops']);
        }
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
        
        if($req['brand'] != -1){
            $brand->where('brands.id', $req['brand']);
        }
        if($req['shops'] != -1){
            $brand->where('o.storeId', $req['shops']);
        }
        $brand->whereBetween('o.created_at', $date);
        $brand->groupBy('o.storeId');
        $result = $brand->get();

        echo json_encode($result);
    }

    private function totalVsSale($req){
    	$date[0] = $req['report_from'] ? $req['report_from']. " 00:00:00" : date('Y-m-d H:i:s');
    	$date[1] = $req['report_to'] ? $req['report_to']. " 23:59:59" : date('Y-m-d H:i:s');

    	$brand = Brands::query();
    	$brand->select(DB::raw('LEFT(t.created_at, 10) as categories'), DB::raw('sum(s.Price) * sum(t.skutargets) as sale_target'), DB::raw('sum(s.Price) * sum(o.noItem) as sale'));
    	$brand->join('categories as c', 'c.brand_id', '=', 'brands.id', 'inner');
    	$brand->join('skus as s', 's.category_id', '=', 'c.id', 'inner');
        $brand->join('skutargets as t', 's.id', '=', 't.sku_id', 'inner');
    	$brand->join('Orders as o', 'o.SKU', '=', 's.id', 'left');
    	
        if($req['brand'] != -1){
            $brand->where('brands.id', $req['brand']);
        }
        if($req['shops'] != -1){
            $brand->where('o.storeId', $req['shops']);
        }
    	$brand->whereBetween('o.created_at', $date);
    	$brand->groupBy('categories');
    	$result = $brand->get();

    	echo json_encode($result);
    }

    public function genderWise(){
        $data['brands'] = Brands::all();
        $data['shops'] = Stores::all();
        $data['cities'] = City::all();
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
            
            case 'age_wise':
                $this->ageWiseReport($request->only('shops', 'brand', 'report_from', 'report_to'));
                break;
            
            case 'cluster_survey':
                $this->clusterSurvey($request->only('shops', 'brand', 'report_from', 'cities'));
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

    private function ageWiseReport($request){
        
        $date[0] = $request['report_from'] ? $request['report_from']. " 00:00:00" : date('Y-m-d H:i:s');
        $date[1] = $request['report_to'] ? $request['report_to']. " 23:59:59" : date('Y-m-d H:i:s');
        $categories = Brands::select('categories.id', 'categories.Category')->join('categories', 'categories.brand_id', '=', 'brands.id', 'inner')->where('brands.id', $request['brand'])->get();
        $sales = Sales::Query();
        $sales->whereBetween('created_at', $date);  
        if($request['shops'] != -1){
            $sales->where("Location", $request['shops']);
        }
        if($request['brand'] != -1){
            $sales->where("cBrand", "LIKE",  "%".$request['brand']."%");
        }
        $sales = $sales->get();

        $ageGroup = array(
            '15-20' => 0,
            '21-25' => 0,
            '25-30' => 0,
            '30-35' => 0,
            '35-40' => 0,
            '40-45' => 0,
            '45+' => 0,
        );

        foreach($categories as $c){
            $cat[] = $c->id;
        }

        foreach($sales as $s){
            if($s->age <= 20 ){
                $ageGroup['15-20'] += 1;
            }
            elseif($s->age <= 25 ){
                $ageGroup['21-25'] += 1;
            }
            elseif($s->age <= 30 ){
                $ageGroup['25-30'] += 1;
            }
            elseif($s->age <= 35 ){
                $ageGroup['30-35'] += 1;
            }
            elseif($s->age <= 40 ){
                $ageGroup['35-40'] += 1;
            }
            elseif($s->age <= 45 ){
                $ageGroup['40-45'] += 1;
            }
            else{
                $ageGroup['45+'] += 1;
            }
            
        }
        echo json_encode($ageGroup);
    }

    private function clusterSurvey($request){
        
        $date[0] = $request['report_from'] ? $request['report_from']. " 00:00:00" : date('Y-m-d H:i:s');
        $date[1] = $request['report_from'] ? $request['report_from']. " 23:59:59" : date('Y-m-d H:i:s');
        $categories = Brands::select('categories.id', 'categories.Category')->join('categories', 'categories.brand_id', '=', 'brands.id', 'inner')->where('brands.id', $request['brand'])->get();
        $sales = Sales::Query();
        $sales->whereBetween('created_at', $date);  
        if($request['shops'] != -1){
            $sales->where("Location", $request['shops']);
        }
        if($request['brand'] != -1){
            $sales->where("cBrand", "LIKE",  "%".$request['brand']."%");
        }
        if($request['cities'] != -1){
            $sales->where("City", "LIKE",  "%".$request['cities']."%");
        }
        $sales->where("saleStatus", 1);
        $sales = $sales->get();
        $sale = array();
        $categories = array();
        $saleGroup = array(
                "00" => 0, "01" => 0, "02" => 0, "03" => 0, "04" => 0, "05" => 0, 
                "06" => 0, "07" => 0, "08" => 0, "09" => 0, "10" => 0, "11" => 0, 
                "12" => 0, "13" => 0, "14" => 0, "15" => 0, "16" => 0, "17" => 0, 
                "18" => 0, "19" => 0, "20" => 0, "21" => 0, "22" => 0, "23" => 0);

        foreach($sales as $s){
            $hour = date("H", strtotime($s->created_at));
            $saleGroup[(string)$hour] += 1;   
        }
        foreach($saleGroup as $k => $v){
            $categories[] =$k;
            $sale[] =$v;
        }
        $average = array_sum($saleGroup) / 24;
        echo json_encode([$average,$categories, $sale]);
    }

    public function interception(){
        $data['brands'] = Brands::all();
        $data['cities'] = City::all();
        $data['shops'] = Stores::all();
        $data['employees'] = Employee::where('Designation', 7)->get();
        
        return view('admin.brand-share.interception')->with($data);
    }

    public function interceptionReport(Request $request){

        if(!$request->isMethod('post')){
            return redirect()->route('admin.interception');
        }

        $data['brands'] = Brands::all();
        $data['cities'] = City::all();
        $data['shops'] = Stores::all();
        $data['employees'] = Employee::where('Designation', 7)->get();


        $data['interception'] = $this->interceptionReq($request->only($request->report_from, $request->report_to, $request->brands, $request->cities, $request->shops, $request->employees));
        $data['competitor'] = $this->competitor($request->only($request->report_from, $request->report_to, $request->brands, $request->cities, $request->shops, $request->employees));
        $data['noSale'] = $this->noSale($request->only($request->report_from, $request->report_to, $request->brands, $request->cities, $request->shops, $request->employees));

        return view('admin.brand-share.interception')->with($data);
    }

    private function interceptionReq($request){

        $date[0] = $request['report_from'] ? $request['report_from']. " 00:00:00" : date('Y-m-d H:i:s');
        $date[1] = $request['report_to'] ? $request['report_to']. " 23:59:59" : date('Y-m-d H:i:s');
        $sql = "SELECT *, sales.created_at as date , cSale.BrandName as cName, pSale.BrandName as pName, (SELECT GROUP_CONCAT(sk.name) FROM Orders AS o INNER JOIN skus AS sk ON sk.id = o.SKU WHERE o.SalesId = sales.`id` GROUP BY o.salesId) AS skuName FROM sales LEFT JOIN brands AS cSale ON sales.`cBrand` = CONCAT('\"',cSale.`id`,'\"') LEFT JOIN brands AS pSale ON sales.`pBrand` = CONCAT('\"',pSale.`id`,'\"') LEFT JOIN shops as s ON s.id = sales.Location INNER JOIN Orders AS o ON o.salesId = sales.id WHERE ";

        if($request['brands'] != -1){
            $sql .= "sales.cBrand LIKE '%".$request['brands']."%' AND ";
        }

        if($request['cities'] != -1){
            $sql .= "City LIKE '%".$request['cities']."%' AND ";
        }

        if($request['shops'] != -1){
            $sql .= "sales.Location = ".$request['shops']." AND ";
        }
        
        if($request['employees'] != -1){
            $sql .= "sales.empId = ".$request['employees']." AND ";
        }
        
        $sql .= "sales.created_at BETWEEN \"$date[0]\" AND \"$date[1]\"";
        
        $sales = DB::select(DB::raw($sql));
        return $sales;

    }

    private function competitor($request){

        $date[0] = $request['report_from'] ? $request['report_from']. " 00:00:00" : date('Y-m-d H:i:s');
        $date[1] = $request['report_to'] ? $request['report_to']. " 23:59:59" : date('Y-m-d H:i:s');
        $sql = "SELECT *, sales.created_at as date , cSale.BrandName as cName, pSale.BrandName as pName, (SELECT GROUP_CONCAT(sk.name) FROM Orders AS o INNER JOIN skus AS sk ON sk.id = o.SKU WHERE o.SalesId = sales.`id` GROUP BY o.salesId) AS skuName FROM sales LEFT JOIN brands AS cSale ON sales.`cBrand` = CONCAT('\"',cSale.`id`,'\"') LEFT JOIN brands AS pSale ON sales.`pBrand` = CONCAT('\"',pSale.`id`,'\"') LEFT JOIN shops as s ON s.id = sales.Location INNER JOIN Orders AS o ON o.salesId = sales.id INNER JOIN skus AS sk ON sk.id = o.SKU WHERE ";

        if($request['brands'] != -1){
            $sql .= "sales.pBrand LIKE '%".$request['brands']."%' AND ";
        }

        if($request['cities'] != -1){
            $sql .= "City LIKE '%".$request['cities']."%' AND ";
        }

        if($request['shops'] != -1){
            $sql .= "sales.Location = ".$request['shops']." AND ";
        }
        
        if($request['employees'] != -1){
            $sql .= "sales.empId = ".$request['employees']." AND ";
        }
        
        $sql .= "sales.created_at BETWEEN \"$date[0]\" AND \"$date[1]\" AND sales.saleStatus = 1 AND sales.cBrand NOT IN (SELECT CONCAT('\"',id,'\"') from brands)";
        
        $sales = DB::select(DB::raw($sql));

        return $sales;
 
    }

    private function noSale($request){

        $date[0] = $request['report_from'] ? $request['report_from']. " 00:00:00" : date('Y-m-d H:i:s');
        $date[1] = $request['report_to'] ? $request['report_to']. " 23:59:59" : date('Y-m-d H:i:s');
        $sql = "SELECT *, sales.created_at as date , cSale.BrandName as cName, pSale.BrandName as pName FROM sales LEFT JOIN brands AS cSale ON sales.`cBrand` = CONCAT('\"',cSale.`id`,'\"') LEFT JOIN brands AS pSale ON sales.`pBrand` = CONCAT('\"',pSale.`id`,'\"') LEFT JOIN shops as s ON s.id = sales.Location WHERE ";

        if($request['brands'] != -1){
            $sql .= "sales.cBrand LIKE '%".$request['brands']."%' AND ";
        }

        if($request['cities'] != -1){
            $sql .= "City LIKE '%".$request['cities']."%' AND ";
        }

        if($request['shops'] != -1){
            $sql .= "sales.Location = ".$request['shops']." AND ";
        }
        
        if($request['employees'] != -1){
            $sql .= "sales.empId = ".$request['employees']." AND ";
        }
        
        $sql .= "sales.created_at BETWEEN \"$date[0]\" AND \"$date[1]\" AND sales.saleStatus = 0";
        
        $sales = DB::select(DB::raw($sql));
        return $sales;
    }

    public function break(){
        $data['brands'] = Brands::all();
        $data['cities'] = City::all();
        $data['shops'] = Stores::all();
        $data['employees'] = Employee::where('Designation', 7)->get();

        $date[0] = date("Y-m-d"). " 00:00:00";
        $date[1] = date('Y-m-d H:i:s');

        $break = Breaks::query();
        $break->join("employees as emp", "emp.id", "=", 'emp_break.emp_id', 'inner');
        $break->join("break as b", "b.id", "=", 'emp_break.break_id', 'inner');
        $break->whereBetween('emp_break.created_at', $date);
        $data['breaks'] = $break->get();


        return view('admin.brand-share.break')->with($data);
    }

    public function breakReport(Request $request){
        if(!$request->isMethod('post')){
            return redirect()->route('admin.break');
        }
        $data['brands'] = Brands::all();
        $data['cities'] = City::all();
        $data['shops'] = Stores::all();
        $data['employees'] = Employee::where('Designation', 7)->get();

        $date[0] = $request->report_from ? $request->report_from. " 00:00:00" : date('Y-m-d H:i:s');
        $date[1] = $request->report_to ? $request->report_to. " 23:59:59" : date('Y-m-d H:i:s');

        $break = Breaks::query();
        $break->join("employees as emp", "emp.id", "=", 'emp_break.emp_id', 'inner');
        $break->join("break as b", "b.id", "=", 'emp_break.break_id', 'inner');
        $break->whereBetween('emp_break.created_at', $date);

        if($request->employees != -1){
            $break->where('emp_break', $request->employees);
        }

        if($request->brands != -1){
            $break->where('emp.SelectBrand', $request->brands);
        }

        if($request->cities != -1){
            $break->where('emp.ShopCity', $request->cities);
        }

        if($request->shops != -1){
            $break->where('emp.Shop', $request->shops);
        }

        $data['breaks'] = $break->get();
        // return redirect()->back()->with($data);
        return view('admin.brand-share.break')->with($data);
    }

    public function outOfStock(){
        $data['brands'] = Brands::all();
        $data['cities'] = City::all();
        $data['shops'] = Stores::all();
        $data['employees'] = Employee::where('Designation', 7)->get();
        return view('admin.brand-share.out-of-stock')->with($data);
    }

    public function outOfStockReport(Request $request){
        if(!$request->isMethod('post')){
            return redirect()->route('admin.outOfStock');
        }
        $data['brands'] = Brands::all();
        $data['cities'] = City::all();
        $data['shops'] = Stores::all();
        $data['employees'] = Employee::where('Designation', 7)->get();

        $date[0] = $request->report_from ? $request->report_from. " 00:00:00" : date('Y-m-d H:i:s');
        $date[1] = $request->report_to ? $request->report_to. " 23:59:59" : date('Y-m-d H:i:s');

        $stock = Order::query();
        $stock->select("b.BrandName", "c.Category", "sk.name as sku", "s.name as shop", "orders.created_at as date", "ct.name as city");
        $stock->join("categories as c", "c.id", "=", 'orders.skuCategory');
        $stock->join("skus as sk", "sk.id", "=", 'orders.SKU');
        $stock->join("brands as b", "b.id", "=", 'c.brand_id');
        $stock->join("shops as s", "s.id", "=", 'orders.storeId');
        $stock->join("cities as ct", "ct.id", "=", 's.city_id');
        
        if($request->brands != -1){
            
            $stock->where('b.id', $request->brands);
        }
        
        if($request->cities != -1){
            $stock->where('emp.ShopCity', $request->cities);
        }
        
        if($request->shops != -1){
            $stock->where('orders.storeId', $request->shops);
        }
        $stock->whereBetween('orders.created_at', $date);
        $stock->where('orders.noItem', 0);
        
        $data['stock'] = $stock->get();
        // return redirect()->back()->with($data);
        return view('admin.brand-share.out-of-stock')->with($data);
    }

    //ajax requests
    public function getShopsByBrands($id){
        $shops = Stores::query();
        $shops->select('name', 'id');
        if($id != -1){
            $shops->where('brand_id', $id);
        }
        $shops = $shops->get();
        echo json_encode($shops);
    }

    public function getCitiesByBrands($id){
        $cities = city::query();
        $cities->select('cities.name', 'cities.id');
        $cities->join('brand_cities as bc', 'bc.city_id', '=', 'cities.id', 'inner');
        if($id != -1){
            $cities->where('bc.brand_id', $id);
        }
        $cities = $cities->get();
        echo json_encode($cities);
    }

    public function getBasByBrands($id){
        $employees = Employee::query();
        $employees->select('EmployeeName as name', 'employees.id');
        $employees->join('brand_employees as be', 'be.emp_id', '=', 'employees.id', 'inner');
        if($id != -1){
            $employees->where('be.brand_id', $id);
        }
        $employees = $employees->get();
        echo json_encode($employees);
    }


}
