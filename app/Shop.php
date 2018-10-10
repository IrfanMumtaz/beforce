<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $table = 'shops';
    protected $fillable=[
    	'name',
    	'Ownername',
    	'Contactperson',
    	'Contactnumber',
    	'latitude',
    	'longitude',
    	'Storesize',
    	'brand_id',
    	'city_id'
    ];
    public function shops()
    {
        return $this->hasManyThrough('App\Brand', 'App\city', 'brand_id', 'id','id','emp_id');     
    }
}
