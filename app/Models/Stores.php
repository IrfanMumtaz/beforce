<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class Stores extends Model
{
    use SoftDeletes;

    public $table = 'shops';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'undefined',
        'name',
        'Ownername',
        'Contactperson',
        'Contactnumber',
        'latitude',
        'longitude',
        'Storesize',
        'brand_id',
        'city_id',
	'created_at'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'Ownername' => 'string',
        'Contactperson' => 'string',
        'Contactnumber' => 'string',
        'latitude' => 'numeric',
        'longitude' => 'numeric',
        'Storesize' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|unique:shops',
        'latitude'=> 'required',
        'longitude'=> 'required',
        'Storesize'=> 'required',
        'Region'=> 'required',
        'Storecity'=> 'required'
	    ];
}
