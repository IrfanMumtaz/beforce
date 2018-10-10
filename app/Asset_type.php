<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Asset_type extends Model
{
    protected $table = 'asset_types';
    
    protected $fillable=['name','asset_id'];
}
