<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class Brand extends Model
{
    protected $table = 'brands';
    
    protected $fillable=['BrandName','Description'];
    public function employees()
    {
        return $this->hasMany('App\Brand_employee');
    }
    public function tags()
    {
        return $this->marphToMany('App\city','taggable');
    }
     public function cities()
    {
    	return $this->hasMany('App\Brand_city');

        return $this->hasManyThrough('App\city', 'App\Brand_city', 'brand_id', 'id','id','city_id');
        
    }
    public function employee()
    {
        return $this->hasManyThrough('App\Employee', 'App\Brand_employee', 'brand_id', 'id','id','emp_id');    	
    }
}
