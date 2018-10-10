<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class Asset extends Model
{
    use SoftDeletes;

    public $table = 'Assets';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'AssetName',
        'AssetType',
        'shop_id',
        'brand_id',
        'Description',
        'QRCode'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'AssetName' => 'string',
        'AssetType' => 'string',
        'Description' => 'string',
        'QRCode' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'AssetName' => 'required',
        'AssetType' => 'required',
        'SelectShop' => 'required',
        'Description' => 'required'
    ];
}
