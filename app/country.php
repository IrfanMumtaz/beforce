<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class country extends Model
{
    protected $table = 'countries';

    protected $fillable = ['sortname','name'];

     public function states()
    {
        return $this->hasMany('App\state');
    }
     public function cities()
    {
        return $this->hasManyThrough('App\city', 'App\state');
    }
}
