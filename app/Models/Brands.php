<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class Brands extends Model
{
    use SoftDeletes;

    public $table = 'brands';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'BrandName',
        'Description'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'BrandName' => 'string',
        'Description' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
        'BrandName'=> 'required|unique:brands',
        // 'Description'=> 'required',
        // 'SelectManagers'=> 'required',
        // 'SelectSupervisors'=> 'required',
        // 'SelectMerchandisers'=> 'required',
        // 'SelectDamageVerification'=> 'required',
        // 'SelectEmloyee'=> 'required',
        'BrandCities'=> 'required'
    ];


public function Employee()
    {
       return $this->hasMany(Employee::class);
    }


}
