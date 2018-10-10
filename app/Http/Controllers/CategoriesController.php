<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\CreateCategoriesRequest;
use App\Http\Requests\UpdateCategoriesRequest;
use App\Repositories\CategoriesRepository;
use App\Http\Controllers\AppBaseController as InfyOmBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use App\Models\Categories;
use App\Category;
use App\Brand;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class CategoriesController extends InfyOmBaseController
{
    /** @var  CategoriesRepository */
    private $categoriesRepository;

    public function __construct(CategoriesRepository $categoriesRepo)
    {
        $this->categoriesRepository = $categoriesRepo;
    }

    /**
     * Display a listing of the Categories.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {

        $categories = Category::where('deleted_at',NULL)->get();
        foreach ($categories as $key => $value) {
            $brands=Brand::where('id',$value->brand_id)->get();
            foreach ($brands as $brand_key => $brand_value) {
                $categories[$key]->brand.=$brand_value->BrandName;
            }
        }
        return view('admin.categories.index')
            ->with('categories', $categories);
    }

    /**
     * Show the form for creating a new Categories.
     *
     * @return Response
     */
    public function create()
    {

    $all_brands=Brand::where('deleted_at',NULL)->get();
    foreach ($all_brands as $key => $value) {
        $brands[$value->id]=$value->BrandName;
    }

        return view('admin.categories.create')->with('brands', $brands);

    }

    /**
     * Store a newly created Categories in storage.
     *
     * @param CreateCategoriesRequest $request
     *
     * @return Response
     */
    public function store(CreateCategoriesRequest $request)
    {
       
        	$input['brand_id'] = $request->brand;
            $input['Category'] = $request->Category;
            if(!empty($request->Competition)){ 	
        	$input['Competition']=$request->Competition;
            }
        	$categories = Category::create($input);
            Flash::success('Categories saved successfully.');
            return redirect(route('admin.categories.create'));
    }

    /**
     * Display the specified Categories.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $categories = Category::findOrFail($id);
            $brands=Brand::where('id',$categories->brand_id)->get();
            foreach ($brands as $brand_key => $brand_value) {
                $categories['brand'].=$brand_value->BrandName;
            }
        if (empty($categories)) {
            Flash::error('Categories not found');

            return redirect(route('categories.index'));
        }

        return view('admin.categories.show')->with('categories', $categories);
    }

    /**
     * Show the form for editing the specified Categories.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {

        $categories = Category::findOrFail($id);
        $brand=Brand::where('id',$categories->brand_id)->get();
            foreach ($brand as $brand_key => $brand_value) {
                $selected_brand[]=$brand_value->id;
            }
        $all_brands=Brand::where('deleted_at',NULL)->get();
        foreach ($all_brands as $key => $value) {
            $brands[$value->id]=$value->BrandName;
        }

        if (empty($categories)) {
            Flash::error('Categories not found');

            return redirect(route('categories.index'));
        }
        return view('admin.categories.edit')->with('categories', $categories)->with('brands', $brands)->with('selected_brand',$selected_brand);
    }

    /**
     * Update the specified Categories in storage.
     *
     * @param  int              $id
     * @param UpdateCategoriesRequest $request
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        $request->validate([
            'Category'=> 'required',
            'brand'=> 'required'
            ]);
        $data['Category']=$request->Category;
        $data['brand_id']=$request->brand;
        if(isset($request->Competition))
        {
            $data['Competition']=$request->Competition;
        }
        else
        {
            $data['Competition']=0;
        }
        $categories = Category::whereId($id)->update($data);

        if (empty($categories)) {
            Flash::error('Categories not found');

            return redirect(route('categories.index'));
        }

        Flash::success('Categories updated successfully.');

        return redirect(route('admin.categories.edit',['categories'=>$id]));
    }

    /**
     * Remove the specified Categories from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
      public function getModalDelete($id = null)
      {
          $error = '';
          $model = '';
          $confirm_route =  route('admin.categories.delete',['id'=>$id]);
          return View('admin.layouts/modal_confirmation', compact('error','model', 'confirm_route'));

      }

       public function getDelete($id = null)
       {
           $sample = Category::destroy($id);

           // Redirect to the group management page
           return redirect(route('admin.categories.index'))->with('success', Lang::get('message.success.delete'));

       }
       public function getdata(Request $request) {
        $from=$request->dateFrom;
        $to=$request->dateTo;
        if(!empty($from) && !empty($to)) {
        $categories=Category::whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59'])->where('deleted_at',NULL)->get();
        foreach ($categories as $key => $value) {
            $brands=Brand::where('id',$value->brand_id)->get();
            foreach ($brands as $brand_key => $brand_value) {
                $categories[$key]->brand.=$brand_value->BrandName;
            }
        }
        }
        else {
            $categories = Category::where('deleted_at',NULL)->get();
        foreach ($categories as $key => $value) {
            $brands=Brand::where('id',$value->brand_id)->get();
            foreach ($brands as $brand_key => $brand_value) {
                $categories[$key]->brand.=$brand_value->BrandName;
            }
        }
        }
       return view('admin.categories.index')
            ->with('categories', $categories)->with('from',$from)->with('to',$to);
       }

}
