<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\CreateAssetRequest;
use App\Http\Requests\UpdateAssetRequest;
use App\Repositories\AssetRepository;
use App\Http\Controllers\AppBaseController as InfyOmBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use App\Asset;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Shop;
use App\Brand;
use App\Asset_type;
use URL;
use File;
use QRCode;
class AssetController extends InfyOmBaseController
{
    /** @var  AssetRepository */
    private $assetRepository;

    public function __construct(AssetRepository $assetRepo)
    {
    }

    /**
     * Display a listing of the Asset.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {

        $assets = Asset::where('deleted_at',NULL)->get();
        foreach ($assets as $key => $value) {
            $asset_type=Asset_type::where('asset_id',$value->id)->get();
            foreach ($asset_type as $asset_type_key => $asset_type_value) {
                $assets[$key]->AssetType.=$asset_type_value->name;
            }
            $asset_shop=Shop::where('id',$value->shop_id)->get();
            foreach ($asset_shop as $asset_shop_key => $asset_shop_value) {
                $assets[$key]->SelectShop.=$asset_shop_value->name;
                $asset_brand=Brand::where('id',$asset_shop_value->brand_id)->get();
                foreach ($asset_brand as $asset_brand_key => $asset_brand_value) {
                    $assets[$key]->Brand.=$asset_brand_value->BrandName;
                }
            }
            $assets[$key]->QRCode=URL::to('/storage/QrImage/').'/'.$assets[$key]->QRCode;
        }
        
        return view('admin.assets.index')
            ->with('assets', $assets);
    }

    /**
     * Show the form for creating a new Asset.
     *
     * @return Response
     */
    public function create()
    {
        $all_shops=Shop::where('deleted_at',NULL)->get();
        $stores[null]='--Select Shop--';
        foreach ($all_shops as $key => $value) {
            $stores[$value->id]=$value->name;
        }
        return view('admin.assets.create')->with('shops', $stores);
    }

    /**
     * Store a newly created Asset in storage.
     *
     * @param CreateAssetRequest $request
     *
     * @return Response
     */
    public function store(CreateAssetRequest $request)
    {
        $input['AssetName']=$request->AssetName;
        $input['shop_id']=$request->SelectShop;
        $input['Description']=$request->Description;
        $imgpath = storage_path('/QrImage');
        File::makeDirectory($imgpath, $mode = 0777, true, true);
        $file=$imgpath.'//'.$request->AssetName.'.png';
        $input['QRCode']=$request->AssetName.'.png';
        QRCode::text($request->AssetName)->setOutfile($file)->png();
        $asset = Asset::create($input);
        
        $data['asset_id']=$asset['id'];
        $data['name']=$request->AssetType;
        $asset = Asset_type::create($data);

        Flash::success('Asset saved successfully.');

        return redirect(route('admin.assets.create'));
    }

