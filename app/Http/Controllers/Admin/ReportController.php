<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Brands;
use App\Models\Categories;
use App\Models\Stores;
use App\Employee;
use App\Attendance;
use App\Order;
use App\sales;
use App\Breaks;
use App\city as BrandCity;
use App\Category;
use App\Task;
use App\city;
use App\Sku;
use DB;
use Excel;

class ReportController extends Controller
{
    //

    public function brandShare(){
        $data['brands'] = Brands::all();
        $data['shops'] = Stores::all();
        $data['categories'] = Category::where("Competition", 0)->get();
        $data['cities'] = BrandCity::all();
        $data['skus'] = Sku::all();
        $categories = new Categories();
        $data['saleType'] = $categories->categoryStatus();
        return view('admin.brand-share.index')->with($data);
    }

    public function brandShareAjax(Request $request){

        switch ($request->request_to) {
            case 'brand_share':
                    $this->brandShareFilter($request->only('sale_type', 'brand', 'shops', 'report_from', 'report_to', 'categories', 'cities'));
                break;

            case 'interception_brand':
                    $this->inteceptionBrandShare($request->only('brand', 'shops', 'report_from', 'report_to', 'categories', 'cities'));
                break;

            case 'total_interception':
                    $this->totalInterception($request->only('brand', 'shops', 'report_from', 'report_to', 'categories', 'cities'));
                break;

            case 'target_sale':
                    $this->totalVsSale($request->only('brand', 'shops', 'report_from', 'report_to', 'categories', 'cities'));
                break;

            case 'brand_size':
                $this->brandSize($request->only('brand', 'categories', 'skus', 'cities', 'shops', 'report_from', 'report_to'));
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
        $brand->join('shops as sh', 'sh.id', '=', 'o.storeId', 'inner');
        if($req['brand'] != -1){
            $brand->where('brands.id', $req['brand']);
        }
        if($req['shops'] != -1){
            $brand->where('o.storeId', $req['shops']);
        }
        if($req['categories'] != -1){
            $brand->where('o.skuCategory', $req['categories']);
        }
        if( $req['cities'] != -1){
            $brand->where('sh.city_id', $req['cities']);
        }
        $brand->where('c.Competition', $req['sale_type']);
        $brand->whereBetween('o.created_at', $date);
        $brand->groupBy('o.skuCategory');
        $result = $brand->get();

        echo json_encode($result);
    }

    private function brandSize($req){
        $date[0] = $req['report_from'] ? $req['report_from']. " 00:00:00" : date('Y-m-d H:i:s');
        $date[1] = $req['report_to'] ? $req['report_to']. " 23:59:59" : date('Y-m-d H:i:s');

        $brand = Brands::query();
        $brand->select('sk.name as name', DB::raw('count(o.id) as y'));
        $brand->join('categories as c', 'c.brand_id', '=', 'brands.id', 'inner');
        $brand->join('skus as sk', 'sk.category_id', '=', 'c.id', 'inner');
        $brand->join('Orders as o', 'o.SKU', '=', 'sk.id', 'inner');
        $brand->join('shops as sh', 'sh.id', '=', 'o.storeId', 'inner');
        if($req['brand'] != -1){
            $brand->where('brands.id', $req['brand']);
        }
        if(!in_array(-1, $req['shops'])){
            $brand->whereIn('o.storeId', $req['shops']);
        }
        if(!in_array(-1, $req['categories'])){
            $brand->whereIn('o.skuCategory', $req['categories']);
        }
        if(!in_array(-1, $req['cities'])){
            $brand->whereIn('sh.city_id', $req['cities']);
        }
        if(!in_array(-1, $req['skus'])){
            $brand->whereIn('sk.id', $req['skus']);
        }
        $brand->where('c.Competition', 0);
        $brand->whereBetween('o.created_at', $date);
        $brand->groupBy('o.SKU');
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
        $brand->join('shops as sh', 'sh.id', '=', 'o.storeId', 'inner');
        if($req['brand'] != -1){
            $brand->where('brands.id', $req['brand']);
        }
        if(!in_array(-1, $req['shops'])){
            $brand->whereIn('o.storeId', $req['shops']);
        }
        if(!in_array(-1, $req['categories'])){
            $brand->whereIn('o.skuCategory', $req['categories']);
        }
        if(!in_array(-1, $req['cities'])){
            $brand->whereIn('sh.city_id', $req['cities']);
        }
        $brand->where('c.Competition', 0);
        $brand->whereBetween('o.created_at', $date);
        $brand->groupBy('o.SKU');
        $result = $brand->get();

        echo json_encode($result);
    }

    private function totalInterception($req){
        $date[0] = $req['report_from'] ? $req['report_from']. " 00:00:00" : date('Y-m-d H:i:s');
        $date[1] = $req['report_to'] ? $req['report_to']. " 23:59:59" : date('Y-m-d H:i:s');

        $brand = Brands::query();
        $brand->select('sh.name as name', DB::raw('count(o.id) as y'));
        $brand->join('categories as c', 'c.brand_id', '=', 'brands.id', 'inner');
        $brand->join('Orders as o', 'o.skuCategory', '=', 'c.id', 'inner');
        $brand->join('shops as sh', 'sh.id', '=', 'o.storeId', 'inner');  

        if($req['brand'] != -1){
            $brand->where('brands.id', $req['brand']);
        }
        if(!in_array(-1, $req['shops'])){
            $brand->whereIn('o.storeId', $req['shops']);
        }
        if(!in_array(-1, $req['categories'])){
            $brand->whereIn('o.skuCategory', $req['categories']);
        }
        if(!in_array(-1, $req['cities'])){
            $brand->whereIn('sh.city_id', $req['cities']);
        }
        $brand->whereBetween('o.created_at', $date);
        $brand->groupBy('o.storeId');
        $result = $brand->get();

        echo json_encode($result);
    }

    private function totalVsSale($req){
        $date[0] = $req['report_from'] ? $req['report_from']. " 00:00:00" : date('Y-m-d H:i:s');
        $date[1] = $req['report_to'] ? $req['report_to']. " 23:59:59" : date('Y-m-d H:i:s');



        while (strtotime($date[0]) <= strtotime($date[1])){

            // $created_at = array();
            

            $created_at[0] = date ("Y-m-d 00:00:00", strtotime($date[0]));
            $created_at[1] = date ("Y-m-d 23:59:59", strtotime($date[0]));

            $brand = Brands::query();
            $brand = $brand->select(DB::raw('LEFT(t.created_at, 10) as categories'),db::raw('(s.price * t.skutargets) as targets'));
            $brand->join('categories as c', 'c.brand_id', '=', 'brands.id', 'inner');
            $brand->join('skus as s', 's.category_id', '=', 'c.id', 'inner');
            $brand->join('skutargets as t', 's.id', '=', 't.sku_id', 'inner');
            $brand->join('shops as sh', 'sh.brand_id', '=', 'brands.id', 'inner');

            if($req['brand'] != -1){
                $brand->where('brands.id', $req['brand']);
            }
            if($req['shops'] != -1){
                $brand->where('sh.id', $req['shops']);
            }
            if($req['categories'] != -1){
                $brand->where('c.id', $req['categories']);
            }
            if($req['cities'] != -1){
                $brand->where('sh.city_id', $req['cities']);
            }
            $brand->whereBetween('t.created_at', $created_at);
            $brand = $brand->get();

            echo json_encode($brand);
            $date[0] = date ("Y-m-d H:i:s", strtotime("+1 day", strtotime($date[0])));
        }
            exit();

            /*$sales = Sales::query();
            $sales->select(db::raw('sum(t.skutargets) as targets'));
            $sales->join('categories as c', 'c.brand_id', '=', 'brands.id', 'inner');
            $sales->join('skus as s', 's.category_id', '=', 'c.id', 'inner');
            $sales->join('skutargets as t', 's.id', '=', 't.sku_id', 'inner');
            $sales->join('shops as sh', 'sh.brand_id', '=', 'brands.id', 'inner');

            if($req['brand'] != -1){
                $sales->where('brands.id', $req['brand']);
            }
            if($req['shops'] != -1){
                $sales->where('sh.id', $req['shops']);
            }
            if($req['categories'] != -1){
                $sales->where('c.id', $req['categories']);
            }
            if($req['cities'] != -1){
                $sales->where('sh.city_id', $req['cities']);
            }
            $sales->whereBetween('t.created_at', $created_at);
            $sales = $brand->first();*/

            $return[] = ['categories' => date ("Y-m-d", strtotime($date[0])), 'sale_targets' => $brand->targets];

            
            $date[0] = date ("Y-m-d H:i:s", strtotime("+1 day", strtotime($date[0])));
        

        echo json_encode($return);
        exit();

        $brand = Brands::query();
        $brand->select(DB::raw('LEFT(t.created_at, 10) as categories'), DB::raw('s.Price * sum(t.skutargets) as sale_target'), DB::raw('s.Price * sum(o.noItem) as sale'));
        $brand->join('categories as c', 'c.brand_id', '=', 'brands.id', 'inner');
        $brand->join('skus as s', 's.category_id', '=', 'c.id', 'inner');
        $brand->join('skutargets as t', 's.id', '=', 't.sku_id', 'inner');
        $brand->join('Orders as o', 'o.SKU', '=', 's.id', 'left');
        $brand->join('shops as sh', 'sh.id', '=', 'o.storeId', 'inner'); 
        
        if($req['brand'] != -1){
            $brand->where('brands.id', $req['brand']);
        }
        if($req['shops'] != -1){
            $brand->where('o.storeId', $req['shops']);
        }
        if($req['categories'] != -1){
            $brand->where('o.skuCategory', $req['categories']);
        }
        if($req['cities'] != -1){
            $brand->where('sh.city_id', $req['cities']);
        }
        $brand->whereBetween('o.created_at', $date);
        $brand->groupBy('categories');
        $result = $brand->get();
        /*print_r($result);
        exit();*/

        echo json_encode($result);
    }

    public function genderWise(){
        $data['brands'] = Brands::all();
        $data['shops'] = Stores::all();
        $data['cities'] = City::all();
        $data['categories'] = Category::all();
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
                $this->clusterSurvey($request->only('shops', 'brand', 'report_from', 'cities', 'categories'));
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
        $sales->select("sales.created_at");
        $sales->join("categories as c", "c.id", "=", DB::raw("(SELECT TRIM(BOTH '\"' FROM sales.cBrand) AS cBrands)"), "inner");
        $sales->join("cities as ci", "ci.name", "LIKE", DB::raw("(SELECT TRIM(BOTH '\"' FROM sales.City) AS citiyName)"), "inner");
        $sales->whereBetween('sales.created_at', $date);  
        if($request['shops'] != -1){
            $sales->where("Location", $request['shops']);
        }
        if($request['brand'] != -1){
            $sales->where("c.brand_id", "=",  $request['brand']);
        }
        if($request['categories'] != -1){
            $sales->where("cBrand", "LIKE",  "%".$request['categories']."%");
        }
        if($request['cities'] != -1){
            $sales->where("ci.id", "=",  $request['cities']);
        }
        $sales->where("saleStatus", 1);
        $sales = $sales->get();

        // dd($sales);
        $sale = array();
        $categories = array();
        $saleGroup = array(
                "00:00" => 0, "01:00" => 0, "02:00" => 0, "03:00" => 0, "04:00" => 0, "05:00" => 0, 
                "06:00" => 0, "07:00" => 0, "08:00" => 0, "09:00" => 0, "10:00" => 0, "11:00" => 0, 
                "12:00" => 0, "13:00" => 0, "14:00" => 0, "15:00" => 0, "16:00" => 0, "17:00" => 0, 
                "18:00" => 0, "19:00" => 0, "20:00" => 0, "21:00" => 0, "22:00" => 0, "23:00" => 0);

        foreach($sales as $s){
            $hour = date("H:00", strtotime($s->created_at));
            $saleGroup[(string)$hour] += 1;   
        }
        foreach($saleGroup as $k => $v){
            $categories[] =$k;
            $sale[] =$v;
        }
        $average = array_sum($saleGroup) / 24;
        for($i = 0; $i <24; $i++){
            $avgArr[] = $average;
        }
        echo json_encode([$avgArr,$categories, $sale]);
    }

    public function interception(){
        $data['brands'] = Brands::all();
        $data['cities'] = City::all();
        $data['categories'] = Category::all();
        $data['shops'] = Stores::all();
        $data['employees'] = Employee::where('Designation', 7)->get();
        
        return view('admin.brand-share.interception')->with($data);
    }

    public function interceptionReport(Request $request){

        if(!$request->isMethod('post')){
            return redirect()->route('admin.interception');
        }
        // dd($request->all());

        $data['brands'] = Brands::all();
        $data['cities'] = City::all();
        $data['categories'] = Category::all();
        $data['shops'] = Stores::all();
        $data['employees'] = Employee::where('Designation', 7)->get();
        $data['request'] = $request->all();

        $data['interception'] = $this->interceptionReq($request->only('report_from', 'report_to', 'brands', 'cities', 'shops', 'employees', 'categories'));
        $data['competitor'] = $this->competitor($request->only('report_from', 'report_to', 'brands', 'cities', 'shops', 'employees', 'categories'));
        $data['noSale'] = $this->noSale($request->only('report_from', 'report_to', 'brands', 'cities', 'shops', 'employees', 'categories'));

        return view('admin.brand-share.interception')->with($data);
    }

    private function interceptionReq($request){

        $date[0] = $request['report_from'] ? $request['report_from']. " 00:00:00" : date('Y-m-d H:i:s');
        $date[1] = $request['report_to'] ? $request['report_to']. " 23:59:59" : date('Y-m-d H:i:s');

        $sql = "SELECT *, sales.created_at as date , cSale.Category as cName, pSale.Category as pName, (SELECT GROUP_CONCAT(sk.name) FROM Orders AS o INNER JOIN skus AS sk ON sk.id = o.SKU WHERE o.SalesId = sales.`id` GROUP BY o.salesId) AS skuName FROM sales INNER JOIN categories AS cSale ON sales.`cBrand` = CONCAT('\"',cSale.`id`,'\"') INNER JOIN categories AS pSale ON sales.`pBrand` = CONCAT('\"',pSale.`id`,'\"') INNER JOIN brands as cBrand ON cBrand.id = cSale.brand_id INNER JOIN brands as pBrand ON pBrand.id = cSale.brand_id LEFT JOIN shops as s ON s.id = sales.Location INNER JOIN Orders AS o ON o.salesId = sales.id WHERE cSale.Competition = 0 AND sales.saleStatus = 1 AND ";


        if($request['brands'] != -1){
            $sql .= "cBrand.id =  '".$request['brands']."' AND ";
        }

        if($request['categories'] != -1){
            $sql .= "sales.cBrand LIKE '%".$request['categories']."%' AND ";
        }

        if($request['cities'] != -1){
            $sql .= "s.city_id = ".$request['cities']." AND ";
        }

        if($request['shops'] != -1){
            $sql .= "sales.Location = ".$request['shops']." AND ";
        }
        
        if($request['employees'] != -1){
            $sql .= "sales.empId = ".$request['employees']." AND ";
        }
        
        $sql .= "sales.created_at BETWEEN \"$date[0]\" AND \"$date[1]\" GROUP BY sales.id";
        // dd($sql);
        $sales = DB::select(DB::raw($sql));
        return $sales;

    }

    private function competitor($request){

        $date[0] = $request['report_from'] ? $request['report_from']. " 00:00:00" : date('Y-m-d H:i:s');
        $date[1] = $request['report_to'] ? $request['report_to']. " 23:59:59" : date('Y-m-d H:i:s');

        $sql = "SELECT *, s.name as  name, sales.created_at as date , cSale.Category as cName, pSale.Category as pName, (SELECT GROUP_CONCAT(sk.name) FROM Orders AS o INNER JOIN skus AS sk ON sk.id = o.SKU WHERE o.SalesId = sales.`id` GROUP BY o.salesId) AS skuName FROM sales LEFT JOIN categories AS cSale ON sales.`cBrand` = CONCAT('\"',cSale.`id`,'\"') LEFT JOIN categories AS pSale ON sales.`pBrand` = CONCAT('\"',pSale.`id`,'\"') INNER JOIN brands as cBrand ON cBrand.id = cSale.brand_id INNER JOIN brands as pBrand ON pBrand.id = cSale.brand_id LEFT JOIN shops as s ON s.id = sales.Location INNER JOIN Orders AS o ON o.salesId = sales.id INNER JOIN skus AS sk ON sk.id = o.SKU WHERE cSale.Competition = 1 AND ";


        if($request['brands'] != -1){
            $sql .= "cBrand.id =  '".$request['brands']."' AND ";
        }

        if($request['categories'] != -1){
            $sql .= "sales.cBrand LIKE '%".$request['categories']."%' AND ";
        }

        if($request['cities'] != -1){
            $sql .= "s.city_id = ".$request['cities']." AND ";
        }

        if($request['shops'] != -1){
            $sql .= "sales.Location = ".$request['shops']." AND ";
        }
        
        if($request['employees'] != -1){
            $sql .= "sales.empId = ".$request['employees']." AND ";
        }
        
        $sql .= "sales.created_at BETWEEN \"$date[0]\" AND \"$date[1]\" AND sales.saleStatus = 1 AND sales.cBrand NOT IN (SELECT CONCAT('\"',id,'\"') from brands) GROUP BY sales.id";
        
        $sales = DB::select(DB::raw($sql));

        return $sales;
 
    }

    private function noSale($request){

        $date[0] = $request['report_from'] ? $request['report_from']. " 00:00:00" : date('Y-m-d H:i:s');
        $date[1] = $request['report_to'] ? $request['report_to']. " 23:59:59" : date('Y-m-d H:i:s');
        $sql = "SELECT *, sales.created_at as date , cSale.Category as cName, pSale.Category as pName FROM sales LEFT JOIN categories AS cSale ON sales.`cBrand` = CONCAT('\"',cSale.`id`,'\"') LEFT JOIN categories AS pSale ON sales.`pBrand` = CONCAT('\"',pSale.`id`,'\"') INNER JOIN brands as cBrand ON cBrand.id = cSale.brand_id INNER JOIN brands as pBrand ON pBrand.id = cSale.brand_id LEFT JOIN shops as s ON s.id = sales.Location WHERE ";

        if($request['brands'] != -1){
            $sql .= "cBrand.id =  '".$request['brands']."' AND ";
        }

        if($request['categories'] != -1){
            $sql .= "sales.cBrand LIKE '%".$request['categories']."%' AND ";
        }

        if($request['cities'] != -1){
            $sql .= "s.city_id = ".$request['cities']." AND ";
        }

        if($request['shops'] != -1){
            $sql .= "sales.Location = ".$request['shops']." AND ";
        }
        
        if($request['employees'] != -1){
            $sql .= "sales.empId = ".$request['employees']." AND ";
        }
        
        $sql .= "sales.created_at BETWEEN \"$date[0]\" AND \"$date[1]\" AND sales.saleStatus = 0 GROUP BY sales.id";
        
        $sales = DB::select(DB::raw($sql));
        return $sales;
    }

    public function break(){
        $data['brands'] = Brands::all();
        $data['categories'] = Category::all();
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
        $data['categories'] = Category::all();
        $data['cities'] = City::all();
        $data['shops'] = Stores::all();
        $data['employees'] = Employee::where('Designation', 7)->get();

        $date[0] = $request->report_from ? $request->report_from. " 00:00:00" : date('Y-m-d H:i:s');
        $date[1] = $request->report_to ? $request->report_to. " 23:59:59" : date('Y-m-d H:i:s');

        $break = Breaks::query();
        $break->select('emp.*', "b.*", "emp_break.*", "emp_break.created_at as create_date");
        $break->join("employees as emp", "emp.id", "=", 'emp_break.emp_id', 'inner');
        $break->join("break as b", "b.id", "=", 'emp_break.break_id', 'inner');
        $break->join("categories as c", "c.brand_id", "=", 'emp.SelectBrand', 'inner');
        $break->whereBetween('emp_break.created_at', $date);

        if($request->employees != -1){
            $break->where('emp.id', $request->employees);
        }

        if($request->brands != -1){
            $break->where('emp.SelectBrand', $request->brands);
        }

        if($request->cities != -1){
            $break->where('emp.ShopCity', $request->cities);
        }

        if($request->categories != -1){
            $break->where('c.id', $request->categories);
        }

        if($request->shops != -1){
            $break->where('emp.Shop', $request->shops);
        }
        $break->groupBy('emp_break.id');

        $data['breaks'] = $break->get();
        $data['request'] = $request->all();
        // return redirect()->back()->with($data);
        return view('admin.brand-share.break')->with($data);
    }

    public function outOfStock(){
        $data['brands'] = Brands::all();
        $data['categories'] = Category::all();
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
        $data['categories'] = Category::all();
        $data['request'] = $request->all();
        $data['employees'] = Employee::where('Designation', 7)->get();

        $date[0] = $request->report_from ? $request->report_from. " 00:00:00" : date('Y-m-d H:i:s');
        $date[1] = $request->report_to ? $request->report_to. " 23:59:59" : date('Y-m-d H:i:s');

        $stock = Order::query();
        $stock->select("b.BrandName", "c.Category", "sk.name as sku", "s.name as shop", "Orders.created_at as date", "ct.name as city");
        $stock->join("categories as c", "c.id", "=", 'Orders.skuCategory');
        $stock->join("skus as sk", "sk.id", "=", 'Orders.SKU');
        $stock->join("brands as b", "b.id", "=", 'c.brand_id');
        $stock->join("shops as s", "s.id", "=", 'Orders.storeId');
        $stock->join("cities as ct", "ct.id", "=", 's.city_id');
        
        if($request->brands != -1){
            
            $stock->where('b.id', $request->brands);
        }

        if($request->categories != -1){
            
            $stock->where('Orders.skuCategory', $request->categories);
        }
        
        if($request->cities != -1){
            $stock->where('s.city_id', $request->cities);
        }
        
        if($request->shops != -1){
            $stock->where('orders.storeId', $request->shops);
        }
        $stock->whereBetween('Orders.created_at', $date);
        $stock->where('Orders.noItem', 0);
        
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

    public function getShopByBrandsNCity($brand, $city){
        $shops = Stores::query();
        $shops->select('name', 'id');
        if($brand != -1){
            $shops->where('brand_id', $brand);
        }
        if($city != -1){
            $shops->where('city_id', $city);
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
        // $employees->join('brand_employees as be', 'be.emp_id', '=', 'employees.id', 'inner');
        if($id != -1){
            $employees->where('employees.SelectBrand', $id);
        }
        $employees->where('employees.Designation', 7);
        $employees = $employees->get();
        echo json_encode($employees);
    }

    public function getCatByBrands($id, $cat){
        $categories = Category::query();
        $categories->select('Category as name', 'id');
        // $cities->join('brand_cities as bc', 'bc.city_id', '=', 'cities.id', 'inner');
        if($id != -1){
            $categories->where('brand_id', $id);
        }
        if($cat != "undefined"){
            $categories = $categories->where("Competition", $cat);
            
        }
        $categories = $categories->get();
        echo json_encode($categories);
    }

    public function exportData(Request $request){

        switch ($request->request_to) {
            case 'overall-task':
                $excel = $this->overAllTask($request->only('report_from', 'report_to'));
                $excelArray[] = ['#', 'Date', 'Name', 'Brand', 'City', 'Time', 'Shop', 'Task', 'Status', 'Distance Covered', 'Start Meeting', 'End Meeting'];
                foreach ($excel as $key => $ex) {
                    $e[] = $ex->id;
                    $e[] = $ex->date;
                    $e[] = $ex->EmployeeName;
                    $e[] = $ex->BrandName;
                    $e[] = $ex->cityName;
                    $e[] = str_replace('"', '', $ex->startTime);
                    $e[] = $ex->sName;
                    $e[] = $ex->Tasktype;
                    $e[] = ($ex->status == 1) ? "Complete" : "Pending";
                    $e[] = "N/A";
                    $e[] = $ex->startTime;
                    $e[] = $ex->endTime;
                    $excelArray[] = $e;
                    unset($e);
                }
                break;

            case 'individual-task':
                $excel = $this->individualTask($request->only('employees', 'report_from', 'report_to'));
                $excelArray[] = ['#', 'Date', 'Name', 'Brand', 'City', 'Time', 'Shop', 'Task', 'Status', 'Distance Covered', 'Start Meeting', 'End Meeting'];
                foreach ($excel as $key => $ex) {
                    $e[] = $ex->id;
                    $e[] = $ex->date;
                    $e[] = $ex->EmployeeName;
                    $e[] = $ex->BrandName;
                    $e[] = $ex->cityName;
                    $e[] = str_replace('"', '', $ex->startTime);
                    $e[] = $ex->sName;
                    $e[] = $ex->Tasktype;
                    $e[] = ($ex->status == 1) ? "Complete" : "Pending";
                    $e[] = "N/A";
                    $e[] = $ex->startTime;
                    $e[] = $ex->endTime;
                    $excelArray[] = $e;
                    unset($e);
                }
                break;

            case 'attendance':
                $excel = $this->attendanceReport($request->only('employees', 'report_from', 'report_to'));
                // dd($attendance);
                $excelArray[] = ['Date', 'Name', 'Store', 'City', 'Attendance', 'Start Time', 'Attendance End Time'];
                foreach ($excel as $key => $ex) {
                    $e[] = $ex->date;
                    $e[] = $ex->EmployeeName;
                    $e[] = $ex->shopName;
                    $e[] = $ex->cityName;
                    $e[] = $ex->status;
                    $e[] = str_replace('"', '', $ex->startTime);
                    $e[] = str_replace('"', '', $ex->endTime);
                    $excelArray[] = $e;
                    unset($e);
                }  
                break;

            case 'successfull':
                $excel = $this->interceptionReq($request->only('brands', 'categories', 'cities', 'shops', 'employees', 'report_from', 'report_to'));
                // dd($excel[0]->skuName);

                $excelArray[] = ['#', 'Date', 'Store', 'Exployees', 'Name', 'Contact', 'Email', 'Previous Brand', 'Current Brand', "customer Type", 'Gender', 'Age', 'SKUs'];
                foreach ($excel as $key => $ex) {
                    $e[] = ++$key;
                    $e[] = date('Y-m-d', strtotime($ex->date));
                    $e[] = $ex->name;
                    $e[] = str_replace('"', '', $ex->empName);
                    $e[] = str_replace('"', '', $ex->cusName);
                    $e[] = str_replace('"', '', $ex->Contact);
                    $e[] = str_replace('"', '', $ex->email) ;
                    $e[] = $ex->pName;
                    $e[] = $ex->cName;
                    $e[] = ($ex->pName == $ex->cName) ? "existing" : "new";
                    $e[] = str_replace('"', '', $ex->gender);
                    $e[] = $ex->age;
                    // $e[] = $ex->skuName;
                    
                    if($ex->skuName){

                        $skus = explode(",",$ex->skuName);
                        foreach ($skus as $k => $v) {
                            $e[] = $v;
                        }
                    }


                    $excelArray[] = $e;
                    unset($e);
                }
                break;

            case 'competitor':
                $excel = $this->competitor($request->only('brands', 'categories', 'cities', 'shops', 'employees', 'report_from', 'report_to'));
                // dd($excel[0]->skuName);

                $excelArray[] = ['#', 'Date', 'Store', 'Exployees', 'Name', 'Contact', 'Email', 'Previous Brand', 'Current Brand', "customer Type", 'Gender', 'Age', 'SKUs'];
                foreach ($excel as $key => $ex) {
                    $e[] = ++$key;
                    $e[] = date('Y-m-d', strtotime($ex->date));
                    $e[] = $ex->name;
                    $e[] = str_replace('"', '', $ex->empName);
                    $e[] = str_replace('"', '', $ex->cusName);
                    $e[] = str_replace('"', '', $ex->Contact);
                    $e[] = str_replace('"', '', $ex->email) ;
                    $e[] = $ex->pName;
                    $e[] = $ex->cName;
                    $e[] = ($ex->pName == $ex->cName) ? "existing" : "new";
                    $e[] = str_replace('"', '', $ex->gender);
                    $e[] = $ex->age;
                    // $e[] = $ex->skuName;
                    
                    if($ex->skuName){

                        $skus = explode(",",$ex->skuName);
                        foreach ($skus as $k => $v) {
                            $e[] = $v;
                        }
                    }


                    $excelArray[] = $e;
                    unset($e);
                }
                break;

            case 'noSale':
                $excel = $this->noSale($request->only('brands', 'categories', 'cities', 'shops', 'employees', 'report_from', 'report_to'));
                // dd($excel[0]->skuName);

                $excelArray[] = ['#', 'Date', 'Store', 'Exployees', 'Name', 'Contact', 'Email', 'Previous Brand', 'Current Brand', "customer Type", 'Gender', 'Age'];
                foreach ($excel as $key => $ex) {
                    $e[] = ++$key;
                    $e[] = date('Y-m-d', strtotime($ex->date));
                    $e[] = $ex->name;
                    $e[] = str_replace('"', '', $ex->empName);
                    $e[] = str_replace('"', '', $ex->cusName);
                    $e[] = str_replace('"', '', $ex->Contact);
                    $e[] = str_replace('"', '', $ex->email) ;
                    $e[] = $ex->pName;
                    $e[] = $ex->cName;
                    $e[] = ($ex->pName == $ex->cName) ? "existing" : "new";
                    $e[] = str_replace('"', '', $ex->gender);
                    $e[] = $ex->age;

                    $excelArray[] = $e;
                    unset($e);
                }
                break;

            default:
                # code...
                break;
        }


        return Excel::create($request->request_to, function($excel) use ($excelArray) {
            $excel->sheet('sheet 1', function($sheet) use ($excelArray)
            {
                $sheet->fromArray($excelArray);
            });
        })->download('xlsx');
    }

    public function export(){
        $data['brands'] = Brands::all();
        $data['cities'] = City::all();
        $data['categories'] = Category::all();
        $data['shops'] = Stores::all();
        $data['employees'] = Employee::all();
        return view("admin.brand-share.export")->with($data);
    }

    private function attendanceReport($request){
        $date[0] = $request['report_from'] ? $request['report_from']. " 00:00:00" : date('Y-m-d H:i:s');
        $date[1] = $request['report_to'] ? $request['report_to']. " 23:59:59" : date('Y-m-d H:i:s');

        $attendance = Attendance::select(DB::raw("DATE(attendance.created_at) as date"), "emp.EmployeeName", "shops.name as shopName", "cities.name as cityName", DB::raw('(CASE WHEN attendance.status = 1 THEN "P" ELSE "A" END) AS status') ,"attendance.startTime", "attendance.endTime")
                        ->join("employees as emp", "emp.id", "=", "attendance.empid", "inner")
                        ->join("shops", "emp.Shop", "=", "shops.id", "inner")
                        ->join("cities", "emp.ShopCity", "=", "cities.id", "inner")
                        ->where("emp.id", $request['employees'])
                        ->whereBetween("attendance.created_at", $date)->get();
        return $attendance;
    }

    private function overAllTask($request){
        $date[0] = $request['report_from'] ? $request['report_from']. " 00:00:00" : date('Y-m-d H:i:s');
        $date[1] = $request['report_to'] ? $request['report_to']. " 23:59:59" : date('Y-m-d H:i:s');

        $tasks = Task::select("tasks.id", DB::raw("DATE(tasks.created_at) as date"), "emp.EmployeeName", "brands.BrandName", "cities.name as cityName", "tsk.startTime", "shops.name as sName", "tasks.Tasktype", "tasks.status" ,"tsk.startTime", "tsk.endTime")
                        ->join("task_shops as tsk", "tsk.task_id", "=", "tasks.id", "inner")
                        // ->join("emp_tracking as emp_tr", "tsk.task_id", "=", "tasks.id", "inner")
                        ->join("employees as emp", "emp.id", "=", "tasks.emp_id", "inner")
                        ->join("shops", "emp.Shop", "=", "shops.id", "inner")
                        ->join("cities", "emp.ShopCity", "=", "cities.id", "inner")
                        ->join("brands", "brands.id", "=", "emp.SelectBrand", "inner")
                        ->whereBetween("tasks.created_at", $date)->get();
        return $tasks;
    }

    private function individualTask($request){
        $date[0] = $request['report_from'] ? $request['report_from']. " 00:00:00" : date('Y-m-d H:i:s');
        $date[1] = $request['report_to'] ? $request['report_to']. " 23:59:59" : date('Y-m-d H:i:s');

        $tasks = Task::select("tasks.id", DB::raw("DATE(tasks.created_at) as date"), "emp.EmployeeName", "brands.BrandName", "cities.name as cityName", "tsk.startTime", "shops.name as sName", "tasks.Tasktype", "tasks.status" ,"tsk.startTime", "tsk.endTime")
                        ->join("task_shops as tsk", "tsk.task_id", "=", "tasks.id", "inner")
                        // ->join("emp_tracking as emp_tr", "tsk.task_id", "=", "tasks.id", "inner")
                        ->join("employees as emp", "emp.id", "=", "tasks.emp_id", "inner")
                        ->join("shops", "emp.Shop", "=", "shops.id", "inner")
                        ->join("cities", "emp.ShopCity", "=", "cities.id", "inner")
                        ->join("brands", "brands.id", "=", "emp.SelectBrand", "inner")
                        ->where("emp.id", $request['employees'])
                        ->whereBetween("tasks.created_at", $date)->get();
        return $tasks;
    }

    public function brandSizeReport(Request $request){

        $result = Brands::query();
        $result = $result->select("brands.id as b_id", "brands.BrandName as b_name", "c.id as c_id", "c.Category as c_name", "sk.id as sk_id", "sk.name as sk_name", "sh.id as sh_id", "sh.name as sh_name", "ci.id as ci_id", "ci.name as ci_name");
        $result = $result->join("categories as c", "c.brand_id", "=", "brands.id", "inner");
        $result = $result->join("skus as sk", "sk.category_id", "=", "c.id", "inner");
        $result = $result->join("shops as sh", "sh.brand_id", "=", "brands.id", "inner");
        $result = $result->join("cities as ci", "sh.city_id", "=", "ci.id", "inner");

        if($request->brand != -1){
            $result = $result->where('brands.id', $request->brand);
        }

        if(!in_array(-1, $request->categories)){
            $result = $result->whereIn('c.id', $request->categories);
        }

        if(!in_array(-1, $request->skus)){
            $result = $result->whereIn('sk.id', $request->skus);
        }

        if(!in_array(-1, $request->shops)){
            $result = $result->whereIn('sh.id', $request->shops);
        }

        if(!in_array(-1, $request->cities)){
            $result = $result->whereIn('ci.id', $request->cities);
        }

        $result = $result->get();

        $response['brands'] = [];
        $response['categories'] = [];
        $response['skus'] = [];
        $response['shops'] = [];
        $response['cities'] = [];

        foreach ($result as $key => $value) {
            if(!array_key_exists($value->b_id, $response['brands'])){
                $response['brands'][$value->b_id] = [$value->b_id, $value->b_name];
            }
            if(!array_key_exists($value->c_id, $response['categories'])){
                $response['categories'][$value->c_id] = [$value->c_id, $value->c_name];
            }
            if(!array_key_exists($value->sk_id, $response['skus'])){
                $response['skus'][$value->sk_id] = [$value->sk_id, $value->sk_name];
            }
            if(!array_key_exists($value->sh_id, $response['shops'])){
                $response['shops'][$value->sh_id] = [$value->sh_id, $value->sh_name];
            }
            if(!array_key_exists($value->ci_id, $response['cities'])){
                $response['cities'][$value->ci_id] = [$value->ci_id, $value->ci_name];
            }
        }
        echo json_encode($response);
    }


}
