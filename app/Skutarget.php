<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Skutarget extends Model
{
    protected $fillable=['shop_id','Location','City','sku_id','skutargets','created_at','updated_at'];
}
