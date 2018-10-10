<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role_employee extends Model
{
    protected $table = 'role_employees';
    protected $fillable=['emp_id','role_id'];
    
}
