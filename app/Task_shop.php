<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task_shop extends Model
{
    protected $table='task_shops';
    protected $fillable=['task_id','shop_id'];
}
