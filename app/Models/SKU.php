<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class SKU extends Model
{
    use SoftDeletes;

    public $table = 'skus';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'category_id',
        'name',
        'Price',
        'ItemPerCarton',
        'SKUImage'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'CateId' => 'integer',
        'name' => 'string',
        'Price' => 'integer',
        'ItemPerCarton' => 'integer',
        'SKUImage' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'CateId' => 'required',
        'name' => 'required|unique:skus',
        'Price' => 'required'
    ];
}
