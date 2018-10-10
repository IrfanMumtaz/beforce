<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\CreateSKURequest;
use App\Http\Requests\UpdateSKURequest;
use App\Repositories\SKURepository;
use App\Http\Controllers\AppBaseController as InfyOmBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Sku;
use App\Category;
use File;
use URL;
class SKUController extends InfyOmBaseController
{
    /** @var  SKURepository */
    private $sKURepository;

    public function __construct(SKURepository $sKURepo)
    {
        $this->sKURepository = $sKURepo;
    }

    /**
     * Display a listing of the SKU.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $sKUS = Sku::where('deleted_at',NULL)->get();
        foreach ($sKUS as $key => $value) {
            $categories=Category::where('id',$value->category_id)->get();
            foreach ($categories as $category_key => $category_value) {
                $sKUS[$key]->SKUCaategory.=$category_value->Category;
            }
                $sKUS[$key]->SKUImage=URL::to('/storage/skuimages/').'/'.$sKUS[$key]->SKUImage;
        }
        return view('admin.sKUS.index')
            ->with('sKUS', $sKUS);
    }

    /**
     * Show the form for creating a new SKU.
     *
     * @return Response
     */
    public function create()
    {
        $all_categories=Category::where([['deleted_at',NULL],['Competition',0]])->get();
        foreach ($all_categories as $key => $value) {
            $cate[$value->id]=$value->Category;
        }
        return view('admin.sKUS.create')->with('cate', $cate);

    }

    /**
     * Store a newly created SKU in storage.
     *
     * @param CreateSKURequest $request
     *
     * @return Response
     */
    public function store(CreateSKURequest $request)
    {
        $name='';
        if($request->hasFile('SKUImage')) {
            $imgfile = $request->file('SKUImage');
            $imgpath = 'storage/skuimages';
            File::makeDirectory($imgpath, $mode = 0777, true, true);
            $imgDestinationPath = $imgpath.'/';
            $name = time()."_".$imgfile->getClientOriginalName();
            $filename1 = $name;
            $usuccess = $imgfile->move($imgDestinationPath, $filename1);
        }
        $input = $request->all();
        $input['category_id']=$request->CateId;
        $input['name']=$request->name;
        $input['Price']=$request->Price;
        $input['ItemPerCarton']=$request->ItemPerCarton;
        $input['SKUImage']=$name;

        $sKU = Sku::create($input);

        Flash::success('SKU saved successfully.');

        return redirect(route('admin.sKUS.create'));
    }

    /**
     * Display the specified SKU.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $sKU = Sku::findOrFail($id);
        $sKU->SKUImage=URL::to('/storage/skuimages/').'/'.$sKU->SKUImage;
            $categories=Category::where('id',$sKU->category_id)->get();
            foreach ($categories as $category_key => $category_value) {
                $sKU['SKUCaategory']=$category_value->Category;
            }
        if (empty($sKU)) {
            Flash::error('SKU not found');

            return redirect(route('sKUS.index'));
        }

        return view('admin.sKUS.show')->with('sKU', $sKU);
    }

    /**
     * Show the form for editing the specified SKU.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $sKU = Sku::findOrFail($id);
        $sKU->SKUImage=URL::to('/storage/skuimages/').'/'.$sKU->SKUImage;        
        $all_categories=Category::where('deleted_at',NULL)->get();
        foreach ($all_categories as $key => $value) {
            $cate[$value->id]=$value->Category;
        }
        if (empty($sKU)) {
            Flash::error('SKU not found');

            return redirect(route('sKUS.index'));
        }
        return view('admin.sKUS.edit')->with('sKU', $sKU)->with('cate', $cate)->with('selected_category',$sKU->category_id);
    }

    /**
     * Update the specified SKU in storage.
     *
     * @param  int              $id
     * @param UpdateSKURequest $request
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        $request->validate([
            'CateId' => 'required',
            'name' => 'required',
            'Price' => 'required'
            ]);
        $sku_image=Sku::findOrFail($id);
        $data['category_id']=$request->CateId;
        $data['name']=$request->name;
        $data['Price']=$request->Price;
        $data['ItemPerCarton']=$request->ItemPerCarton;
        if($request->hasFile('SKUImage')) {
            $imgfile = $request->file('SKUImage');
            $imgpath = 'storage/skuimages';
            File::makeDirectory($imgpath, $mode = 0777, true, true);
            $imgDestinationPath = $imgpath.'/';
            $name = $sku_image->id.''.$imgfile->getClientOriginalName();
            $data['SKUImage']=$name;
            $old_image=$imgDestinationPath.$sku_image->SKUImage;
            if (File::exists($old_image)) {
            File::delete($old_image);
            }
            $usuccess = $imgfile->move($imgDestinationPath, $name);
            $data['SKUImage']=$name;
        }
        $sKU=Sku::whereId($id)->update($data);

        if (empty($sKU)) {
            Flash::error('SKU not found');

            return redirect(route('sKUS.index'));
        }
        Flash::success('SKU updated successfully.');

        return redirect(route('admin.sKUS.edit',['sKUS'=>$id]));
    }

    /**
     * Remove the specified SKU from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
      public function getModalDelete($id = null)
      {
          $error = '';
          $model = '';
          $confirm_route =  route('admin.sKUS.delete',['id'=>$id]);
          return View('admin.layouts/modal_confirmation', compact('error','model', 'confirm_route'));

      }

       public function getDelete($id = null)
       {
           $sample = Sku::destroy($id);

           // Redirect to the group management page
           return redirect(route('admin.sKUS.index'))->with('success', Lang::get('message.success.delete'));

       }
       public function getdata(Request $request) {
        $from=$request->dateFrom;
        $to=$request->dateTo;
        if(!empty($from) && !empty($to)) {
        $sKUS=Sku::whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59'])->where('deleted_at',NULL)->get();
        foreach ($sKUS as $key => $value) {
            $categories=Category::where('id',$value->category_id)->get();
            foreach ($categories as $category_key => $category_value) {
                $sKUS[$key]->SKUCaategory.=$category_value->Category;
            }
                $sKUS[$key]->SKUImage=URL::to('/storage/skuimages/').'/'.$sKUS[$key]->SKUImage;
        }
        }
        else {
            $sKUS = Sku::where('deleted_at',NULL)->get();
        foreach ($sKUS as $key => $value) {
            $categories=Category::where('id',$value->category_id)->get();
            foreach ($categories as $category_key => $category_value) {
                $sKUS[$key]->SKUCaategory.=$category_value->Category;
            }
                $sKUS[$key]->SKUImage=URL::to('/storage/skuimages/').'/'.$sKUS[$key]->SKUImage;
        }
        }
        return view('admin.sKUS.index')
            ->with('sKUS', $sKUS)->with('from',$from)->with('to',$to);
       }

}
