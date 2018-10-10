<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Categories extends Model
{
    use SoftDeletes;

    public $table = 'categories';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'Category',
        'Competition',
        'brand_id'

    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'Category' => 'string',
        'Competition' => 'boolean',
	    'brand_id' => 'interger'

    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

        'Category'=> 'required|unique:categories',
        'brand'=> 'required'
    ];

    public function categoryStatus(){
        $status = $this->select('Competition AS id', DB::raw('(CASE WHEN categories.Competition = 1 THEN "Competition Sale" ELSE "Own Sale" END) AS sale_type'))->groupBy('sale_type')->get();
        return $status;
    }
}
