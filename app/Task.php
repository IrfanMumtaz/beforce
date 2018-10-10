<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tasks';
    
    protected $fillable=['asset_id','assign_by','emp_id','Tasktype','StartDate','EndDate','status','description'];
}
