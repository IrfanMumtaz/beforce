<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class state extends Model
{
    protected $table = 'states';

	protected $fillable = ['name','country_id'];
    //
}
