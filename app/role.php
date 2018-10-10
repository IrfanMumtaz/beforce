<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class role extends Model
{
    protected $table = 'roles';

    public function employees()
    {
        return $this->hasManyThrough('App\Employee', 'App\Role_employee', 'role_id', 'id','id','emp_id');
    }
    

}
