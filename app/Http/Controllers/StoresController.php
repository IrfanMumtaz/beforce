<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\CreateStoresRequest;
use App\Http\Requests\UpdateStoresRequest;
use App\Repositories\StoresRepository;
use App\Http\Controllers\AppBaseController as InfyOmBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use App\Models\Stores;
use App\Brand;
use App\country;
use App\Shop;
use App\city;
use App\Employee;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class StoresController extends InfyOmBaseController
{
    /** @var  StoresRepository */
    private $storesRepository;

    public function __construct(StoresRepository $storesRepo)
    {
        
    }

    /**
     * Display a listing of the Stores.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $all_stores=Shop::where('deleted_at',NULL)->get();
        foreach ($all_stores as $key => $value) {
            $brands=Brand::where('id',$value->brand_id)->get();
            foreach ($brands as $brand_key => $brand_value) {
                $all_stores[$key]->Region.=$brand_value->BrandName;
            }
            $cities=city::where('id',$value->city_id)->get();
            foreach ($cities as $city_key => $city_value) {
                $all_stores[$key]->Storecity.=$city_value->name;
            }
        }
        return view('admin.stores.index')
            ->with('stores', $all_stores);
    }
    
    public function app_shop(Request $request)
    {
        $all_stores=\DB::table('shopsapp')->where('deleted_at',NULL)->where('transfer',0)->get();
        foreach ($all_stores as $key => $value) {
            $emp=Employee::find($value->emp_id);
            if(isset($emp->EmployeeName)) {
            $all_stores[$key]->ba=$emp->EmployeeName;
            }
            else {
                $all_stores[$key]->ba='';
            }
        }
        
        return view('admin.stores.app_shop')
            ->with('stores', $all_stores);
    }

    /**
     * Show the form for creating a new Stores.
     *
     * @return Response
     */
    public function create()
    {
 	    $all_brands=Brand::where('deleted_at',NULL)->get();
        $brands[null]='--Select Brand--';
        foreach ($all_brands as $key => $value) {
            $brands[$value->id]=$value->BrandName;
        }
        $cities_result=country::findOrFail(1)->cities;
        $cities[null]='--Select City--';
        foreach ($cities_result as $key => $value) {
            $cities[$value->id]=$value->name;
        }	
        return view('admin.stores.create')->with('brands', $brands)->with('cities',$cities);

    }

    /**
     * Store a newly created Stores in storage.
     *
     * @param CreateStoresRequest $request
     *
     * @return Response
     */
    public function store(CreateStoresRequest $request)
    {
        
        $store['name']=$request->name;
        $store['Ownername']=$request->Ownername;
        $store['Contactperson']=$request->Contactperson;
        $store['Contactnumber']=$request->Contactnumber;
        $store['latitude']=$request->latitude;
        $store['longitude']=$request->longitude;
        $store['Storesize']=$request->Storesize;
        $store['Storesize']=$request->Storesize;
        $store['brand_id']=$request->Region;
        $store['city_id']=$request->Storecity;

        $stores = Shop::create($store);

        Flash::success('Stores saved successfully.');
        return redirect(route('admin.stores.create'));
    }

    /**
     * Display the specified Stores.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $Shop_data=Shop::findOrFail($id);
        $brands=Brand::where('id',$Shop_data->brand_id)->get();
        foreach ($brands as $brand_key => $brand_value) {
            $Shop_data->Region.=$brand_value->BrandName;
        }
        $cities=city::where('id',$Shop_data->city_id)->get();
        foreach ($cities as $city_key => $city_value) {
            $Shop_data->Storecity.=$city_value->name;
        }

        if (empty($Shop_data)) {
            Flash::error('Stores not found');

            return redirect(route('stores.index'));
        }

        return view('admin.stores.show')->with('stores', $Shop_data);
    }

    /**
     * Show the form for editing the specified Stores.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $stores=Shop::findOrFail($id);
        if (empty($stores)) {
            Flash::error('Stores not found');

            return redirect(route('stores.index'));
        }
        $cities_result=country::findOrFail(1)->cities;
        foreach ($cities_result as $key => $value) {
            $cities[$value->id]=$value->name;
        }
        $all_brands=Brand::where('deleted_at',NULL)->get();
        foreach ($all_brands as $key => $value) {
            $brands[$value->id]=$value->BrandName;
        }

        return view('admin.stores.edit')->with('stores', $stores)->with('brands', $brands)->with('cities', $cities)->with('select_City',$stores->city_id)->with('selected_brand',$stores->brand_id)->with('selected_size',$stores->Storesize);

    }
    
    public function app_shop_edit($id)
    {
        $stores=\DB::table('shopsapp')->find($id);
        $stores->shopname=trim($stores->shopname,'"');
        $stores->name=$stores->shopname;
        $stores->Ownername=$stores->owner;
        $stores->Contactperson=$stores->contactperson;
        $stores->Contactnumber=$stores->contactnumber;
        $stores->latitude=$stores->lat;
        $stores->longitude=$stores->lng;
        $stores->city_id='';
        $stores->brand_id='';
        $stores->Storesize='';
        if (empty($stores)) {
            Flash::error('Stores not found');

            return redirect(route('stores.index'));
        }
        $cities=array();
        $cities[null]='--Select City--';
        $cities_result=country::find(1)->cities;
        if(count($cities_result)>0) {
        foreach ($cities_result as $key => $value) {
            $cities[$value->id]=$value->name;
        }
        }
        $brands[null]='--Select Region--';
        $all_brands=Brand::where('deleted_at',NULL)->get();
        foreach ($all_brands as $key => $value) {
            $brands[$value->id]=$value->BrandName;
        }

        return view('admin.stores.appedit')->with('stores', $stores)->with('brands', $brands)->with('cities', $cities)->with('select_City',$stores->city_id)->with('selected_brand',$stores->brand_id)->with('selected_size',$stores->Storesize);

    }

    /**
     * Update the specified Stores in storage.
     *
     * @param  int              $id
     * @param UpdateStoresRequest $request
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        $request->validate([
        'name' => 'required',
        'latitude'=> 'required',
        'longitude'=> 'required',
        'Storesize'=> 'required',
        'Region'=> 'required',
        'Storecity'=> 'required'
            ]);
    $data['name']=$request->name;
    $data['Ownername']=$request->Ownername;
    $data['Contactperson']=$request->Contactperson;
    $data['Contactnumber']=$request->Contactnumber;
    $data['latitude']=$request->latitude;
    $data['longitude']=$request->longitude;
    $data['Storesize']=$request->Storesize;
    $data['brand_id']=$request->Region;
    $data['city_id']=$request->Storecity;
    $stores =Shop::whereId($id)->update($data);
        if (empty($stores)) {
            Flash::error('Stores not found');

            return redirect(route('stores.index'));
        }

        Flash::success('Stores updated successfully.');

        return redirect(route('admin.stores.edit',['stores'=>$id]));
    }
    
    public function appupdate($id, Request $request)
    {
        $request->validate([
        'name' => 'required',
        'latitude'=> 'required',
        'longitude'=> 'required',
        'Storesize'=> 'required',
        'Region'=> 'required',
        'Storecity'=> 'required'
            ]);
    $store['name']=$request->name;
    $store['Ownername']=$request->Ownername;
    $store['Contactperson']=$request->Contactperson;
    $store['Contactnumber']=$request->Contactnumber;
    $store['latitude']=$request->latitude;
    $store['longitude']=$request->longitude;
    $store['Storesize']=$request->Storesize;
    $store['brand_id']=$request->Region;
    $store['city_id']=$request->Storecity;

    $stores = Shop::create($store);
    
    $data['shopname']=$request->name;
    $data['owner']=$request->Ownername;
    $data['contactperson']=$request->Contactperson;
    $data['contactnumber']=$request->Contactnumber;
    $data['lat']=$request->latitude;
    $data['lng']=$request->longitude;
    $data['transfer']=1;
    $stores =\DB::table('shopsapp')->where('id',$id)->update($data);
        if (empty($stores)) {
            Flash::error('Stores not found');

            return redirect(route('stores.app_shop'));
        }
        Flash::success('Stores updated successfully.');

        return redirect(route('admin.stores.appedit',['stores'=>$id]));
    }

    /**
     * Remove the specified Stores from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
      public function getModalDelete($id = null)
      {
          $error = '';
          $model = '';
          $confirm_route =  route('admin.stores.delete',['id'=>$id]);
          return View('admin.layouts/modal_confirmation', compact('error','model', 'confirm_route'));

      }

       public function getDelete($id = null)
       {
           $sample = Shop::destroy($id);

           // Redirect to the group management page
           return redirect(route('admin.stores.index'))->with('success', Lang::get('message.success.delete'));

       }
       public function getdata(Request $request) {
        $from=$request->dateFrom;
        $to=$request->dateTo;
        if(!empty($from) && !empty($to)) {
        $shops=Shop::whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59'])->where('deleted_at',NULL)->get();
        }
        else {
        $shops=Shop::where('deleted_at',NULL)->get();
        foreach ($shops as $key => $value) {
            $brands=Brand::where('id',$value->brand_id)->get();
            foreach ($brands as $brand_key => $brand_value) {
                $shops[$key]->Region.=$brand_value->BrandName;
            }
            $cities=city::where('id',$value->city_id)->get();
            foreach ($cities as $city_key => $city_value) {
                $shops[$key]->Storecity.=$city_value->name;
            }
        }
        }
        return view('admin.stores.index')
            ->with('stores', $shops)->with('from',$from)->with('to',$to);
       }
       public function get_app_shop_data(Request $request) {
        $from=$request->dateFrom;
        $to=$request->dateTo;
        if(!empty($from) && !empty($to)) {
        $shops=\DB::table('shopsapp')->whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59'])->where('deleted_at',NULL)->where('transfer',0)->get();
        foreach ($shops as $key => $value) {
            $emp=Employee::find($value->emp_id);
            if(isset($emp->EmployeeName)) {
            $shops[$key]->ba=$emp->EmployeeName;
            }
            else {
                $shops[$key]->ba='';
            }
        }
        
        }
        return view('admin.stores.app_shop')
            ->with('stores', $shops)->with('from',$from)->with('to',$to);
       }

}
