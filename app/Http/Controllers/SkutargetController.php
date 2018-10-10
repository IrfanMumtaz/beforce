<?php

namespace App\Http\Controllers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests;
use App\Http\Requests\CreateSkutargetRequest;
use App\Http\Requests\UpdateSkutargetRequest;
use App\Repositories\SkutargetRepository;
use App\Http\Controllers\AppBaseController as InfyOmBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use App\Skutarget;
use App\Shop;
use App\Brand;
use App\Category;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Sku;
use Carbon\Carbon;
class SkutargetController extends InfyOmBaseController
{
    /** @var  SkutargetRepository */
    private $skutargetRepository;

    public function __construct(SkutargetRepository $skutargetRepo)
    {
    }

    /**
     * Display a listing of the Skutarget.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request,$storeid)
    {
        $skutargets = Skutarget::where('shop_id',$storeid)->get();
        foreach ($skutargets as $key => $value) {
            $shop = Shop::find($value->shop_id);
            $skutargets[$key]->shop_name.=$shop->name;
            $sku = Sku::find($value->sku_id);
            if(isset($sku->name)) {
            $skutargets[$key]->SkuName.=$sku->name;
            }
            if(isset($sku->category_id)) {
            $category = Category::find($sku->category_id);
            $skutargets[$key]->Category.=$category->Category;
            }
        }


       return view('admin.skutargets.index')->with('skutargets', $skutargets)->with('storeid', $storeid);

    }



    /**
     * Show the form for creating a new Skutarget.
     *
     * @return Response
     */
    public function create($id)
    {
        $skuids=array();
        $category_ids=array();
        $skus=array();
        $brand_name='';
        $shops=Shop::find($id);
        $shop_name=$shops->name;
        $brand=Brand::find($shops->brand_id);
        if(isset($brand->BrandName)) {
        $brand_name=$brand->BrandName;
        }
        $categories=Category::where('brand_id',$shops->brand_id)->get();
        foreach ($categories as $key => $value) {
            $category_ids[]=$value->id;
        }
        $skus=Sku::whereIn('category_id',$category_ids)->get();
         foreach ($skus as $key => $value) {
            $categories=Category::find($value->category_id);
             $skus[$key]->Category=$categories->Category;
             $skuids[]=$value->id;
         }
        return view('admin.skutargets.create')->with('brand', $brand_name)->with('store', $shop_name)->with('skus', $skus)->with('storeid', $id)->with('skuids', $skuids);

}

    /**
     * Store a newly created Skutarget in storage.
     *
     * @param CreateSkutargetRequest $request
     *
     * @return Response
     */
    public function store(CreateSkutargetRequest $request)
    {
        $input = $request->all();
        $names = $input['name'];
        $skutargets=$input['skutargets'];
        $shopid=$input['shopid'];
        $skuids=$input['skuids'];
        Skutarget::whereIn('sku_id',$skuids)->where('shop_id',$shopid)->update(array('status' => 0));

        foreach($names as $key=> $name){
        if($skutargets[$key]>0 )
        {
            $data[]=array(
                'skutargets'=>$skutargets[$key],
                'shop_id'=>$shopid[$key],
                'sku_id'=>$skuids[$key],
                'status'=>1,
                'created_at'=> Carbon::now()
            );
        } 
       }

        $result=Skutarget::insert($data);


        Flash::success('Skutarget saved successfully.');

        return redirect(route('admin.stores.index'));


    }

    /**
     * Display the specified Skutarget.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $skutarget = Skutarget::find($id);

        if (empty($skutarget)) {
            Flash::error('Skutarget not found');

            return redirect(route('skutargets.index'));
        }

        return view('admin.skutargets.show')->with('skutarget', $skutarget);
    }

    /**
     * Show the form for editing the specified Skutarget.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $skutarget = $this->skutargetRepository->findWithoutFail($id);

        if (empty($skutarget)) {
            Flash::error('Skutarget not found');

            return redirect(route('skutargets.index'));
        }

        return view('admin.skutargets.edit')->with('skutarget', $skutarget);
    }

    /**
     * Update the specified Skutarget in storage.
     *
     * @param  int              $id
     * @param UpdateSkutargetRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSkutargetRequest $request)
    {
        $skutarget = $this->skutargetRepository->findWithoutFail($id);

        

        if (empty($skutarget)) {
            Flash::error('Skutarget not found');

            return redirect(route('skutargets.index'));
        }

        $skutarget = $this->skutargetRepository->update($request->all(), $id);

        Flash::success('Skutarget updated successfully.');

        return redirect(route('admin.skutargets.index'));
    }

    /**
     * Remove the specified Skutarget from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
      public function getModalDelete($id = null)
      {
          $error = '';
          $model = '';
          $confirm_route =  route('admin.skutargets.delete',['id'=>$id]);
          return View('admin.layouts/modal_confirmation', compact('error','model', 'confirm_route'));

      }

       public function getDelete($id = null)
       {
           $sample = Skutarget::destroy($id);

           // Redirect to the group management page
           return redirect(route('admin.skutargets.index'))->with('success', Lang::get('message.success.delete'));

       }
       public function getdata(Request $request) {
        $from=$request->dateFrom;
        $to=$request->dateTo;
        $storeid=$request->shop_id;
        if(!empty($from) && !empty($to)) {
        $skutargets = Skutarget::whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59'])->where('shop_id',$storeid)->get();
        foreach ($skutargets as $key => $value) {
            $shop = Shop::find($value->shop_id);
            $skutargets[$key]->shop_name.=$shop->name;
            $sku = Sku::find($value->sku_id);
            $skutargets[$key]->SkuName.=$sku->name;
            $category = Category::find($sku->category_id);
            $skutargets[$key]->Category.=$category->Category;
        }
        }
        else
        {
        $skutargets = Skutarget::where('shop_id',$storeid)->get();
        foreach ($skutargets as $key => $value) {
            $shop = Shop::find($value->shop_id);
            $skutargets[$key]->shop_name.=$shop->name;
            $sku = Sku::find($value->sku_id);
            $skutargets[$key]->SkuName.=$sku->name;
            $category = Category::find($sku->category_id);
            $skutargets[$key]->Category.=$category->Category;
        }
        }


       return view('admin.skutargets.index')->with('skutargets', $skutargets)->with('storeid', $storeid)->with('from',$from)->with('to',$to);
       }

}