    /**
     * Display the specified Asset.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $asset = Asset::find($id);
            $asset_type=Asset_type::where('asset_id',$asset->id)->get();
            foreach ($asset_type as $asset_type_key => $asset_type_value) {
                $asset->AssetType.=$asset_type_value->name;
            }
            $asset_shop=Shop::where('id',$asset->shop_id)->get();
            foreach ($asset_shop as $asset_shop_key => $asset_shop_value) {
                $asset->SelectShop.=$asset_shop_value->name;
                $asset_brand=Brand::where('id',$asset_shop_value->brand_id)->get();
                foreach ($asset_brand as $asset_brand_key => $asset_brand_value) {
                    $asset->Brand.=$asset_brand_value->BrandName;
                }
            }
            $asset->QRCode=URL::to('/storage/QrImage/').'/'.$asset->QRCode;

        if (empty($asset)) {
            Flash::error('Asset not found');

            return redirect(route('assets.index'));
        }

        return view('admin.assets.show')->with('asset', $asset);
    }

    /**
     * Show the form for editing the specified Asset.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $asset = Asset::findOrFail($id);
        $all_shops=Shop::where('deleted_at',NULL)->get();
        $stores[]='Select Shop';
        foreach ($all_shops as $key => $value) {
            $stores[$value->id]=$value->name;
        }
        $asset_type=Asset_type::where('asset_id',$asset->id)->get();
        foreach ($asset_type as $key => $value) {
            $type=$value->name;
        }
        if (empty($asset)) {
            Flash::error('Asset not found');

            return redirect(route('assets.index'));
        }
        return view('admin.assets.edit')->with('asset', $asset)->with('shops', $stores)->with('shop_id',$asset->shop_id)->with('asset_type',$type);
    }

    /**
     * Update the specified Asset in storage.
     *
     * @param  int              $id
     * @param UpdateAssetRequest $request
     *
     * @return Response
     */
    public function update($id,Request $request)
    {
        $request->validate([
            'AssetName' => 'required',
            'AssetType' => 'required',
            'SelectShop' => 'required',
            'Description' => 'required'
            ]);
        $asset = Asset::find($id);

        $input['AssetName']=$request->AssetName;
        $input['shop_id']=$request->SelectShop;
        $input['Description']=$request->Description;
        $imgpath = 'storage/QrImage';
        File::makeDirectory($imgpath, $mode = 0777, true, true);
        $imgDestinationPath = $imgpath.'/';
        $old_image=$imgDestinationPath.$asset->QRCode;
        if (File::exists($old_image)) {
            File::delete($old_image);
        }
        $imgpath = storage_path('\QrImage');
        File::makeDirectory($imgpath, $mode = 0777, true, true);
        $file=$imgpath.'\\'.$request->AssetName.'.png';
        $input['QRCode']=$request->AssetName.'.png';
        QRCode::text($request->AssetName)->setOutfile($file)->png();
        $asset = Asset::whereId($id)->update($input);
        
        $asset_type=Asset_type::where('asset_id',$id)->get();
        foreach ($asset_type as $asset_type_key => $asset_type_value) {
            $asset_id=$asset_type_value->id;
        }
        $data['asset_id']=$id;
        $data['name']=$request->AssetType;
        $asset = Asset_type::whereId($asset_id)->update($data);


        

        if (empty($asset)) {
            Flash::error('Asset not found');

            return redirect(route('assets.index'));
        }
        Flash::success('Asset updated successfully.');

        return redirect(route('admin.assets.edit',['assets'=>$id]));
    }

    /**
     * Remove the specified Asset from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
      public function getModalDelete($id = null)
      {
          $error = '';
          $model = '';
          $confirm_route =  route('admin.assets.delete',['id'=>$id]);
          return View('admin.layouts/modal_confirmation', compact('error','model', 'confirm_route'));

      }

       public function getDelete($id = null)
       {
           $sample = Asset::destroy($id);

           // Redirect to the group management page
           return redirect(route('admin.assets.index'))->with('success', Lang::get('message.success.delete'));

       }

       public function getdata(Request $request) {
        $from=$request->dateFrom;
        $to=$request->dateTo;
        if(!empty($from) && !empty($to)) {
        $assets = Asset::whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59'])->where('deleted_at',NULL)->get();
        foreach ($assets as $key => $value) {
            $asset_type=Asset_type::where('asset_id',$value->id)->get();
            foreach ($asset_type as $asset_type_key => $asset_type_value) {
                $assets[$key]->AssetType.=$asset_type_value->name;
            }
            $asset_shop=Shop::where('id',$value->shop_id)->get();
            foreach ($asset_shop as $asset_shop_key => $asset_shop_value) {
                $assets[$key]->SelectShop.=$asset_shop_value->name;
                $asset_brand=Brand::where('id',$asset_shop_value->brand_id)->get();
                foreach ($asset_brand as $asset_brand_key => $asset_brand_value) {
                    $assets[$key]->Brand.=$asset_brand_value->BrandName;
                }
            }
        }
        }
        else
        {
            $assets = Asset::where('deleted_at',NULL)->get();
        foreach ($assets as $key => $value) {
            $asset_type=Asset_type::where('asset_id',$value->id)->get();
            foreach ($asset_type as $asset_type_key => $asset_type_value) {
                $assets[$key]->AssetType.=$asset_type_value->name;
            }
            $asset_shop=Shop::where('id',$value->shop_id)->get();
            foreach ($asset_shop as $asset_shop_key => $asset_shop_value) {
                $assets[$key]->SelectShop.=$asset_shop_value->name;
                $asset_brand=Brand::where('id',$asset_shop_value->brand_id)->get();
                foreach ($asset_brand as $asset_brand_key => $asset_brand_value) {
                    $assets[$key]->Brand.=$asset_brand_value->BrandName;
                }
            }
        }
        }
        return view('admin.assets.index')
            ->with('assets', $assets)->with('from',$from)->with('to',$to);
        
       }

}
