<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



class EBStag extends Model
{

    public $table = 'EBStag';
    


    public $fillable = [
        
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'empId' => 'integer',
        'brandId' => 'integer',
        'StoreId' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'empId' => 'required',
        'brandId' => 'required',
        'StoreId' => 'required'
    ];
}
