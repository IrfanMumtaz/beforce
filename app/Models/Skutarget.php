<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



class Skutarget extends Model
{

    public $table = 'skutarget';
    


    public $guarded = ['id'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];
}
