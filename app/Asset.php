<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $table = 'assets';
    
    protected $fillable=['AssetName','shop_id','brand_id','Description','QRCode'];
}
