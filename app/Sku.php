<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sku extends Model
{
    protected $table = 'skus';

    protected $fillable=['category_id','name','Price','ItemPerCarton','SKUImage'];
}
